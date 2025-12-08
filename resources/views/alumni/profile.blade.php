@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
        <p class="text-gray-600 mt-2">Manage your personal and professional information</p>
    </div>

    @if(session('success'))
        <div class="alert-success mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Sidebar -->
        <div class="lg:col-span-1">
            <div class="card p-6">
                <!-- Profile Photo -->
                <div class="text-center">
                    <div class="relative inline-block">
                        <img class="h-32 w-32 rounded-full mx-auto" 
                             src="{{ $alumni->profile_photo_path ? asset('storage/' . $alumni->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($alumni->full_name) . '&color=FFFFFF&background=1E40AF&size=128' }}" 
                             alt="{{ $alumni->full_name }}">
                        <form id="profile-photo-form" action="{{ route('alumni.profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                            @csrf
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="hidden" onchange="document.getElementById('profile-photo-form').submit()">
                            <button type="button" onclick="document.getElementById('profile_photo').click()" 
                                    class="text-sm btn-primary py-1 px-3">
                                <i class="fas fa-camera mr-1"></i>Change Photo
                            </button>
                        </form>
                    </div>
                    
                    <h2 class="mt-4 text-xl font-semibold text-gray-900">{{ $alumni->full_name }}</h2>
                    <p class="text-gray-600">{{ $alumni->programme }} â€¢ {{ $alumni->year_of_completion }}</p>
                    
                    <!-- Verification Badge -->
                    <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $alumni->verification_status === 'verified' ? 'bg-green-100 text-green-800' : 
                                   ($alumni->verification_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800') }}">
                        <i class="fas {{ $alumni->verification_status === 'verified' ? 'fa-check-circle' : 
                                       ($alumni->verification_status === 'pending' ? 'fa-clock' : 'fa-times-circle') }} mr-1"></i>
                        {{ ucfirst($alumni->verification_status) }}
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="mt-6 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Member Since</span>
                        <span class="font-medium">{{ $alumni->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Business Listings</span>
                        <span class="font-medium">{{ $alumni->businesses->count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Event Registrations</span>
                        <span class="font-medium">{{ $alumni->eventRegistrations->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="lg:col-span-2">
            <div class="card p-6">
                <form action="{{ route('alumni.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        </div>

                        <div>
                            <label class="form-label">First Name</label>
                            <input type="text" value="{{ $alumni->first_name }}" class="form-input bg-gray-50" readonly>
                            <p class="text-xs text-gray-500 mt-1">Locked field from SIS</p>
                        </div>

                        <div>
                            <label class="form-label">Last Name</label>
                            <input type="text" value="{{ $alumni->last_name }}" class="form-input bg-gray-50" readonly>
                            <p class="text-xs text-gray-500 mt-1">Locked field from SIS</p>
                        </div>

                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" value="{{ $alumni->email }}" class="form-input bg-gray-50" readonly>
                            <p class="text-xs text-gray-500 mt-1">Locked field from SIS</p>
                        </div>

                        <div>
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $alumni->phone) }}" 
                                   required class="form-input">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Professional Information -->
                        <div class="md:col-span-2 mt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Professional Information</h3>
                        </div>

                        <div>
                            <label for="current_employer" class="form-label">Current Employer</label>
                            <input type="text" id="current_employer" name="current_employer" 
                                   value="{{ old('current_employer', $alumni->current_employer) }}" class="form-input">
                        </div>

                        <div>
                            <label for="job_title" class="form-label">Job Title</label>
                            <input type="text" id="job_title" name="job_title" 
                                   value="{{ old('job_title', $alumni->job_title) }}" class="form-input">
                        </div>

                        <div>
                            <label for="industry" class="form-label">Industry</label>
                            <input type="text" id="industry" name="industry" 
                                   value="{{ old('industry', $alumni->industry) }}" class="form-input">
                        </div>

                        <!-- Location Information -->
                        <div class="md:col-span-2 mt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Location Information</h3>
                        </div>

                        <div>
                            <label for="country" class="form-label">Country</label>
                            <input type="text" id="country" name="country" 
                                   value="{{ old('country', $alumni->country) }}" class="form-input">
                        </div>

                        <div>
                            <label for="city" class="form-label">City</label>
                            <input type="text" id="city" name="city" 
                                   value="{{ old('city', $alumni->city) }}" class="form-input">
                        </div>

                        <div class="md:col-span-2">
                            <label for="postal_address" class="form-label">Postal Address</label>
                            <textarea id="postal_address" name="postal_address" rows="3" 
                                      class="form-input">{{ old('postal_address', $alumni->postal_address) }}</textarea>
                        </div>

                        <!-- Social Links -->
                        <div class="md:col-span-2 mt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Social Links</h3>
                        </div>

                        <div>
                            <label for="website" class="form-label">Website</label>
                            <input type="url" id="website" name="website" 
                                   value="{{ old('website', $alumni->website) }}" class="form-input" placeholder="https://">
                        </div>

                        <div>
                            <label for="linkedin" class="form-label">LinkedIn</label>
                            <input type="url" id="linkedin" name="linkedin" 
                                   value="{{ old('linkedin', $alumni->linkedin) }}" class="form-input" placeholder="https://linkedin.com/in/username">
                        </div>

                        <div>
                            <label for="twitter" class="form-label">Twitter</label>
                            <input type="url" id="twitter" name="twitter" 
                                   value="{{ old('twitter', $alumni->twitter) }}" class="form-input" placeholder="https://twitter.com/username">
                        </div>

                        <div>
                            <label for="facebook" class="form-label">Facebook</label>
                            <input type="url" id="facebook" name="facebook" 
                                   value="{{ old('facebook', $alumni->facebook) }}" class="form-input" placeholder="https://facebook.com/username">
                        </div>

                        <!-- Privacy Settings -->
                        <div class="md:col-span-2 mt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Privacy Settings</h3>
                            <div class="flex items-center">
                                <input type="checkbox" id="is_visible_in_directory" name="is_visible_in_directory" 
                                       value="1" {{ $alumni->is_visible_in_directory ? 'checked' : '' }} 
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="is_visible_in_directory" class="ml-2 block text-sm text-gray-900">
                                    Make my profile visible in alumni directory
                                </label>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                When enabled, other verified alumni can find you in the directory search.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="btn-primary">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
