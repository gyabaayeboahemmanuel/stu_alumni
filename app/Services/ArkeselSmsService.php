<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ArkeselSmsService
{
    /**
     * Send plain SMS (same message to many recipients) via Arkesel V2.
     *
     * @param  string|array            $to            Single MSISDN or array of MSISDNs
     * @param  string                  $message       Message content
     * @param  string|null             $sender        Max 11 chars (defaults to services.arkesel.sender)
     * @param  string|null             $callbackUrl   Optional delivery webhook URL
     * @param  \DateTimeInterface|null $scheduledAt   Optional schedule (format 'Y-m-d h:i A' per API)
     * @param  bool|null               $sandbox       Optional sandbox flag (defaults to config)
     * @return array{success:bool,http:int,data:array|null}
     * @throws \Exception
     */
    public static function send(
        string|array $to,
        string $message,
        ?string $sender = null,
        ?string $callbackUrl = null,
        ?\DateTimeInterface $scheduledAt = null,
        ?bool $sandbox = null
    ): array {
        $apiKey = (string) config('services.arkesel.api_key');
        $base   = rtrim((string) config('services.arkesel.base_url', 'https://sms.arkesel.com/api/v2'), '/');

        if ($apiKey === '') {
            throw new \Exception('Arkesel API key missing. Set ARKESEL_SMS_API_KEY in .env');
        }

        $recipients = self::normalizeMany($to);
        if (empty($recipients)) {
            throw new \Exception('No valid recipients.');
        }

        $payload = [
            'sender'     => substr($sender ?: (config('services.arkesel.sender') ?: 'STU Alumni'), 0, 11),
            'message'    => $message,
            'recipients' => array_values($recipients),
        ];

        if ($callbackUrl) {
            $payload['callback_url'] = $callbackUrl;
        }

        if ($scheduledAt) {
            // Arkesel expects 12-hour format with AM/PM.
            $payload['scheduled_date'] = $scheduledAt->format('Y-m-d h:i A');
        }

        $payload['sandbox'] = $sandbox ?? (bool) config('services.arkesel.sandbox', false);

        $url = $base . '/sms/send';

        try {
            $res = Http::withHeaders(['api-key' => $apiKey])
                ->acceptJson()
                ->asJson()
                ->connectTimeout(5)
                ->timeout(12)
                ->retry(2, 200) // two quick retries on transient errors
                ->post($url, $payload);

            $json = $res->json() ?? [];
            $ok   = $res->successful() && strtolower((string)($json['status'] ?? '')) === 'success';

            if ($ok) {
                Log::info('Arkesel V2 send success', [
                    'http'  => $res->status(),
                    'count' => count($recipients),
                    'data'  => $json,
                ]);
            } else {
                Log::error('Arkesel V2 send failed', [
                    'http' => $res->status(),
                    'data' => $json,
                ]);
            }

            return ['success' => $ok, 'http' => $res->status(), 'data' => $json];
        } catch (\Throwable $e) {
            Log::error('Arkesel V2 exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Attempt to extract a message id from a V2 response.
     */
    public static function extractMessageId(?array $response): ?string
    {
        if (!$response) return null;

        $data = $response['data'] ?? null;

        if (is_string($data)) {
            return $data;
        }

        if (is_array($data)) {
            foreach ($data as $item) {
                if (is_array($item)) {
                    foreach (['sms_id', 'id', 'message_id', 'uuid'] as $k) {
                        if (!empty($item[$k])) return (string) $item[$k];
                    }
                } elseif (is_string($item)) {
                    return $item;
                }
            }
        }

        foreach (['sms_id', 'id', 'message_id', 'uuid'] as $k) {
            if (!empty($response[$k])) return (string) $response[$k];
        }

        return null;
    }

    /* ==================== Helpers ==================== */

    /**
     * Normalize many numbers into E.164-like strings (best-effort).
     *
     * @param  string|array $to
     * @return array<int,string>
     */
    protected static function normalizeMany(string|array $to): array
    {
        $arr = is_array($to) ? $to : [$to];

        return collect($arr)
            ->map(fn ($n) => self::normalizeOne((string) $n))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Normalize a single number. Special handling for Ghana:
     * - 0XXXXXXXXX  => 233XXXXXXXXX
     * - 9 digits     => 233 + digits
     * - already 233XXXXXXXXX (>=12) is kept
     *
     * @param  string|null $number
     * @return string|null
     */
    protected static function normalizeOne(?string $number): ?string
    {
        if (!$number) return null;

        $n = ltrim(trim($number), '+');
        $d = preg_replace('/\D+/', '', $n);
        if ($d === '') return null;

        // Ghana helpers:
        if (str_starts_with($d, '233') && strlen($d) >= 12) return $d;
        if (str_starts_with($d, '0') && strlen($d) === 10)  return '233' . substr($d, 1);
        if (strlen($d) === 9)                                return '233' . $d;

        // Otherwise assume already international-format digits
        return $d;
    }
}

