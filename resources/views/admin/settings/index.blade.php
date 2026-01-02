@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Site Settings</h1>
            <p class="mt-2 text-gray-600">Manage your website settings, social media links, and contact information</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Social Media Settings -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-stu-green to-stu-green-dark px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-share-alt mr-3"></i>
                                Social Media Links
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach($socialMedia as $setting)
                                <div>
                                    <label for="{{ $setting->key }}" class="form-label flex items-center">
                                        @if(str_contains($setting->key, 'facebook'))
                                            <i class="fab fa-facebook text-blue-600 mr-2"></i>
                                        @elseif(str_contains($setting->key, 'twitter'))
                                            <i class="fab fa-twitter text-sky-500 mr-2"></i>
                                        @elseif(str_contains($setting->key, 'linkedin'))
                                            <i class="fab fa-linkedin text-blue-700 mr-2"></i>
                                        @elseif(str_contains($setting->key, 'instagram'))
                                            <i class="fab fa-instagram text-pink-600 mr-2"></i>
                                        @elseif(str_contains($setting->key, 'youtube'))
                                            <i class="fab fa-youtube text-red-600 mr-2"></i>
                                        @endif
                                        {{ $setting->description }}
                                    </label>
                                    <input 
                                        type="url" 
                                        id="{{ $setting->key }}" 
                                        name="settings[{{ $setting->key }}]" 
                                        value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                        class="form-input"
                                        placeholder="https://{{ str_replace('_url', '', $setting->key) }}.com/your-profile">
                                    @error('settings.' . $setting->key)
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-medium">Social Media Tips:</p>
                                        <ul class="mt-2 space-y-1 list-disc list-inside">
                                            <li>Enter full URLs including https://</li>
                                            <li>Leave fields empty to hide social media icons</li>
                                            <li>Test links after saving to ensure they work</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mt-6">
                        <div class="bg-gradient-to-r from-stu-red to-stu-red-light px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-address-book mr-3"></i>
                                Contact Information
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach($contact as $setting)
                                <div>
                                    <label for="{{ $setting->key }}" class="form-label flex items-center">
                                        @if(str_contains($setting->key, 'email'))
                                            <i class="fas fa-envelope text-stu-green mr-2"></i>
                                        @elseif(str_contains($setting->key, 'phone'))
                                            <i class="fas fa-phone text-stu-green mr-2"></i>
                                        @elseif(str_contains($setting->key, 'address'))
                                            <i class="fas fa-map-marker-alt text-stu-red mr-2"></i>
                                        @endif
                                        {{ $setting->description }}
                                    </label>
                                    @if($setting->type === 'textarea')
                                        <textarea 
                                            id="{{ $setting->key }}" 
                                            name="settings[{{ $setting->key }}]" 
                                            rows="3"
                                            class="form-textarea"
                                            placeholder="Enter {{ strtolower($setting->description) }}">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                                    @else
                                        <input 
                                            type="{{ $setting->type }}" 
                                            id="{{ $setting->key }}" 
                                            name="settings[{{ $setting->key }}]" 
                                            value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                            class="form-input"
                                            placeholder="Enter {{ strtolower($setting->description) }}">
                                    @endif
                                    @error('settings.' . $setting->key)
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Notification Settings -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mt-6">
                        <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-bell mr-3"></i>
                                Notification Settings
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex-1">
                                        <label for="email_notifications_enabled" class="text-sm font-medium text-gray-900 flex items-center">
                                            <i class="fas fa-envelope text-blue-600 mr-2"></i>
                                            Email Notifications
                                        </label>
                                        <p class="text-xs text-gray-500 mt-1">Enable email notifications system-wide</p>
                                    </div>
                                    <input type="checkbox" 
                                           id="email_notifications_enabled" 
                                           name="settings[email_notifications_enabled]" 
                                           value="1"
                                           {{ old('settings.email_notifications_enabled', $notifications['email_notifications_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                           class="h-5 w-5 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                                </div>

                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex-1">
                                        <label for="sms_notifications_enabled" class="text-sm font-medium text-gray-900 flex items-center">
                                            <i class="fas fa-sms text-purple-600 mr-2"></i>
                                            SMS Notifications
                                        </label>
                                        <p class="text-xs text-gray-500 mt-1">Enable SMS notifications system-wide</p>
                                    </div>
                                    <input type="checkbox" 
                                           id="sms_notifications_enabled" 
                                           name="settings[sms_notifications_enabled]" 
                                           value="1"
                                           {{ old('settings.sms_notifications_enabled', $notifications['sms_notifications_enabled'] ?? '0') == '1' ? 'checked' : '' }}
                                           class="h-5 w-5 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                                </div>

                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex-1">
                                        <label for="registration_notification_enabled" class="text-sm font-medium text-gray-900 flex items-center">
                                            <i class="fas fa-user-plus text-green-600 mr-2"></i>
                                            Registration Notifications
                                        </label>
                                        <p class="text-xs text-gray-500 mt-1">Send notifications for new registrations</p>
                                    </div>
                                    <input type="checkbox" 
                                           id="registration_notification_enabled" 
                                           name="settings[registration_notification_enabled]" 
                                           value="1"
                                           {{ old('settings.registration_notification_enabled', $notifications['registration_notification_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                           class="h-5 w-5 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                                </div>

                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex-1">
                                        <label for="event_notification_enabled" class="text-sm font-medium text-gray-900 flex items-center">
                                            <i class="fas fa-calendar-alt text-orange-600 mr-2"></i>
                                            Event Notifications
                                        </label>
                                        <p class="text-xs text-gray-500 mt-1">Send notifications for new events</p>
                                    </div>
                                    <input type="checkbox" 
                                           id="event_notification_enabled" 
                                           name="settings[event_notification_enabled]" 
                                           value="1"
                                           {{ old('settings.event_notification_enabled', $notifications['event_notification_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                           class="h-5 w-5 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                                </div>

                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex-1">
                                        <label for="announcement_notification_enabled" class="text-sm font-medium text-gray-900 flex items-center">
                                            <i class="fas fa-bullhorn text-red-600 mr-2"></i>
                                            Announcement Notifications
                                        </label>
                                        <p class="text-xs text-gray-500 mt-1">Send notifications for new announcements</p>
                                    </div>
                                    <input type="checkbox" 
                                           id="announcement_notification_enabled" 
                                           name="settings[announcement_notification_enabled]" 
                                           value="1"
                                           {{ old('settings.announcement_notification_enabled', $notifications['announcement_notification_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                           class="h-5 w-5 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                                </div>
                            </div>

                            <!-- Test Notifications Section -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Test Notification Channels</h3>
                                <p class="text-sm text-gray-600 mb-4">Send test notifications to verify your channels are working correctly.</p>
                                
                                <!-- Custom Recipient Input -->
                                <div class="mb-4 space-y-3">
                                    <div>
                                        <label for="test_email" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-envelope text-blue-600 mr-2"></i>Test Email Address
                                        </label>
                                        <input 
                                            type="email" 
                                            id="test_email" 
                                            placeholder="Enter email address (default: {{ Auth::user()->email }})"
                                            class="form-input"
                                            value="{{ Auth::user()->email }}">
                                        <p class="text-xs text-gray-500 mt-1">Leave empty to use your account email</p>
                                    </div>
                                    
                                    <div>
                                        <label for="test_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-phone text-purple-600 mr-2"></i>Test Phone Number
                                        </label>
                                        <input 
                                            type="text" 
                                            id="test_phone" 
                                            placeholder="Enter phone number (e.g., +233244123456)"
                                            class="form-input">
                                        <p class="text-xs text-gray-500 mt-1">Required for SMS, WhatsApp, and GekyChat tests</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    <button type="button" onclick="testNotification('email')" class="w-full flex items-center justify-between px-4 py-3 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors">
                                        <div class="flex items-center">
                                            <i class="fas fa-envelope text-blue-600 mr-3"></i>
                                            <span class="font-medium text-gray-900">Test Email</span>
                                        </div>
                                        <i class="fas fa-arrow-right text-blue-600"></i>
                                    </button>

                                    @if($smsConfigured)
                                    <button type="button" onclick="testNotification('sms')" class="w-full flex items-center justify-between px-4 py-3 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg transition-colors">
                                        <div class="flex items-center">
                                            <i class="fas fa-sms text-purple-600 mr-3"></i>
                                            <span class="font-medium text-gray-900">Test SMS (Arkesel)</span>
                                        </div>
                                        <i class="fas fa-arrow-right text-purple-600"></i>
                                    </button>
                                    @else
                                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg opacity-60">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-sms text-gray-400 mr-3"></i>
                                                <span class="font-medium text-gray-500">Test SMS</span>
                                            </div>
                                            <span class="text-xs text-gray-400">Not configured</span>
                                        </div>
                                    </div>
                                    @endif

                                    @if($whatsappConfigured)
                                    <button type="button" onclick="testNotification('whatsapp')" class="w-full flex items-center justify-between px-4 py-3 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg transition-colors">
                                        <div class="flex items-center">
                                            <i class="fab fa-whatsapp text-green-600 mr-3"></i>
                                            <span class="font-medium text-gray-900">Test WhatsApp</span>
                                        </div>
                                        <i class="fas fa-arrow-right text-green-600"></i>
                                    </button>
                                    @else
                                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg opacity-60">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fab fa-whatsapp text-gray-400 mr-3"></i>
                                                <span class="font-medium text-gray-500">Test WhatsApp</span>
                                            </div>
                                            <span class="text-xs text-gray-400">Not configured</span>
                                        </div>
                                    </div>
                                    @endif

                                    @if($gekychatConfigured)
                                    <button type="button" onclick="testNotification('gekychat')" class="w-full flex items-center justify-between px-4 py-3 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg transition-colors">
                                        <div class="flex items-center">
                                            <i class="fas fa-comments text-indigo-600 mr-3"></i>
                                            <span class="font-medium text-gray-900">Test GekyChat</span>
                                        </div>
                                        <i class="fas fa-arrow-right text-indigo-600"></i>
                                    </button>
                                    @else
                                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg opacity-60">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-comments text-gray-400 mr-3"></i>
                                                <span class="font-medium text-gray-500">Test GekyChat</span>
                                            </div>
                                            <span class="text-xs text-gray-400">Not configured</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                                        <div class="text-sm text-blue-800">
                                            <p class="font-medium">Test Notifications:</p>
                                            <ul class="mt-1 text-xs space-y-1 list-disc list-inside">
                                                <li>Email: Uses custom email or your account email</li>
                                                <li>SMS (Arkesel): Requires phone number and ARKESEL_SMS_API_KEY in .env</li>
                                                <li>WhatsApp/GekyChat: Requires phone number and API configuration</li>
                                                <li>All test notifications are logged in the system</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                @if(!$smsConfigured)
                                <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-3"></i>
                                        <div class="text-sm text-yellow-800">
                                            <p class="font-medium">SMS Not Configured:</p>
                                            <p class="mt-1 text-xs">Add <code class="bg-yellow-100 px-1 rounded">ARKESEL_SMS_API_KEY</code> to your .env file to enable SMS testing and broadcasts.</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Preview & Actions -->
                <div class="lg:col-span-1">
                    <!-- Preview Card -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden sticky top-4">
                        <div class="bg-gradient-to-r from-gray-700 to-gray-900 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-eye mr-3"></i>
                                Preview
                            </h2>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-600 mb-4">Social Media Icons Preview:</p>
                            <div class="flex flex-wrap gap-3 mb-6">
                                @foreach($socialMedia as $setting)
                                    @if($setting->value)
                                        <div class="w-10 h-10 bg-stu-green rounded-full flex items-center justify-center">
                                            @if(str_contains($setting->key, 'facebook'))
                                                <i class="fab fa-facebook-f text-white"></i>
                                            @elseif(str_contains($setting->key, 'twitter'))
                                                <i class="fab fa-twitter text-white"></i>
                                            @elseif(str_contains($setting->key, 'linkedin'))
                                                <i class="fab fa-linkedin-in text-white"></i>
                                            @elseif(str_contains($setting->key, 'instagram'))
                                                <i class="fab fa-instagram text-white"></i>
                                            @elseif(str_contains($setting->key, 'youtube'))
                                                <i class="fab fa-youtube text-white"></i>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <button type="submit" class="w-full btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Save Settings
                                </button>
                            </div>

                            <div class="mt-4 bg-gray-50 rounded-lg p-4">
                                <p class="text-xs text-gray-600">
                                    <i class="fas fa-shield-alt text-stu-green mr-1"></i>
                                    Changes are logged for security
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function testNotification(channel) {
    // Get custom recipient
    const customEmail = document.getElementById('test_email').value.trim();
    const customPhone = document.getElementById('test_phone').value.trim();
    
    // Validate based on channel
    if (channel === 'email') {
        if (customEmail && !isValidEmail(customEmail)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Email',
                text: 'Please enter a valid email address.',
                confirmButtonColor: '#B71C1C'
            });
            return;
        }
    } else if (['sms', 'whatsapp', 'gekychat'].includes(channel)) {
        if (!customPhone) {
            Swal.fire({
                icon: 'warning',
                title: 'Phone Number Required',
                text: `Please enter a phone number to test ${channel.toUpperCase()} notifications.`,
                confirmButtonColor: '#F59E0B'
            });
            return;
        }
        if (!isValidPhone(customPhone)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Phone Number',
                text: 'Please enter a valid phone number (e.g., +233244123456).',
                confirmButtonColor: '#B71C1C'
            });
            return;
        }
    }

    Swal.fire({
        title: 'Test Notification',
        text: `Sending test ${channel.toUpperCase()} notification...`,
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('{{ route("admin.settings.test-notification") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            channel: channel,
            email: customEmail || null,
            phone: customPhone || null
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Test Sent!',
                html: data.message || `Test ${channel.toUpperCase()} notification sent successfully!`,
                confirmButtonColor: '#1B5E20'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Test Failed',
                html: data.message || `Failed to send test ${channel.toUpperCase()} notification.`,
                confirmButtonColor: '#B71C1C'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while sending the test notification.',
            confirmButtonColor: '#B71C1C'
        });
        console.error('Error:', error);
    });
}

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function isValidPhone(phone) {
    // Accepts phone numbers with or without country code
    // Examples: +233244123456, 0244123456, 233244123456
    const re = /^(\+?233|0)?[0-9]{9}$/;
    return re.test(phone.replace(/\s+/g, ''));
}
</script>
@endpush
@endsection

