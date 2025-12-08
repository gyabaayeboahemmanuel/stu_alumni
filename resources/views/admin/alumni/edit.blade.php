@extends('layouts.app')

@section('title', 'Edit ' . $alumni->full_name)

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Alumni: {{ $alumni->full_name }}</h1>
            <p class="text-gray-600 mt-2">Update alumni information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.alumni.show', $alumni) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Details
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert-error mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.alumni.update', $alumni) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Personal Information</h3>
                </div>

                <div>
                    <label for="first_name" class="form-label">First Name *</label>
                    <input type="text" id="first_name" name="first_name" required 
                           class="form-input" value="{{ old('first_name', $alumni->first_name) }}">
                </div>

                <div>
                    <label for="last_name" class="form-label">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" required 
                           class="form-input" value="{{ old('last_name', $alumni->last_name) }}">
                </div>

                <div>
                    <label for="other_names" class="form-label">Other Names</label>
                    <input type="text" id="other_names" name="other_names" 
                           class="form-input" value="{{ old('other_names', $alumni->other_names) }}">
                </div>

                <div>
                    <label for="gender" class="form-label">Gender *</label>
                    <select id="gender" name="gender" required class="form-input">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $alumni->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $alumni->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $alumni->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label for="date_of_birth" class="form-label">Date of Birth *</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" required 
                           class="form-input" value="{{ old('date_of_birth', $alumni->date_of_birth?->format('Y-m-d')) }}">
                </div>

                <div>
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" id="email" name="email" required 
                           class="form-input" value="{{ old('email', $alumni->email) }}">
                </div>

                <div>
                    <label for="phone" class="form-label">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required 
                           class="form-input" value="{{ old('phone', $alumni->phone) }}">
                </div>

                <!-- Academic Information -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Academic Information</h3>
                </div>

                <div>
                    <label for="year_of_completion" class="form-label">Year of Completion *</label>
                    <input type="number" id="year_of_completion" name="year_of_completion" required 
                           class="form-input" value="{{ old('year_of_completion', $alumni->year_of_completion) }}" 
                           min="1990" max="{{ date('Y') }}">
                </div>

                <div>
                    <label for="programme" class="form-label">Programme *</label>
                    <input type="text" id="programme" name="programme" required 
                           class="form-input" value="{{ old('programme', $alumni->programme) }}">
                </div>

                <div class="md:col-span-2">
                    <label for="qualification" class="form-label">Qualification *</label>
                    <select id="qualification" name="qualification" required class="form-input">
                        <option value="">Select Qualification</option>
                        <option value="Certificate" {{ old('qualification', $alumni->qualification) == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                        <option value="Diploma" {{ old('qualification', $alumni->qualification) == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                        <option value="Bachelor" {{ old('qualification', $alumni->qualification) == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                        <option value="Master" {{ old('qualification', $alumni->qualification) == 'Master' ? 'selected' : '' }}>Master</option>
                        <option value="PhD" {{ old('qualification', $alumni->qualification) == 'PhD' ? 'selected' : '' }}>PhD</option>
                    </select>
                </div>

                <!-- Professional Information -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Professional Information</h3>
                </div>

                <div>
                    <label for="current_employer" class="form-label">Current Employer</label>
                    <input type="text" id="current_employer" name="current_employer" 
                           class="form-input" value="{{ old('current_employer', $alumni->current_employer) }}">
                </div>

                <div>
                    <label for="job_title" class="form-label">Job Title</label>
                    <input type="text" id="job_title" name="job_title" 
                           class="form-input" value="{{ old('job_title', $alumni->job_title) }}">
                </div>

                <div class="md:col-span-2">
                    <label for="industry" class="form-label">Industry</label>
                    <input type="text" id="industry" name="industry" 
                           class="form-input" value="{{ old('industry', $alumni->industry) }}">
                </div>

                <!-- Location Information -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Location Information</h3>
                </div>

                <div>
                    <label for="country" class="form-label">Country</label>
                    <input type="text" id="country" name="country" 
                           class="form-input" value="{{ old('country', $alumni->country) }}">
                </div>

                <div>
                    <label for="city" class="form-label">City</label>
                    <input type="text" id="city" name="city" 
                           class="form-input" value="{{ old('city', $alumni->city) }}">
                </div>

                <div class="md:col-span-2">
                    <label for="postal_address" class="form-label">Postal Address</label>
                    <textarea id="postal_address" name="postal_address" rows="3" 
                              class="form-input">{{ old('postal_address', $alumni->postal_address) }}</textarea>
                </div>

                <!-- Social Links -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Social Links</h3>
                </div>

                <div>
                    <label for="website" class="form-label">Website</label>
                    <input type="url" id="website" name="website" 
                           class="form-input" value="{{ old('website', $alumni->website) }}" placeholder="https://">
                </div>

                <div>
                    <label for="linkedin" class="form-label">LinkedIn</label>
                    <input type="url" id="linkedin" name="linkedin" 
                           class="form-input" value="{{ old('linkedin', $alumni->linkedin) }}" placeholder="https://linkedin.com/in/username">
                </div>

                <div>
                    <label for="twitter" class="form-label">Twitter</label>
                    <input type="url" id="twitter" name="twitter" 
                           class="form-input" value="{{ old('twitter', $alumni->twitter) }}" placeholder="https://twitter.com/username">
                </div>

                <div>
                    <label for="facebook" class="form-label">Facebook</label>
                    <input type="url" id="facebook" name="facebook" 
                           class="form-input" value="{{ old('facebook', $alumni->facebook) }}" placeholder="https://facebook.com/username">
                </div>

                <!-- Privacy Settings -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Privacy Settings</h3>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_visible_in_directory" name="is_visible_in_directory" 
                               value="1" {{ old('is_visible_in_directory', $alumni->is_visible_in_directory) ? 'checked' : '' }} 
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_visible_in_directory" class="ml-2 block text-sm text-gray-900">
                            Make profile visible in alumni directory
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.alumni.show', $alumni) }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    Update Alumni
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
