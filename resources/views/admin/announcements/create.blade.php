@extends('layouts.admin')

@section('title', 'Create Announcement')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('admin.announcements.index') }}" class="text-stu-green hover:text-stu-green-dark mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Create Announcement</h1>
            </div>
            <p class="text-gray-600">Create a new announcement to share with alumni</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i>Please fix the following errors:
                <ul class="mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="title" class="form-label">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}"
                                       class="form-input @error('title') border-red-500 @enderror"
                                       placeholder="Enter announcement title"
                                       required>
                                @error('title')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category_id" class="form-label">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select id="category_id" 
                                        name="category_id" 
                                        class="form-input @error('category_id') border-red-500 @enderror"
                                        required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="excerpt" class="form-label">
                                    Excerpt <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                </label>
                                <textarea id="excerpt" 
                                          name="excerpt" 
                                          rows="3"
                                          class="form-textarea @error('excerpt') border-red-500 @enderror"
                                          placeholder="Brief summary of the announcement (max 500 characters)">{{ old('excerpt') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Leave blank to auto-generate from content</p>
                                @error('excerpt')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="content" class="form-label">
                                    Content <span class="text-red-500">*</span>
                                </label>
                                <textarea id="content" 
                                          name="content" 
                                          rows="12"
                                          class="form-textarea @error('content') border-red-500 @enderror"
                                          placeholder="Enter the full announcement content"
                                          required>{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
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
                        
                        <div class="space-y-4">
                            <div>
                                <label for="visibility" class="form-label">
                                    Visibility <span class="text-red-500">*</span>
                                </label>
                                <select id="visibility" 
                                        name="visibility" 
                                        class="form-input @error('visibility') border-red-500 @enderror"
                                        required>
                                    <option value="public" {{ old('visibility', 'public') == 'public' ? 'selected' : '' }}>Public - Visible to everyone</option>
                                    <option value="alumni" {{ old('visibility') == 'alumni' ? 'selected' : '' }}>Alumni Only - Visible to registered alumni only</option>
                                </select>
                                @error('visibility')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="published_at" class="form-label">
                                        Publish Date <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                    </label>
                                    <input type="datetime-local" 
                                           id="published_at" 
                                           name="published_at" 
                                           value="{{ old('published_at') }}"
                                           class="form-input @error('published_at') border-red-500 @enderror">
                                    <p class="text-xs text-gray-500 mt-1">Leave blank to publish immediately when saved</p>
                                    @error('published_at')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="is_published" 
                                           name="is_published" 
                                           value="1"
                                           {{ old('is_published', false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                                    <label for="is_published" class="ml-2 block text-sm text-gray-900">
                                        <span class="font-medium">Publish Now</span> - Make this announcement visible immediately
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="is_pinned" 
                                           name="is_pinned" 
                                           value="1"
                                           {{ old('is_pinned', false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                                    <label for="is_pinned" class="ml-2 block text-sm text-gray-900">
                                        <span class="font-medium">Pin Announcement</span> - Keep this announcement at the top of the list
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Settings</h3>
                        <p class="text-sm text-gray-600 mb-4">Optional: Customize how this announcement appears in search engines</p>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="meta_title" class="form-label">
                                    Meta Title <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                </label>
                                <input type="text" 
                                       id="meta_title" 
                                       name="meta_title" 
                                       value="{{ old('meta_title') }}"
                                       class="form-input @error('meta_title') border-red-500 @enderror"
                                       placeholder="Leave blank to use announcement title"
                                       maxlength="255">
                                <p class="text-xs text-gray-500 mt-1">Recommended: 50-60 characters</p>
                                @error('meta_title')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="meta_description" class="form-label">
                                    Meta Description <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                                </label>
                                <textarea id="meta_description" 
                                          name="meta_description" 
                                          rows="3"
                                          class="form-textarea @error('meta_description') border-red-500 @enderror"
                                          placeholder="Leave blank to auto-generate from content"
                                          maxlength="500">{{ old('meta_description') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Recommended: 150-160 characters</p>
                                @error('meta_description')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('admin.announcements.index') }}" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Create Announcement
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
});
</script>
@endsection
