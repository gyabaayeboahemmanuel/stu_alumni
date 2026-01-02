@extends('layouts.app')

@section('title', 'Edit Business')

@section('content')
<div class="max-w-5xl mx-auto py-8 sm:px-6 lg:px-8">
    <!-- Enhanced Header -->
    <div class="mb-10 animate-fade-in-up">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-stu-green via-stu-green-light to-stu-green-dark p-8 text-white shadow-2xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-edit text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Edit Business</h1>
                        <p class="text-green-100 mt-1 text-lg">Update your business information</p>
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

    @if($errors->any())
        <div class="mb-6 alert-error rounded-xl animate-fade-in-up">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle mr-2 text-red-600 mt-1"></i>
                <div>
                    <h3 class="font-semibold mb-2">Please fix the following errors:</h3>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Current Logo Preview -->
    @if($business->logo_path)
    <div class="card p-6 mb-8 hover-lift animate-fade-in-up" style="animation-delay: 0.1s">
        <div class="flex items-center mb-4">
            <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
            <h3 class="text-xl font-bold text-gray-900">Current Logo</h3>
        </div>
        <div class="flex items-center space-x-6">
            <div class="relative">
                <div class="absolute -inset-2 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-xl opacity-20 blur"></div>
                <img src="{{ asset('storage/' . $business->logo_path) }}" 
                     alt="{{ $business->name }}" 
                     class="relative w-24 h-24 rounded-xl object-cover border-4 border-white shadow-lg">
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700">Current business logo</p>
                <p class="text-xs text-gray-500 mt-1">Upload a new logo to replace this one</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Business Form -->
    <div class="card p-8 hover-lift animate-fade-in-up" style="animation-delay: 0.2s">
        <form action="{{ route('alumni.businesses.update', $business) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Basic Information -->
                <div>
                    <div class="flex items-center mb-6">
                        <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                        <h3 class="text-xl font-bold text-gray-900">Basic Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="name" class="form-label">Business Name *</label>
                            <input type="text" id="name" name="name" required 
                                   class="form-input focus:ring-2 focus:ring-stu-green" 
                                   value="{{ old('name', $business->name) }}"
                                   placeholder="Enter your business name">
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="form-label">Business Description *</label>
                            <textarea id="description" name="description" required rows="4"
                                      class="form-input focus:ring-2 focus:ring-stu-green" 
                                      placeholder="Describe your business, products, or services">{{ old('description', $business->description) }}</textarea>
                        </div>

                        <div>
                            <label for="industry" class="form-label">Industry *</label>
                            <select id="industry" name="industry" required class="form-input focus:ring-2 focus:ring-stu-green">
                                <option value="">Select Industry</option>
                                <option value="Technology" {{ old('industry', $business->industry) == 'Technology' ? 'selected' : '' }}>Technology</option>
                                <option value="Healthcare" {{ old('industry', $business->industry) == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                <option value="Education" {{ old('industry', $business->industry) == 'Education' ? 'selected' : '' }}>Education</option>
                                <option value="Finance" {{ old('industry', $business->industry) == 'Finance' ? 'selected' : '' }}>Finance</option>
                                <option value="Retail" {{ old('industry', $business->industry) == 'Retail' ? 'selected' : '' }}>Retail</option>
                                <option value="Manufacturing" {{ old('industry', $business->industry) == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                <option value="Services" {{ old('industry', $business->industry) == 'Services' ? 'selected' : '' }}>Services</option>
                                <option value="Agriculture" {{ old('industry', $business->industry) == 'Agriculture' ? 'selected' : '' }}>Agriculture</option>
                                <option value="Construction" {{ old('industry', $business->industry) == 'Construction' ? 'selected' : '' }}>Construction</option>
                                <option value="Transportation" {{ old('industry', $business->industry) == 'Transportation' ? 'selected' : '' }}>Transportation</option>
                                <option value="Other" {{ old('industry', $business->industry) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div>
                    <div class="flex items-center mb-6">
                        <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                        <h3 class="text-xl font-bold text-gray-900">Contact Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="website" class="form-label">Website</label>
                            <input type="url" id="website" name="website" 
                                   class="form-input focus:ring-2 focus:ring-stu-green" 
                                   value="{{ old('website', $business->website) }}"
                                   placeholder="https://example.com">
                        </div>

                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" 
                                   class="form-input focus:ring-2 focus:ring-stu-green" 
                                   value="{{ old('email', $business->email) }}"
                                   placeholder="contact@business.com">
                        </div>

                        <div>
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" id="phone" name="phone" 
                                   class="form-input focus:ring-2 focus:ring-stu-green" 
                                   value="{{ old('phone', $business->phone) }}"
                                   placeholder="+233 XX XXX XXXX">
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div>
                    <div class="flex items-center mb-6">
                        <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                        <h3 class="text-xl font-bold text-gray-900">Location Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="form-label">Address</label>
                            <textarea id="address" name="address" rows="2"
                                      class="form-input focus:ring-2 focus:ring-stu-green" 
                                      placeholder="Street address">{{ old('address', $business->address) }}</textarea>
                        </div>

                        <div>
                            <label for="city" class="form-label">City</label>
                            <input type="text" id="city" name="city" 
                                   class="form-input focus:ring-2 focus:ring-stu-green" 
                                   value="{{ old('city', $business->city) }}"
                                   placeholder="City">
                        </div>

                        <div>
                            <label for="country" class="form-label">Country</label>
                            <input type="text" id="country" name="country" 
                                   class="form-input focus:ring-2 focus:ring-stu-green" 
                                   value="{{ old('country', $business->country) }}"
                                   placeholder="Country">
                        </div>
                    </div>
                </div>

                <!-- Business Logo -->
                <div>
                    <div class="flex items-center mb-6">
                        <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                        <h3 class="text-xl font-bold text-gray-900">Business Logo</h3>
                    </div>
                    <div>
                        <label for="logo" class="form-label">Logo (Optional)</label>
                        <input type="file" id="logo" name="logo" 
                               accept="image/jpeg,image/png,image/jpg"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-stu-green file:text-white hover:file:bg-stu-green-dark transition-all">
                        <p class="text-sm text-gray-500 mt-2 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            JPG, PNG files only. Maximum size: 2MB. Recommended: 400x400 pixels.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('alumni.businesses.my-businesses') }}" class="btn-secondary rounded-xl">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="btn-primary rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>Update Business
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
