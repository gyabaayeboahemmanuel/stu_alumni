<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\User;
use App\Models\Notification as NotificationModel;
use App\Models\Chapter;
use App\Models\YearGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\ArkeselSmsService;
use App\Services\WhatsAppService;
use App\Services\GekyChatService;

class BroadcastController extends Controller
{
    /**
     * Show broadcast message form
     */
    public function index()
    {
        $chapters = Chapter::active()->orderBy('name')->get();
        $yearGroups = YearGroup::active()->orderBy('start_year', 'desc')->get();
        $recentBroadcasts = NotificationModel::where('type', NotificationModel::TYPE_BROADCAST)
            ->latest()
            ->take(10)
            ->get();
        
        // Check which channels are configured
        $smsConfigured = !empty(config('services.arkesel.api_key'));
        
        // Check WhatsApp configuration (support both generic API and Meta Cloud API)
        $whatsappConfigured = (
            (!empty(config('services.whatsapp.api_key')) && !empty(config('services.whatsapp.api_url'))) ||
            (!empty(config('services.whatsapp.token')) && !empty(config('services.whatsapp.phone_number_id')))
        );
        
        $gekychatConfigured = !empty(config('services.gekychat.client_id')) && !empty(config('services.gekychat.client_secret'));
        
        return view('admin.broadcast.index', compact('chapters', 'yearGroups', 'recentBroadcasts', 'smsConfigured', 'whatsappConfigured', 'gekychatConfigured'));
    }

    /**
     * Search users for custom list
     */
    public function searchUsers(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $alumni = Alumni::with('user')
            ->where(function($q) use ($query) {
                $q->where('student_id', 'LIKE', "%{$query}%")
                  ->orWhere('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%")
                  ->orWhereHas('user', function($userQuery) use ($query) {
                      $userQuery->where('email', 'LIKE', "%{$query}%");
                  });
            })
            ->verified()
            ->limit(20)
            ->get();

        return response()->json($alumni->map(function($alumnus) {
            return [
                'id' => $alumnus->id,
                'student_id' => $alumnus->student_id,
                'name' => $alumnus->full_name,
                'email' => $alumnus->user->email ?? $alumnus->email,
                'phone' => $alumnus->phone,
                'display' => $alumnus->full_name . ($alumnus->student_id ? ' (' . $alumnus->student_id . ')' : '') . ($alumnus->user->email ? ' - ' . $alumnus->user->email : ''),
            ];
        }));
    }

    /**
     * Send broadcast message
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_type' => 'required|in:all,chapter,year_group,custom',
            'chapter_id' => 'required_if:recipient_type,chapter|nullable|exists:chapters,id',
            'year_group_id' => 'required_if:recipient_type,year_group|nullable|exists:year_groups,id',
            'custom_emails' => 'required_if:recipient_type,custom|nullable|string',
            'channel' => 'required|in:email,sms,whatsapp,gekychat,both,all',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please check the form for errors.');
        }

        try {
            // Get recipients based on type
            $recipients = $this->getRecipients($request);

            if ($recipients->isEmpty()) {
                return redirect()->back()
                    ->with('warning', 'No recipients found matching your criteria.')
                    ->withInput();
            }

            $sentCount = 0;
            $failedCount = 0;

            foreach ($recipients as $alumni) {
                $channels = $this->getChannels($request->channel);
                $sentVia = [];
                
                try {
                    // Send via selected channel(s)
                    if (in_array('email', $channels)) {
                        $this->sendEmail($alumni, $request->subject, $request->message);
                        $sentVia[] = 'email';
                    }

                    if (in_array('sms', $channels)) {
                        $this->sendSMS($alumni, $request->message);
                        $sentVia[] = 'sms';
                    }

                    if (in_array('whatsapp', $channels) && $this->isWhatsAppConfigured()) {
                        $this->sendWhatsApp($alumni, $request->message);
                        $sentVia[] = 'whatsapp';
                    }

                    if (in_array('gekychat', $channels) && $this->isGekyChatConfigured()) {
                        $this->sendGekyChat($alumni, $request->message);
                        $sentVia[] = 'gekychat';
                    }

                    // Log notification
                    NotificationModel::create([
                        'type' => NotificationModel::TYPE_BROADCAST,
                        'recipient' => $alumni->user->email ?? $alumni->phone,
                        'subject' => $request->subject,
                        'content' => $request->message,
                        'sent_via' => implode(',', $sentVia),
                        'status' => NotificationModel::STATUS_SENT,
                        'sent_at' => now(),
                    ]);

                    $sentCount++;
                } catch (\Exception $e) {
                    Log::error('Broadcast failed for alumni ' . $alumni->id . ': ' . $e->getMessage());
                    $failedCount++;
                    
                    // Log failed notification
                    NotificationModel::create([
                        'type' => NotificationModel::TYPE_BROADCAST,
                        'recipient' => $alumni->user->email ?? $alumni->phone,
                        'subject' => $request->subject,
                        'content' => $request->message,
                        'sent_via' => implode(',', $sentVia ?: $channels),
                        'status' => NotificationModel::STATUS_FAILED,
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }

            $message = "Broadcast sent successfully! Sent: {$sentCount}";
            if ($failedCount > 0) {
                $message .= ", Failed: {$failedCount}";
            }

            return redirect()->route('admin.broadcast.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Broadcast error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while sending broadcast: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get recipients based on criteria
     */
    private function getRecipients(Request $request)
    {
        $query = Alumni::with('user');

        switch ($request->recipient_type) {
            case 'all':
                // All verified alumni
                $query->where('verification_status', 'verified');
                break;

            case 'chapter':
                // Alumni in specific chapter
                $query->where('chapter_id', $request->chapter_id)
                      ->where('verification_status', 'verified');
                break;

            case 'year_group':
                // Alumni in specific year group
                $yearGroup = YearGroup::find($request->year_group_id);
                if ($yearGroup) {
                    $query->whereBetween('year_of_completion', [$yearGroup->start_year, $yearGroup->end_year])
                          ->where('verification_status', 'verified');
                }
                break;

            case 'custom':
                // Custom email list - can contain emails or user IDs
                $customInput = array_filter(array_map('trim', explode(',', $request->custom_emails)));
                $emails = [];
                $userIds = [];
                
                foreach ($customInput as $item) {
                    if (is_numeric($item)) {
                        // If it's a number, treat as user ID
                        $userIds[] = $item;
                    } else {
                        // Otherwise treat as email
                        $emails[] = $item;
                    }
                }
                
                $query->where(function($q) use ($emails, $userIds) {
                    if (!empty($emails)) {
                        $q->whereHas('user', function ($userQuery) use ($emails) {
                            $userQuery->whereIn('email', $emails);
                        });
                    }
                    if (!empty($userIds)) {
                        $q->orWhereIn('id', $userIds);
                    }
                })->where('verification_status', 'verified');
                break;
        }

        return $query->get();
    }

    /**
     * Send email notification
     */
    private function sendEmail($alumni, $subject, $message)
    {
        if (!$alumni->user || !$alumni->user->email) {
            throw new \Exception('No email address found for alumni');
        }

        // Simple email sending (you can create a proper Mailable class)
        Mail::raw($message, function ($mail) use ($alumni, $subject) {
            $mail->to($alumni->user->email)
                 ->subject($subject);
        });
    }

    /**
     * Send SMS notification
     */
    private function sendSMS($alumni, $message)
    {
        if (!$alumni->phone) {
            throw new \Exception('No phone number found for alumni');
        }

        // Check if Arkesel is configured
        if (empty(config('services.arkesel.api_key'))) {
            throw new \Exception('SMS provider (Arkesel) is not configured. Set ARKESEL_SMS_API_KEY in .env');
        }

        try {
            $response = ArkeselSmsService::send(
                $alumni->phone,
                $message,
                config('services.arkesel.sender')
            );

            if (!$response['success']) {
                throw new \Exception('Arkesel SMS send failed: ' . json_encode($response['data'] ?? 'Unknown error'));
            }

            Log::info('SMS sent successfully via Arkesel', [
                'phone' => $alumni->phone,
                'response' => $response
            ]);
        } catch (\Exception $e) {
            Log::error('SMS send failed', [
                'phone' => $alumni->phone,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Send WhatsApp notification
     */
    private function sendWhatsApp($alumni, $message)
    {
        if (!$alumni->phone) {
            throw new \Exception('No phone number found for alumni');
        }

        // Check if WhatsApp is configured
        if (!$this->isWhatsAppConfigured()) {
            throw new \Exception('WhatsApp is not configured in .env');
        }

        try {
            $whatsappService = app(WhatsAppService::class);
            
            // For Meta Cloud API, always use template (default or specified)
            // Free-form messages only work within 24h window after user messages you
            $defaultTemplate = config('services.whatsapp.default_template');
            if ($defaultTemplate) {
                // Get template params based on template structure
                $paramCount = (int) config('services.whatsapp.default_template_params_count', 1);
                $params = $paramCount > 0 ? array_fill(0, $paramCount, $message) : [];
                
                // Use default template with appropriate params
                $response = $whatsappService->sendTemplate($alumni->phone, $defaultTemplate, config('services.whatsapp.template_locale', 'en_US'), $params);
            } else {
                // Try free-form (may fail if not in 24h window)
                $response = $whatsappService->sendMessage($alumni->phone, $message);
            }

            if (!$response['success']) {
                $errorMsg = $response['error']['message'] ?? 'Unknown error';
                throw new \Exception('WhatsApp send failed: ' . $errorMsg);
            }

            $messageId = $response['messages'][0]['id'] ?? null;
            Log::info('WhatsApp sent successfully', [
                'phone' => $alumni->phone,
                'message_id' => $messageId,
                'response' => $response
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsApp send failed', [
                'phone' => $alumni->phone,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Send GekyChat notification
     */
    private function sendGekyChat($alumni, $message)
    {
        if (!$alumni->phone) {
            throw new \Exception('No phone number found for alumni');
        }

        // Check if GekyChat is configured
        if (!$this->isGekyChatConfigured()) {
            throw new \Exception('GekyChat is not configured in .env');
        }

        if (empty($alumni->phone)) {
            throw new \Exception('Alumni phone number is missing');
        }

        try {
            $gekychatService = new GekyChatService();
            $result = $gekychatService->sendMessageByPhone($alumni->phone, $message, [
                'alumni_id' => $alumni->id,
                'alumni_name' => $alumni->name ?? 'Alumni',
            ]);

            if (!($result['success'] ?? false)) {
                $error = $result['error'] ?? 'Unknown error';
                Log::error('GekyChat send failed', [
                    'alumni_id' => $alumni->id,
                    'phone' => $alumni->phone,
                    'error' => $error
                ]);
                throw new \Exception('GekyChat API error: ' . $error);
            }

            Log::info('GekyChat sent successfully', [
                'alumni_id' => $alumni->id,
                'phone' => $alumni->phone,
                'message_id' => $result['message_id'] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('GekyChat send failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if WhatsApp is configured
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
     * Get channels array from channel selection
     */
    private function getChannels($channel)
    {
        switch ($channel) {
            case 'both':
                return ['email', 'sms'];
            case 'all':
                $channels = ['email', 'sms'];
                if ($this->isWhatsAppConfigured()) {
                    $channels[] = 'whatsapp';
                }
                if ($this->isGekyChatConfigured()) {
                    $channels[] = 'gekychat';
                }
                return $channels;
            default:
                return [$channel];
        }
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

