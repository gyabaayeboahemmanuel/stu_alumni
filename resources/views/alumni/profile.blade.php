@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* Custom Bootstrap form styling with STU colors */
    .form-control:focus {
        border-color: #1B5E20;
        box-shadow: 0 0 0 0.25rem rgba(27, 94, 32, 0.25);
    }
    
    .form-check-input:checked {
        background-color: #1B5E20;
        border-color: #1B5E20;
    }
    
    .form-check-input:focus {
        border-color: #1B5E20;
        box-shadow: 0 0 0 0.25rem rgba(27, 94, 32, 0.25);
    }
    
    .btn-primary {
        background-color: #1B5E20;
        border-color: #1B5E20;
    }
    
    .btn-primary:hover {
        background-color: #0D3C11;
        border-color: #0D3C11;
    }
    
    .form-control {
        border: 2px solid #9CA3AF;
    }
    
    .form-control:hover:not(:focus) {
        border-color: #6B7280;
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto py-8 sm:px-6 lg:px-8">
    <!-- Enhanced Header -->
    <div class="mb-8 animate-fade-in-up">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-stu-green via-stu-green-light to-stu-green-dark p-8 text-white shadow-2xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm border-4 border-white border-opacity-30">
                        <i class="fas fa-user-circle text-4xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">My Profile</h1>
                        <p class="text-green-100 mt-1 text-lg">Manage your personal and professional information</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 alert-success rounded-xl animate-fade-in-up">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Enhanced Profile Sidebar -->
        <div class="lg:col-span-1">
            <div class="card p-6 hover-lift animate-fade-in-up" style="animation-delay: 0.1s">
                <!-- Profile Photo -->
                <div class="text-center">
                    <div class="relative inline-block">
                        <div class="absolute -inset-2 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-full opacity-20 blur"></div>
                        <img class="relative h-32 w-32 rounded-full mx-auto border-4 border-white shadow-xl" 
                             src="{{ $alumni->profile_photo_path ? asset('storage/' . $alumni->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($alumni->full_name) . '&color=FFFFFF&background=1B5E20&size=128' }}" 
                             alt="{{ $alumni->full_name }}">
                        <form id="profile-photo-form" action="{{ route('alumni.profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                            @csrf
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="hidden" onchange="document.getElementById('profile-photo-form').submit()">
                            <button type="button" onclick="document.getElementById('profile_photo').click()" 
                                    class="text-sm btn-primary py-2 px-4 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-camera mr-1"></i>Change Photo
                            </button>
                        </form>
                    </div>
                    
                    <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $alumni->full_name }}</h2>
                    <p class="text-gray-600 mt-1">{{ $alumni->programme }} • {{ $alumni->year_of_completion }}</p>
                    
                    <!-- Verification Badge -->
                    <div class="mt-4 inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-md
                                {{ $alumni->verification_status === 'verified' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border-2 border-green-300' : 
                                   ($alumni->verification_status === 'pending' ? 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border-2 border-yellow-300' : 
                                   'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border-2 border-red-300') }}">
                        <i class="fas {{ $alumni->verification_status === 'verified' ? 'fa-check-circle' : 
                                       ($alumni->verification_status === 'pending' ? 'fa-clock' : 'fa-times-circle') }} mr-2"></i>
                        {{ ucfirst($alumni->verification_status) }}
                    </div>
                </div>

                <!-- Enhanced Quick Stats -->
                <div class="mt-8 space-y-4 border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Member Since</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ $alumni->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-briefcase text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Business Listings</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ $alumni->businesses->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-check text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Event Registrations</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ $alumni->eventRegistrations->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Profile Form -->
        <div class="lg:col-span-2">
            <div class="card p-8 hover-lift animate-fade-in-up" style="animation-delay: 0.2s">
                <form action="{{ route('alumni.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        <!-- Personal Information Section -->
                        <div>
                            <div class="flex items-center mb-6">
                                <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                                <h3 class="text-xl font-bold text-gray-900">Personal Information</h3>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" value="{{ $alumni->first_name }}" class="form-control-plaintext" readonly>
                                    <div class="form-text">
                                        <i class="fas fa-lock me-1"></i> Locked field from SIS
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" value="{{ $alumni->last_name }}" class="form-control-plaintext" readonly>
                                    <div class="form-text">
                                        <i class="fas fa-lock me-1"></i> Locked field from SIS
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" value="{{ $alumni->email }}" class="form-control-plaintext" readonly>
                                    <div class="form-text">
                                        <i class="fas fa-lock me-1"></i> Locked field from SIS
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $alumni->phone) }}" 
                                           required class="form-control @error('phone') is-invalid @enderror">
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information Section -->
                        <div>
                            <div class="flex items-center mb-6">
                                <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                                <h3 class="text-xl font-bold text-gray-900">Professional Information</h3>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="current_employer" class="form-label">Current Employer</label>
                                    <input type="text" id="current_employer" name="current_employer" 
                                           value="{{ old('current_employer', $alumni->current_employer) }}" 
                                           class="form-control" 
                                           placeholder="Enter your current employer">
                                </div>

                                <div class="col-md-6">
                                    <label for="job_title" class="form-label">Job Title</label>
                                    <input type="text" id="job_title" name="job_title" 
                                           value="{{ old('job_title', $alumni->job_title) }}" 
                                           class="form-control"
                                           placeholder="Enter your job title">
                                </div>

                                <div class="col-12">
                                    <label for="industry" class="form-label">Industry</label>
                                    <input type="text" id="industry" name="industry" 
                                           value="{{ old('industry', $alumni->industry) }}" 
                                           class="form-control"
                                           placeholder="Enter your industry">
                                </div>
                            </div>
                        </div>

                        <!-- Location Information Section -->
                        <div>
                            <div class="flex items-center mb-6">
                                <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                                <h3 class="text-xl font-bold text-gray-900">Location Information</h3>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" id="country" name="country" 
                                           value="{{ old('country', $alumni->country) }}" 
                                           class="form-control"
                                           placeholder="Enter your country">
                                </div>

                                <div class="col-md-6">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" id="city" name="city" 
                                           value="{{ old('city', $alumni->city) }}" 
                                           class="form-control"
                                           placeholder="Enter your city">
                                </div>

                                <div class="col-12">
                                    <label for="postal_address" class="form-label">Postal Address</label>
                                    <textarea id="postal_address" name="postal_address" rows="3" 
                                              class="form-control"
                                              placeholder="Enter your postal address">{{ old('postal_address', $alumni->postal_address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Social Links Section -->
                        <div>
                            <div class="flex items-center mb-6">
                                <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                                <h3 class="text-xl font-bold text-gray-900">Social Links</h3>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="website" class="form-label">Website</label>
                                    <input type="url" id="website" name="website" 
                                           value="{{ old('website', $alumni->website) }}" 
                                           class="form-control" 
                                           placeholder="https://">
                                </div>

                                <div class="col-md-6">
                                    <label for="linkedin" class="form-label">LinkedIn</label>
                                    <input type="url" id="linkedin" name="linkedin" 
                                           value="{{ old('linkedin', $alumni->linkedin) }}" 
                                           class="form-control" 
                                           placeholder="https://linkedin.com/in/username">
                                </div>

                                <div class="col-md-6">
                                    <label for="twitter" class="form-label">Twitter</label>
                                    <input type="url" id="twitter" name="twitter" 
                                           value="{{ old('twitter', $alumni->twitter) }}" 
                                           class="form-control" 
                                           placeholder="https://twitter.com/username">
                                </div>

                                <div class="col-md-6">
                                    <label for="facebook" class="form-label">Facebook</label>
                                    <input type="url" id="facebook" name="facebook" 
                                           value="{{ old('facebook', $alumni->facebook) }}" 
                                           class="form-control" 
                                           placeholder="https://facebook.com/username">
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Settings Section -->
                        <div>
                            <div class="flex items-center mb-6">
                                <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                                <h3 class="text-xl font-bold text-gray-900">Privacy Settings</h3>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-200">
                                <div class="form-check">
                                    <input type="checkbox" id="is_visible_in_directory" name="is_visible_in_directory" 
                                           value="1" {{ $alumni->is_visible_in_directory ? 'checked' : '' }} 
                                           class="form-check-input">
                                    <label for="is_visible_in_directory" class="form-check-label">
                                        <span class="fw-semibold">Make my profile visible in alumni directory</span>
                                        <div class="text-muted mt-1">
                                            When enabled, other verified alumni can find you in the directory search.
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-top d-flex justify-content-end gap-3">
                        <a href="{{ route('alumni.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection
