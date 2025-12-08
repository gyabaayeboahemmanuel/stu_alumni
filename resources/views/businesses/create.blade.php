@extends('layouts.app')

@section('title', 'Add Business')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Add Business to Directory</h1>
        <p class="text-gray-600 mt-2">Share your business with the STU alumni community</p>
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

    <!-- Business Form -->
    <div class="card p-6">
        <form action="{{ route('alumni.businesses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Basic Information -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Basic Information</h3>
                </div>

                <div class="md:col-span-2">
                    <label for="name" class="form-label">Business Name *</label>
                    <input type="text" id="name" name="name" required 
                           class="form-input" value="{{ old('name') }}"
                           placeholder="Enter your business name">
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="form-label">Business Description *</label>
                    <textarea id="description" name="description" required rows="4"
                              class="form-input" placeholder="Describe your business, products, or services">{{ old('description') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Tell alumni what your business does and what makes it unique.</p>
                </div>

                <div>
                    <label for="industry" class="form-label">Industry *</label>
                    <select id="industry" name="industry" required class="form-input">
                        <option value="">Select Industry</option>
                        <option value="Technology" {{ old('industry') == 'Technology' ? 'selected' : '' }}>Technology</option>
                        <option value="Healthcare" {{ old('industry') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                        <option value="Education" {{ old('industry') == 'Education' ? 'selected' : '' }}>Education</option>
                        <option value="Finance" {{ old('industry') == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Retail" {{ old('industry') == 'Retail' ? 'selected' : '' }}>Retail</option>
                        <option value="Manufacturing" {{ old('industry') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                        <option value="Services" {{ old('industry') == 'Services' ? 'selected' : '' }}>Services</option>
                        <option value="Agriculture" {{ old('industry') == 'Agriculture' ? 'selected' : '' }}>Agriculture</option>
                        <option value="Construction" {{ old('industry') == 'Construction' ? 'selected' : '' }}>Construction</option>
                        <option value="Transportation" {{ old('industry') == 'Transportation' ? 'selected' : '' }}>Transportation</option>
                        <option value="Other" {{ old('industry') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Contact Information -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Contact Information</h3>
                </div>

                <div>
                    <label for="website" class="form-label">Website</label>
                    <input type="url" id="website" name="website" 
                           class="form-input" value="{{ old('website') }}"
                           placeholder="https://example.com">
                </div>

                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" 
                           class="form-input" value="{{ old('email') }}"
                           placeholder="contact@business.com">
                </div>

                <div>
                    <label for="phone" class="form-label">Phone</label>
                    <input type="tel" id="phone" name="phone" 
                           class="form-input" value="{{ old('phone') }}"
                           placeholder="+233 XX XXX XXXX">
                </div>

                <!-- Location Information -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Location Information</h3>
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="form-label">Address</label>
                    <textarea id="address" name="address" rows="2"
                              class="form-input" placeholder="Street address">{{ old('address') }}</textarea>
                </div>

                <div>
                    <label for="city" class="form-label">City</label>
                    <input type="text" id="city" name="city" 
                           class="form-input" value="{{ old('city') }}"
                           placeholder="City">
                </div>

                <div>
                    <label for="country" class="form-label">Country</label>
                    <input type="text" id="country" name="country" 
                           class="form-input" value="{{ old('country') }}"
                           placeholder="Country">
                </div>

                <!-- Business Logo -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Business Logo</h3>
                </div>

                <div class="md:col-span-2">
                    <label for="logo" class="form-label">Logo (Optional)</label>
                    <input type="file" id="logo" name="logo" 
                           accept="image/jpeg,image/png,image/jpg"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-sm text-gray-500 mt-1">JPG, PNG files only. Maximum size: 2MB. Recommended: 400x400 pixels.</p>
                </div>

                <!-- Submission Notice -->
                <div class="md:col-span-2 mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex">
                        <div class="shrink-0">
                            <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Verification Required</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Your business listing will be reviewed by the alumni office before appearing in the public directory. This process typically takes 1-2 business days.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('alumni.businesses.my-businesses') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Submit Business
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
