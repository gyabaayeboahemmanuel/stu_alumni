@extends('layouts.admin')

@section('title', 'Broadcast Messages')
@section('page-title', 'Broadcast')

@section('content')
<div class="p-6">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Broadcast Messages</h1>
            <p class="mt-1 text-gray-600">Send messages to alumni via email or SMS</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Broadcast Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('admin.broadcast.send') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    @csrf

                    <div class="p-6 space-y-6">
                        <!-- Recipients -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recipients</h3>
                            
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-stu-green transition-colors">
                                    <input type="radio" name="recipient_type" value="all" 
                                           class="h-4 w-4 text-stu-green" 
                                           {{ old('recipient_type') == 'all' ? 'checked' : '' }}
                                           onchange="toggleRecipientFields()">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">All Alumni</div>
                                        <div class="text-xs text-gray-500">Send to all verified alumni</div>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-stu-green transition-colors">
                                    <input type="radio" name="recipient_type" value="chapter" 
                                           class="h-4 w-4 text-stu-green"
                                           {{ old('recipient_type') == 'chapter' ? 'checked' : '' }}
                                           onchange="toggleRecipientFields()">
                                    <div class="ml-3 flex-1">
                                        <div class="text-sm font-medium text-gray-900">Specific Chapter</div>
                                        <div class="text-xs text-gray-500">Send to members of a chapter</div>
                                    </div>
                                </label>
                                <div id="chapter_select" class="ml-7 hidden">
                                    <select name="chapter_id" class="form-select">
                                        <option value="">Select Chapter</option>
                                        @foreach($chapters as $chapter)
                                            <option value="{{ $chapter->id }}" {{ old('chapter_id') == $chapter->id ? 'selected' : '' }}>
                                                {{ $chapter->name }} ({{ $chapter->members()->count() }} members)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('chapter_id')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-stu-green transition-colors">
                                    <input type="radio" name="recipient_type" value="year_group" 
                                           class="h-4 w-4 text-stu-green"
                                           {{ old('recipient_type') == 'year_group' ? 'checked' : '' }}
                                           onchange="toggleRecipientFields()">
                                    <div class="ml-3 flex-1">
                                        <div class="text-sm font-medium text-gray-900">Year Group</div>
                                        <div class="text-xs text-gray-500">Send to specific graduation years</div>
                                    </div>
                                </label>
                                <div id="year_group_select" class="ml-7 hidden">
                                    <select name="year_group_id" class="form-select">
                                        <option value="">Select Year Group</option>
                                        @foreach($yearGroups as $group)
                                            <option value="{{ $group->id }}" {{ old('year_group_id') == $group->id ? 'selected' : '' }}>
                                                {{ $group->name }} ({{ $group->start_year }}-{{ $group->end_year }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('year_group_id')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-stu-green transition-colors">
                                    <input type="radio" name="recipient_type" value="custom" 
                                           class="h-4 w-4 text-stu-green"
                                           {{ old('recipient_type') == 'custom' ? 'checked' : '' }}
                                           onchange="toggleRecipientFields()">
                                    <div class="ml-3 flex-1">
                                        <div class="text-sm font-medium text-gray-900">Custom List</div>
                                        <div class="text-xs text-gray-500">Enter specific email addresses</div>
                                    </div>
                                </label>
                                <div id="custom_emails" class="ml-7 hidden">
                                    <textarea name="custom_emails" rows="3" class="form-textarea" 
                                              placeholder="email1@example.com, email2@example.com">{{ old('custom_emails') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Separate emails with commas</p>
                                    @error('custom_emails')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            @error('recipient_type')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Channel -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Channel</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                                <label class="flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer hover:border-stu-green transition-colors {{ old('channel') == 'email' || !old('channel') ? 'border-stu-green bg-green-50' : '' }}">
                                    <input type="radio" name="channel" value="email" 
                                           class="sr-only peer" 
                                           {{ old('channel') == 'email' || !old('channel') ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <i class="fas fa-envelope text-2xl mb-2 text-blue-600"></i>
                                        <div class="text-sm font-medium">Email</div>
                                    </div>
                                </label>
                                <label class="flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer hover:border-stu-green transition-colors {{ old('channel') == 'sms' ? 'border-stu-green bg-green-50' : '' }}">
                                    <input type="radio" name="channel" value="sms" 
                                           class="sr-only peer"
                                           {{ old('channel') == 'sms' ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <i class="fas fa-sms text-2xl mb-2 text-purple-600"></i>
                                        <div class="text-sm font-medium">SMS</div>
                                    </div>
                                </label>
                                @if($whatsappConfigured)
                                <label class="flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer hover:border-stu-green transition-colors {{ old('channel') == 'whatsapp' ? 'border-stu-green bg-green-50' : '' }}">
                                    <input type="radio" name="channel" value="whatsapp" 
                                           class="sr-only peer"
                                           {{ old('channel') == 'whatsapp' ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <i class="fab fa-whatsapp text-2xl mb-2 text-green-600"></i>
                                        <div class="text-sm font-medium">WhatsApp</div>
                                    </div>
                                </label>
                                @endif
                                @if($gekychatConfigured)
                                <label class="flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer hover:border-stu-green transition-colors {{ old('channel') == 'gekychat' ? 'border-stu-green bg-green-50' : '' }}">
                                    <input type="radio" name="channel" value="gekychat" 
                                           class="sr-only peer"
                                           {{ old('channel') == 'gekychat' ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <i class="fas fa-comments text-2xl mb-2 text-indigo-600"></i>
                                        <div class="text-sm font-medium">GekyChat</div>
                                    </div>
                                </label>
                                @endif
                                <label class="flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer hover:border-stu-green transition-colors {{ old('channel') == 'all' ? 'border-stu-green bg-green-50' : '' }}">
                                    <input type="radio" name="channel" value="all" 
                                           class="sr-only peer"
                                           {{ old('channel') == 'all' ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <i class="fas fa-paper-plane text-2xl mb-2 text-stu-green"></i>
                                        <div class="text-sm font-medium">All</div>
                                    </div>
                                </label>
                            </div>
                            <div class="mt-3 flex flex-wrap gap-2 text-xs text-gray-500">
                                @if(!$smsConfigured)
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 rounded">
                                        <i class="fas fa-sms mr-1 text-gray-400"></i>SMS (Arkesel) not configured
                                    </span>
                                @endif
                                @if(!$whatsappConfigured)
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 rounded">
                                        <i class="fab fa-whatsapp mr-1 text-gray-400"></i>WhatsApp not configured
                                    </span>
                                @endif
                                @if(!$gekychatConfigured)
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 rounded">
                                        <i class="fas fa-comments mr-1 text-gray-400"></i>GekyChat not configured
                                    </span>
                                @endif
                                @if($smsConfigured && $whatsappConfigured && $gekychatConfigured)
                                    <span class="text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i>All channels available
                                    </span>
                                @endif
                            </div>
                            @error('channel')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Message</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="subject" class="form-label">Subject *</label>
                                    <input type="text" id="subject" name="subject" 
                                           value="{{ old('subject') }}"
                                           class="form-input @error('subject') border-red-500 @enderror"
                                           placeholder="Enter message subject"
                                           required>
                                    @error('subject')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea id="message" name="message" rows="8"
                                              class="form-textarea @error('message') border-red-500 @enderror"
                                              placeholder="Enter your message..."
                                              required>{{ old('message') }}</textarea>
                                    <div class="flex justify-between items-center mt-1">
                                        <p class="text-xs text-gray-500">Maximum 5000 characters</p>
                                        <p class="text-xs text-gray-500" id="char_count">0 / 5000</p>
                                    </div>
                                    @error('message')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="reset" class="btn-secondary">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane mr-2"></i>Send Broadcast
                        </button>
                    </div>
                </form>
            </div>

            <!-- Recent Broadcasts -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Broadcasts</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($recentBroadcasts->take(5) as $broadcast)
                            <div class="p-4 hover:bg-gray-50">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-{{ $broadcast->sent_via == 'email' ? 'envelope' : ($broadcast->sent_via == 'sms' ? 'sms' : 'paper-plane') }} text-blue-600 text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $broadcast->subject }}</p>
                                        <p class="text-xs text-gray-500">{{ $broadcast->created_at->diffForHumans() }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                            {{ $broadcast->status == 'sent' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} mt-1">
                                            {{ ucfirst($broadcast->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2 text-gray-300"></i>
                                <p class="text-sm">No broadcasts yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-6 space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-2">Broadcast Tips:</p>
                                <ul class="space-y-1 list-disc list-inside text-xs">
                                    <li>Test with custom list first</li>
                                    <li>Keep messages concise</li>
                                    <li>Include call-to-action</li>
                                    <li>All sends are logged</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                                    @if(!$smsConfigured || !$whatsappConfigured || !$gekychatConfigured)
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                        <div class="flex items-start">
                                            <i class="fas fa-cog text-yellow-600 mt-0.5 mr-3"></i>
                                            <div class="text-sm text-yellow-800">
                                                <p class="font-medium mb-2">Channel Configuration:</p>
                                                <ul class="space-y-1 text-xs">
                                                    @if(!$smsConfigured)
                                                        <li>• Add <code class="bg-yellow-100 px-1 rounded">ARKESEL_SMS_API_KEY</code> to .env to enable SMS (Arkesel)</li>
                                                    @endif
                                                    @if(!$whatsappConfigured)
                                                        <li>• Add <code class="bg-yellow-100 px-1 rounded">WHATSAPP_API_KEY</code> and <code class="bg-yellow-100 px-1 rounded">WHATSAPP_API_URL</code> to .env to enable WhatsApp</li>
                                                    @endif
                                                    @if(!$gekychatConfigured)
                                                        <li>• Add <code class="bg-yellow-100 px-1 rounded">GEKYCHAT_CLIENT_ID</code> and <code class="bg-yellow-100 px-1 rounded">GEKYCHAT_CLIENT_SECRET</code> to .env to enable GekyChat</li>
                                                    @endif
                                                    @if($smsConfigured && $whatsappConfigured && $gekychatConfigured)
                                                        <li>✓ All channels are configured and ready to use!</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleRecipientFields() {
    const type = document.querySelector('input[name="recipient_type"]:checked').value;
    
    document.getElementById('chapter_select').classList.add('hidden');
    document.getElementById('year_group_select').classList.add('hidden');
    document.getElementById('custom_emails').classList.add('hidden');
    
    if (type === 'chapter') {
        document.getElementById('chapter_select').classList.remove('hidden');
    } else if (type === 'year_group') {
        document.getElementById('year_group_select').classList.remove('hidden');
    } else if (type === 'custom') {
        document.getElementById('custom_emails').classList.remove('hidden');
    }
}

// Character counter
document.getElementById('message').addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('char_count').textContent = count + ' / 5000';
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const checked = document.querySelector('input[name="recipient_type"]:checked');
    if (checked) {
        toggleRecipientFields();
    }
});
</script>
@endpush
@endsection

