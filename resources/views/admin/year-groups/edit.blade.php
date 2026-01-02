@extends('layouts.admin')

@section('title', 'Edit Year Group')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('admin.year-groups.index') }}" class="text-stu-green hover:text-stu-green-dark mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Edit Year Group</h1>
            </div>
            <p class="text-gray-600">Update year group information and links</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.year-groups.update', $yearGroup) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="form-label">
                                    Group Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $yearGroup->name) }}"
                                       class="form-input @error('name') border-red-500 @enderror"
                                       required>
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_year" class="form-label">
                                        Start Year <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           id="start_year" 
                                           name="start_year" 
                                           value="{{ old('start_year', $yearGroup->start_year) }}"
                                           class="form-input @error('start_year') border-red-500 @enderror"
                                           min="1968" 
                                           max="2030"
                                           required>
                                    @error('start_year')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="end_year" class="form-label">
                                        End Year <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           id="end_year" 
                                           name="end_year" 
                                           value="{{ old('end_year', $yearGroup->end_year) }}"
                                           class="form-input @error('end_year') border-red-500 @enderror"
                                           min="1968" 
                                           max="2030"
                                           required>
                                    @error('end_year')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="description" class="form-label">
                                    Description <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                </label>
                                <textarea id="description" 
                                          name="description" 
                                          rows="3"
                                          class="form-textarea @error('description') border-red-500 @enderror">{{ old('description', $yearGroup->description) }}</textarea>
                                @error('description')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Social Group Links -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Social Group Links</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="whatsapp_link" class="form-label flex items-center">
                                    <i class="fab fa-whatsapp text-green-600 mr-2"></i>
                                    WhatsApp Group Link <span class="text-gray-500 text-sm font-normal ml-1">(Optional)</span>
                                </label>
                                <input type="url" 
                                       id="whatsapp_link" 
                                       name="whatsapp_link" 
                                       value="{{ old('whatsapp_link', $yearGroup->whatsapp_link) }}"
                                       class="form-input @error('whatsapp_link') border-red-500 @enderror"
                                       placeholder="https://chat.whatsapp.com/...">
                                @error('whatsapp_link')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="telegram_link" class="form-label flex items-center">
                                    <i class="fab fa-telegram text-blue-600 mr-2"></i>
                                    Telegram Group Link <span class="text-gray-500 text-sm font-normal ml-1">(Optional)</span>
                                </label>
                                <input type="url" 
                                       id="telegram_link" 
                                       name="telegram_link" 
                                       value="{{ old('telegram_link', $yearGroup->telegram_link) }}"
                                       class="form-input @error('telegram_link') border-red-500 @enderror"
                                       placeholder="https://t.me/...">
                                @error('telegram_link')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="gekychat_link" class="form-label flex items-center">
                                    <i class="fas fa-comments text-purple-600 mr-2"></i>
                                    GekyChat Group Link <span class="text-gray-500 text-sm font-normal ml-1">(Optional)</span>
                                </label>
                                <input type="url" 
                                       id="gekychat_link" 
                                       name="gekychat_link" 
                                       value="{{ old('gekychat_link', $yearGroup->gekychat_link) }}"
                                       class="form-input @error('gekychat_link') border-red-500 @enderror"
                                       placeholder="https://gekychat.com/...">
                                @error('gekychat_link')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $yearGroup->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                <span class="font-medium">Active</span> - Alumni can see this year group on their dashboard
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('admin.year-groups.index') }}" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Update Year Group
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

