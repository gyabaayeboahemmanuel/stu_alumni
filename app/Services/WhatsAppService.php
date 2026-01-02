<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WhatsAppService
{
    protected string $apiVersion;
    protected string $base;
    protected string $token;
    protected string $phoneNumberId;
    protected ?string $apiKey;
    protected ?string $apiUrl;

    public function __construct()
    {
        // Meta Cloud API (CUG style)
        $this->apiVersion    = (string) config('services.whatsapp.api_version', 'v21.0');
        $this->base          = rtrim((string) config('services.whatsapp.api_base', 'https://graph.facebook.com'), '/') . '/' . $this->apiVersion;
        $this->token         = (string) config('services.whatsapp.token', '');
        $this->phoneNumberId = (string) config('services.whatsapp.phone_number_id', '');
        
        // Generic API (fallback)
        $this->apiKey = config('services.whatsapp.api_key');
        $this->apiUrl = config('services.whatsapp.api_url');
    }

    /**
     * Normalize phone numbers to strict E.164 format (GH-first rules by default).
     */
    public static function normalizeMsisdn(string $raw, string $defaultRegion = 'GH'): string
    {
        $raw = trim($raw);
        if ($raw === '') return '';

        // keep only + and digits
        $clean = preg_replace('/[^\d+]/', '', $raw) ?? '';

        // 00 prefix -> +
        if (str_starts_with($clean, '00')) {
            $clean = '+' . substr($clean, 2);
        }

        // already starts with +
        if (str_starts_with($clean, '+')) {
            $digits = substr($clean, 1);
            $digits = preg_replace('/\D/', '', $digits) ?? '';
            return '+' . $digits;
        }

        // Ghana rules
        if (strtoupper($defaultRegion) === 'GH') {
            if (preg_match('/^233(\d{9})$/', $clean, $m)) return '+233' . $m[1];
            if (preg_match('/^0(\d{9})$/',   $clean, $m)) return '+233' . $m[1];
            if (preg_match('/^(\d{9})$/',    $clean, $m)) return '+233' . $m[1];
        }

        // other internationals without +
        if (preg_match('/^\d{10,15}$/', $clean)) {
            return '+' . $clean;
        }

        // last resort
        $digits = preg_replace('/\D/', '', $clean) ?? '';
        return $digits ? '+' . $digits : '';
    }

    /**
     * Send a free-form text message via WhatsApp.
     * Supports both Meta Cloud API and generic API.
     * For Meta Cloud API, uses default template if configured, otherwise requires forceTemplate.
     *
     * @return array{success:bool,messages?:array,error?:array}
     */
    public function sendMessage(
        string $to,
        string $message = '',
        bool $forceTemplate = false,
        ?string $templateName = null,
        string $language = 'en_US',
        array $params = []
    ): array {
        $e164 = self::normalizeMsisdn($to);

        // Use Meta Cloud API if configured
        if (!empty($this->token) && !empty($this->phoneNumberId)) {
            // For Meta Cloud API, try to use default template if no template specified
            if (!$forceTemplate && !$templateName) {
                $defaultTemplate = config('services.whatsapp.default_template');
                if ($defaultTemplate) {
                    // Get default template params (can be empty array if template has no variables)
                    $defaultParams = $this->getDefaultTemplateParams($message);
                    return $this->sendViaMetaCloudAPI($e164, $message, true, $defaultTemplate, $language, $defaultParams);
                }
            }
            return $this->sendViaMetaCloudAPI($e164, $message, $forceTemplate, $templateName, $language, $params);
        }

        // Fallback to generic API
        if (!empty($this->apiKey) && !empty($this->apiUrl)) {
            return $this->sendViaGenericAPI($e164, $message);
        }

        return [
            'success' => false,
            'error'   => ['code' => 500, 'message' => 'WhatsApp is not configured. Set WHATSAPP_TOKEN and WHATSAPP_PHONE_NUMBER_ID (or WHATSAPP_API_KEY and WHATSAPP_API_URL) in .env'],
        ];
    }

    /**
     * Get default template parameters based on config
     * If template expects parameters, use message; otherwise return empty array
     */
    protected function getDefaultTemplateParams(string $message): array
    {
        // Check if there's a config for template param count
        $paramCount = (int) config('services.whatsapp.default_template_params_count', 1);
        
        if ($paramCount === 0) {
            // Template has no body variables
            return [];
        }
        
        // Template expects parameters - use message for first param, or replicate for all
        return array_fill(0, $paramCount, $message);
    }

    /**
     * Send a template message via WhatsApp (convenience method)
     *
     * @return array{success:bool,messages?:array,error?:array}
     */
    public function sendTemplate(string $to, string $templateName, string $language = 'en_US', array $params = []): array
    {
        return $this->sendMessage($to, '', true, $templateName, $language, $params);
    }

    /**
     * Send via Meta Cloud API (WhatsApp Business API)
     */
    protected function sendViaMetaCloudAPI(
        string $to,
        string $message,
        bool $forceTemplate = false,
        ?string $templateName = null,
        string $language = 'en_US',
        array $params = []
    ): array {
        if ($forceTemplate && $templateName) {
            $components = [];
            if (!empty($params)) {
                $components[] = [
                    'type'       => 'body',
                    'parameters' => array_map(
                        fn($p) => ['type' => 'text', 'text' => (string) $p],
                        $params
                    ),
                ];
            }

            // Try the requested language first
            $response = $this->trySendTemplate($to, $templateName, $language, $components);
            
            // If language doesn't match, try common alternatives
            if (!$response['success'] && isset($response['error']['code']) && $response['error']['code'] == 132001) {
                $alternatives = $this->getLanguageAlternatives($language);
                foreach ($alternatives as $altLang) {
                    $response = $this->trySendTemplate($to, $templateName, $altLang, $components);
                    if ($response['success']) {
                        return $response;
                    }
                }
                
                // If all failed, return original error with helpful message
                return [
                    'success' => false,
                    'error' => [
                        'code' => $response['error']['code'],
                        'message' => $response['error']['message'] . ' (Tried languages: ' . implode(', ', array_merge([$language], $alternatives)) . ')',
                    ],
                ];
            }
            
            return $response;
        }

        // Free-form text (24-hour window - only works if user messaged you recently)
        // For outbound messages without template, Meta API will fail
        // So we should use default template instead
        $defaultTemplate = config('services.whatsapp.default_template');
        if ($defaultTemplate && !$forceTemplate) {
            // Use default template with message as parameter
            $components = [
                [
                    'type'       => 'body',
                    'parameters' => [['type' => 'text', 'text' => $message]],
                ],
            ];

            $payload = [
                'messaging_product' => 'whatsapp',
                'to'                => $to,
                'type'              => 'template',
                'template'          => [
                    'name'       => $defaultTemplate,
                    'language'   => ['code' => $language],
                    'components' => $components,
                ],
            ];

            return $this->post("/{$this->phoneNumberId}/messages", $payload);
        }

        // Free-form text (will fail if not in 24h window)
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $to,
            'type'              => 'text',
            'text'              => [
                'preview_url' => true,
                'body'        => $message,
            ],
        ];

        return $this->post("/{$this->phoneNumberId}/messages", $payload);
    }

    /**
     * Try sending a template with a specific language code
     */
    protected function trySendTemplate(string $to, string $templateName, string $language, array $components): array
    {
        $templateData = [
            'name'     => $templateName,
            'language' => ['code' => $language],
        ];
        
        // Only add components if they exist and are not empty
        // If template has no body variables, don't send components at all
        if (!empty($components)) {
            $templateData['components'] = $components;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to'                => $to,
            'type'              => 'template',
            'template'          => $templateData,
        ];

        return $this->post("/{$this->phoneNumberId}/messages", $payload);
    }

    /**
     * Get alternative language codes to try if the primary fails
     */
    protected function getLanguageAlternatives(string $primary): array
    {
        $map = [
            'en_US' => ['en', 'en_GB'],
            'en_GB' => ['en', 'en_US'],
            'en'    => ['en_US', 'en_GB'],
        ];

        return $map[$primary] ?? ['en', 'en_US', 'en_GB'];
    }

    /**
     * Send via generic API (third-party providers)
     */
    protected function sendViaGenericAPI(string $to, string $message): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(30)
            ->post($this->apiUrl, [
                'to'      => $to,
                'message' => $message,
            ]);

            if ($response->successful()) {
                $json = $response->json() ?? [];
                return ['success' => true, 'messages' => [['id' => $json['id'] ?? null]]];
            }

            return [
                'success' => false,
                'error'   => [
                    'code'    => $response->status(),
                    'message' => $response->json('error.message') ?? $response->body(),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error'   => [
                    'code'    => 500,
                    'message' => $e->getMessage(),
                ],
            ];
        }
    }

    /**
     * POST helper with consistent success/error envelope (for Meta Cloud API).
     *
     * @return array{success:bool,messages?:array,error?:array}
     */
    protected function post(string $endpoint, array $payload): array
    {
        $url = Str::startsWith($endpoint, 'http') ? $endpoint : "{$this->base}" . $endpoint;

        $resp = Http::withToken($this->token)->post($url, $payload);

        if ($resp->successful()) {
            $j = $resp->json() ?? [];
            return ['success' => true] + $j;
        }

        return [
            'success' => false,
            'error'   => [
                'code'    => $resp->status(),
                'message' => $resp->json('error.message') ?? $resp->body(),
                'raw'     => $resp->json(),
            ],
        ];
    }
}

