@extends('layouts.admin')

@section('title', 'Edit Chapter')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('admin.chapters.index') }}" class="text-stu-green hover:text-stu-green-dark mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Edit Chapter</h1>
            </div>
            <p class="text-gray-600">Update chapter information</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.chapters.update', $chapter) }}" method="POST">
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
                                    Chapter Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $chapter->name) }}"
                                       class="form-input @error('name') border-red-500 @enderror"
                                       placeholder="e.g., Accra Chapter"
                                       required>
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="country" class="form-label">
                                        Country <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="country" 
                                           name="country" 
                                           value="{{ old('country', $chapter->country) }}"
                                           class="form-input @error('country') border-red-500 @enderror"
                                           placeholder="e.g., Ghana"
                                           required>
                                    @error('country')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="region" class="form-label">
                                        Region/State <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                    </label>
                                    <input type="text" 
                                           id="region" 
                                           name="region" 
                                           value="{{ old('region', $chapter->region) }}"
                                           class="form-input @error('region') border-red-500 @enderror"
                                           placeholder="e.g., Greater Accra">
                                    @error('region')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="city" class="form-label">
                                        City <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                    </label>
                                    <input type="text" 
                                           id="city" 
                                           name="city" 
                                           value="{{ old('city', $chapter->city) }}"
                                           class="form-input @error('city') border-red-500 @enderror"
                                           placeholder="e.g., Accra">
                                    @error('city')
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
                                          rows="4"
                                          class="form-textarea @error('description') border-red-500 @enderror"
                                          placeholder="Brief description of the chapter">{{ old('description', $chapter->description) }}</textarea>
                                @error('description')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="president_id" class="form-label">
                                    Chapter President <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                </label>
                                <select id="president_id" 
                                        name="president_id" 
                                        class="form-input @error('president_id') border-red-500 @enderror">
                                    <option value="">Select a president</option>
                                    @foreach($alumni as $alumnus)
                                        <option value="{{ $alumnus->id }}" 
                                                {{ old('president_id', $chapter->president_id) == $alumnus->id ? 'selected' : '' }}>
                                            {{ $alumnus->first_name }} {{ $alumnus->last_name }} 
                                            @if($alumnus->student_id)
                                                ({{ $alumnus->student_id }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Select an alumni member to serve as chapter president</p>
                                @error('president_id')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="contact_email" class="form-label">
                                        Contact Email <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                    </label>
                                    <input type="email" 
                                           id="contact_email" 
                                           name="contact_email" 
                                           value="{{ old('contact_email', $chapter->contact_email) }}"
                                           class="form-input @error('contact_email') border-red-500 @enderror"
                                           placeholder="chapter@example.com">
                                    @error('contact_email')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="contact_phone" class="form-label">
                                        Contact Phone <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                    </label>
                                    <input type="text" 
                                           id="contact_phone" 
                                           name="contact_phone" 
                                           value="{{ old('contact_phone', $chapter->contact_phone) }}"
                                           class="form-input @error('contact_phone') border-red-500 @enderror"
                                           placeholder="+233 XX XXX XXXX">
                                    @error('contact_phone')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="meeting_location" class="form-label">
                                    Meeting Location <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                </label>
                                <input type="text" 
                                       id="meeting_location" 
                                       name="meeting_location" 
                                       value="{{ old('meeting_location', $chapter->meeting_location) }}"
                                       class="form-input @error('meeting_location') border-red-500 @enderror"
                                       placeholder="Regular meeting venue address">
                                @error('meeting_location')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="whatsapp_link" class="form-label">
                                    WhatsApp Group Link <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                </label>
                                <input type="url" 
                                       id="whatsapp_link" 
                                       name="whatsapp_link" 
                                       value="{{ old('whatsapp_link', $chapter->whatsapp_link) }}"
                                       class="form-input @error('whatsapp_link') border-red-500 @enderror"
                                       placeholder="https://chat.whatsapp.com/...">
                                @error('whatsapp_link')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $chapter->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    <span class="font-medium">Active</span> - Make this chapter visible to alumni
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="is_approved" 
                                       name="is_approved" 
                                       value="1"
                                       {{ old('is_approved', $chapter->is_approved) ? 'checked' : '' }}
                                       class="h-4 w-4 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                                <label for="is_approved" class="ml-2 block text-sm text-gray-900">
                                    <span class="font-medium">Approved</span> - Approve this chapter
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('admin.chapters.index') }}" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Update Chapter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
