<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\ArkeselSmsService;
use App\Services\WhatsAppService;
use App\Services\GekyChatService;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $socialMedia = SiteSetting::where('group', SiteSetting::GROUP_SOCIAL_MEDIA)->get();
        $contact = SiteSetting::where('group', SiteSetting::GROUP_CONTACT)->get();
        $general = SiteSetting::where('group', SiteSetting::GROUP_GENERAL)->get();
        $notifications = SiteSetting::where('group', 'notifications')->get()->pluck('value', 'key');
        
        // Check SMS configuration
        $smsConfigured = !empty(config('services.arkesel.api_key'));
        
        // Check WhatsApp configuration (support both generic API and Meta Cloud API)
        $whatsappConfigured = (
            (!empty(config('services.whatsapp.api_key')) && !empty(config('services.whatsapp.api_url'))) ||
            (!empty(config('services.whatsapp.token')) && !empty(config('services.whatsapp.phone_number_id')))
        );
        
        $gekychatConfigured = !empty(config('services.gekychat.client_id')) && !empty(config('services.gekychat.client_secret'));

        return view('admin.settings.index', compact('socialMedia', 'contact', 'general', 'notifications', 'smsConfigured', 'whatsappConfigured', 'gekychatConfigured'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please check the form for errors.');
        }

        try {
            foreach ($request->settings as $key => $value) {
                $setting = SiteSetting::where('key', $key)->first();
                
                // If setting doesn't exist, create it (for notification settings)
                if (!$setting) {
                    $group = 'general';
                    $type = 'text';
                    
                    if (in_array($key, ['email_notifications_enabled', 'sms_notifications_enabled', 'registration_notification_enabled', 'event_notification_enabled', 'announcement_notification_enabled'])) {
                        $group = 'notifications';
                        $type = 'boolean';
                    }
                    
                    $setting = SiteSetting::create([
                        'key' => $key,
                        'value' => $value ?? '0',
                        'type' => $type,
                        'group' => $group,
                    ]);
                } else {
                    // Validate URL fields
                    if ($setting->type === SiteSetting::TYPE_URL && !empty($value)) {
                        if (!filter_var($value, FILTER_VALIDATE_URL)) {
                            return redirect()->back()
                                ->with('error', "Invalid URL format for {$setting->description}")
                                ->withInput();
                        }
                    }

                    // Validate email fields
                    if ($setting->type === SiteSetting::TYPE_EMAIL && !empty($value)) {
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            return redirect()->back()
                                ->with('error', "Invalid email format for {$setting->description}")
                                ->withInput();
                        }
                    }

                    // For checkboxes, set to '0' if not checked
                    if ($setting->type === 'boolean' && empty($value)) {
                        $value = '0';
                    }

                    $setting->update(['value' => $value ?? '0']);
                }
            }

            return redirect()->back()->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while updating settings: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Test notification channel
     */
    public function testNotification(Request $request)
    {
        $request->validate([
            'channel' => 'required|in:email,sms,whatsapp,gekychat',
            'email' => 'nullable|email',
            'phone' => 'nullable|string'
        ]);

        $user = auth()->user();
        $channel = $request->channel;
        $customEmail = $request->email;
        $customPhone = $request->phone;
        $message = "This is a test notification from STU Alumni Portal. Your {$channel} channel is working correctly!";

        try {
            switch ($channel) {
                case 'email':
                    $recipientEmail = $customEmail ?: $user->email;
                    
                    if (!$recipientEmail) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No email address provided. Please enter an email address.'
                        ]);
                    }

                    Mail::raw($message, function ($mail) use ($recipientEmail) {
                        $mail->to($recipientEmail)
                             ->subject('Test Notification - STU Alumni Portal');
                    });

                    return response()->json([
                        'success' => true,
                        'message' => "Test email sent successfully to {$recipientEmail}!"
                    ]);

                case 'sms':
                    if (!$customPhone) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Phone number is required for SMS testing. Please enter a phone number.'
                        ]);
                    }

                    // Check if Arkesel is configured
                    if (empty(config('services.arkesel.api_key'))) {
                        return response()->json([
                            'success' => false,
                            'message' => 'SMS provider (Arkesel) is not configured. Set ARKESEL_SMS_API_KEY in .env'
                        ]);
                    }

                    try {
                        $response = ArkeselSmsService::send(
                            $customPhone,
                            $message,
                            config('services.arkesel.sender')
                        );

                        if ($response['success']) {
                            return response()->json([
                                'success' => true,
                                'message' => "SMS test sent successfully to {$customPhone} via Arkesel!"
                            ]);
                        } else {
                            return response()->json([
                                'success' => false,
                                'message' => 'SMS send failed: ' . json_encode($response['data'] ?? 'Unknown error')
                            ]);
                        }
                    } catch (\Exception $e) {
                        return response()->json([
                            'success' => false,
                            'message' => 'SMS send error: ' . $e->getMessage()
                        ]);
                    }

                case 'whatsapp':
                    // Check if WhatsApp is configured (support both generic API and Meta Cloud API)
                    if (!$this->isWhatsAppConfigured()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'WhatsApp is not configured. Add WHATSAPP_API_KEY and WHATSAPP_API_URL (or WHATSAPP_TOKEN and WHATSAPP_PHONE_NUMBER_ID for Meta Cloud API) to .env'
                        ]);
                    }

                    if (!$customPhone) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Phone number is required for WhatsApp testing. Please enter a phone number.'
                        ]);
                    }

                    // Format phone number
                    $phone = $this->formatPhoneNumber($customPhone);
                    
                    try {
                        $whatsappService = app(WhatsAppService::class);
                        
                        // For Meta Cloud API, use default template if configured
                        // Free-form messages only work within 24h window after user messages you
                        $defaultTemplate = config('services.whatsapp.default_template');
                        if ($defaultTemplate) {
                            // Get template params based on template structure
                            $paramCount = (int) config('services.whatsapp.default_template_params_count', 1);
                            $params = $paramCount > 0 ? array_fill(0, $paramCount, $message) : [];
                            
                            // Use default template with appropriate params
                            $response = $whatsappService->sendTemplate(
                                $phone, 
                                $defaultTemplate, 
                                config('services.whatsapp.template_locale', 'en_US'), 
                                $params
                            );
                        } else {
                            // Try free-form (may fail if not in 24h window)
                            $response = $whatsappService->sendMessage($phone, $message);
                        }

                        if ($response['success']) {
                            $messageId = $response['messages'][0]['id'] ?? 'N/A';
                            $templateInfo = $defaultTemplate ? " (using template: {$defaultTemplate})" : "";
                            return response()->json([
                                'success' => true,
                                'message' => "WhatsApp test sent successfully to {$phone}{$templateInfo}! Message ID: {$messageId}"
                            ]);
                        } else {
                            $errorMsg = $response['error']['message'] ?? 'Unknown error';
                            if (!$defaultTemplate) {
                                $errorMsg .= ' Note: Free-form messages only work within 24h window. Consider setting WHATSAPP_DEFAULT_TEMPLATE in .env';
                            }
                            return response()->json([
                                'success' => false,
                                'message' => 'WhatsApp send failed: ' . $errorMsg
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('WhatsApp test send failed', [
                            'phone' => $phone,
                            'error' => $e->getMessage()
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => 'WhatsApp send error: ' . $e->getMessage()
                        ]);
                    }

                case 'gekychat':
                    if (!$this->isGekyChatConfigured()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'GekyChat is not configured. Add GEKYCHAT_CLIENT_ID and GEKYCHAT_CLIENT_SECRET to .env'
                        ]);
                    }

                    if (!$customPhone) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Phone number is required for GekyChat testing. Please enter a phone number.'
                        ]);
                    }

                    try {
                        $gekychatService = new GekyChatService();
                        $result = $gekychatService->sendMessageByPhone($customPhone, $message);
                        
                        if ($result['success'] ?? false) {
                            return response()->json([
                                'success' => true,
                                'message' => "GekyChat test message sent successfully to {$customPhone}"
                            ]);
                        } else {
                            $error = $result['error'] ?? 'Unknown error';
                            return response()->json([
                                'success' => false,
                                'message' => 'GekyChat send failed: ' . $error
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('GekyChat test send failed', [
                            'phone' => $customPhone,
                            'error' => $e->getMessage()
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => 'GekyChat send failed: ' . $e->getMessage()
                        ]);
                    }

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid channel specified.'
                    ]);
            }
        } catch (\Exception $e) {
            Log::error('Test notification failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check if WhatsApp is configured (support both generic API and Meta Cloud API)
     */
    private function isWhatsAppConfigured()
    {
        // Support both generic API and Meta Cloud API
        return (
            (!empty(config('services.whatsapp.api_key')) && !empty(config('services.whatsapp.api_url'))) ||
            (!empty(config('services.whatsapp.token')) && !empty(config('services.whatsapp.phone_number_id')))
        );
    }

    /**
     * Check if GekyChat is configured
     */
    private function isGekyChatConfigured()
    {
        return !empty(config('services.gekychat.client_id')) && !empty(config('services.gekychat.client_secret'));
    }

    /**
     * Format phone number for API (remove spaces, add country code if needed)
     */
    private function formatPhoneNumber($phone)
    {
        // Remove spaces, dashes, and parentheses
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);
        
        // Add country code if not present (assuming Ghana +233)
        if (!str_starts_with($phone, '+')) {
            if (str_starts_with($phone, '0')) {
                $phone = '+233' . substr($phone, 1);
            } else {
                $phone = '+233' . $phone;
            }
        }
        
        return $phone;
    }
}

