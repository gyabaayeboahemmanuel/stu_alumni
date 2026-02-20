@extends('layouts.admin')

@section('title', 'Create Event')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('admin.events.index') }}" class="text-stu-green hover:text-stu-green-dark mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Create Event</h1>
            </div>
            <p class="text-gray-600">Create a new alumni event</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="title" class="form-label">
                                    Event Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}"
                                       class="form-input @error('title') border-red-500 @enderror"
                                       placeholder="Enter event title"
                                       required>
                                @error('title')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="form-label">
                                    Description <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" 
                                          name="description" 
                                          rows="8"
                                          class="form-textarea @error('description') border-red-500 @enderror"
                                          placeholder="Enter event description"
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="event_type" class="form-label">
                                    Event Type <span class="text-red-500">*</span>
                                </label>
                                <select id="event_type" 
                                        name="event_type" 
                                        class="form-input @error('event_type') border-red-500 @enderror"
                                        required>
                                    <option value="">Select event type</option>
                                    <option value="physical" {{ old('event_type') == 'physical' ? 'selected' : '' }}>Physical - In-person event</option>
                                    <option value="online" {{ old('event_type') == 'online' ? 'selected' : '' }}>Online - Virtual event</option>
                                    <option value="hybrid" {{ old('event_type') == 'hybrid' ? 'selected' : '' }}>Hybrid - Both in-person and online</option>
                                </select>
                                @error('event_type')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Date & Time</h3>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="event_date" class="form-label">
                                        Start Date & Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" 
                                           id="event_date" 
                                           name="event_date" 
                                           value="{{ old('event_date') }}"
                                           class="form-input @error('event_date') border-red-500 @enderror"
                                           required>
                                    @error('event_date')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="event_end_date" class="form-label">
                                        End Date & Time <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                    </label>
                                    <input type="datetime-local" 
                                           id="event_end_date" 
                                           name="event_end_date" 
                                           value="{{ old('event_end_date') }}"
                                           class="form-input @error('event_end_date') border-red-500 @enderror">
                                    @error('event_end_date')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="registration_deadline" class="form-label">
                                    Registration Deadline <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                </label>
                                <input type="datetime-local" 
                                       id="registration_deadline" 
                                       name="registration_deadline" 
                                       value="{{ old('registration_deadline') }}"
                                       class="form-input @error('registration_deadline') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1">Must be before event start date</p>
                                @error('registration_deadline')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location & Venue -->
                    <div class="border-t border-gray-200 pt-6" id="locationSection">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Location & Venue</h3>
                        
                        <div class="space-y-4">
                            <div id="venueField">
                                <label for="venue" class="form-label">
                                    Venue <span class="text-gray-500 text-sm font-normal">(Required for physical/hybrid events)</span>
                                </label>
                                <input type="text" 
                                       id="venue" 
                                       name="venue" 
                                       value="{{ old('venue') }}"
                                       class="form-input @error('venue') border-red-500 @enderror"
                                       placeholder="Event venue address">
                                @error('venue')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="onlineLinkField">
                                <label for="online_link" class="form-label">
                                    Online Link <span class="text-gray-500 text-sm font-normal">(Required for online/hybrid events)</span>
                                </label>
                                <input type="url" 
                                       id="online_link" 
                                       name="online_link" 
                                       value="{{ old('online_link') }}"
                                       class="form-input @error('online_link') border-red-500 @enderror"
                                       placeholder="https://meet.google.com/... or Zoom link">
                                @error('online_link')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Registration Settings -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Registration Settings</h3>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="max_attendees" class="form-label">
                                        Maximum Attendees <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                    </label>
                                    <input type="number" 
                                           id="max_attendees" 
                                           name="max_attendees" 
                                           value="{{ old('max_attendees') }}"
                                           class="form-input @error('max_attendees') border-red-500 @enderror"
                                           placeholder="Leave blank for unlimited"
                                           min="1">
                                    @error('max_attendees')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="price" class="form-label">
                                        Price (GHS) <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                    </label>
                                    <input type="number" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price', 0) }}"
                                           class="form-input @error('price') border-red-500 @enderror"
                                           placeholder="0.00"
                                           step="0.01"
                                           min="0">
                                    @error('price')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="requires_approval" 
                                           name="requires_approval" 
                                           value="1"
                                           {{ old('requires_approval', false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                                    <label for="requires_approval" class="ml-2 block text-sm text-gray-900">
                                        <span class="font-medium">Requires Approval</span> - Manually approve registrations
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Featured Image</h3>
                        
                        <div>
                            <label for="featured_image" class="form-label">
                                Upload Image <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                            </label>
                            <input type="file" 
                                   id="featured_image" 
                                   name="featured_image" 
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="form-input @error('featured_image') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Max file size: 2MB. Supported formats: JPEG, PNG, JPG</p>
                            @error('featured_image')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            
                            <div id="imagePreview" class="mt-4 hidden">
                                <img id="previewImg" src="" alt="Preview" class="max-w-xs rounded-lg shadow-md">
                            </div>
                        </div>
                    </div>

                    <!-- Publishing Options -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Publishing Options</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="is_published" 
                                       name="is_published" 
                                       value="1"
                                       {{ old('is_published', false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                                <label for="is_published" class="ml-2 block text-sm text-gray-900">
                                    <span class="font-medium">Publish Now</span> - Make this event visible to alumni
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="is_featured" 
                                       name="is_featured" 
                                       value="1"
                                       {{ old('is_featured', false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                    <span class="font-medium">Feature Event</span> - Highlight this event on the homepage
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('admin.events.index') }}" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Create Event
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview functionality
    const featuredImageInput = document.getElementById('featured_image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (featuredImageInput) {
        featuredImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('hidden');
            }
        });
    }

    // Show/hide venue and online link based on event type
    const eventTypeSelect = document.getElementById('event_type');
    const venueField = document.getElementById('venueField');
    const onlineLinkField = document.getElementById('onlineLinkField');

    function toggleFields() {
        const eventType = eventTypeSelect.value;
        if (eventType === 'physical') {
            venueField.style.display = 'block';
            onlineLinkField.style.display = 'none';
        } else if (eventType === 'online') {
            venueField.style.display = 'none';
            onlineLinkField.style.display = 'block';
        } else if (eventType === 'hybrid') {
            venueField.style.display = 'block';
            onlineLinkField.style.display = 'block';
        } else {
            venueField.style.display = 'block';
            onlineLinkField.style.display = 'block';
        }
    }

    if (eventTypeSelect) {
        eventTypeSelect.addEventListener('change', toggleFields);
        toggleFields(); // Initial call
    }
});
</script>
@endsection
