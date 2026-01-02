@extends('layouts.app')

@section('title', 'Manual Registration')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-3xl">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Manual Registration
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            For graduates before 2014 - Document verification required
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-3xl">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form id="manual-registration-form" action="{{ route('register.manual.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Personal Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Personal Information</h3>
                    </div>

                    <div>
                        <label for="first_name" class="form-label">First Name *</label>
                        <input type="text" id="first_name" name="first_name" required class="form-input" value="{{ old('first_name') }}">
                    </div>

                    <div>
                        <label for="last_name" class="form-label">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" required class="form-input" value="{{ old('last_name') }}">
                    </div>

                    <div>
                        <label for="other_names" class="form-label">Other Names</label>
                        <input type="text" id="other_names" name="other_names" class="form-input" value="{{ old('other_names') }}">
                    </div>

                    <div>
                        <label for="gender" class="form-label">Gender *</label>
                        <select id="gender" name="gender" required class="form-input">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="date_of_birth" class="form-label">Date of Birth *</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" required class="form-input" value="{{ old('date_of_birth') }}">
                    </div>

                    <div>
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" id="email" name="email" required class="form-input" value="{{ old('email') }}">
                    </div>

                    <div>
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required class="form-input" value="{{ old('phone') }}">
                    </div>

                    <!-- Academic Information -->
                    <div class="md:col-span-2 mt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Academic Information</h3>
                    </div>

                    <div>
                        <label for="year_of_completion" class="form-label">Year of Completion *</label>
                        <select id="year_of_completion" name="year_of_completion" required class="form-input">
                            <option value="">Select Year</option>
                            @for($year = 1990; $year <= 2013; $year++)
                                <option value="{{ $year }}" {{ old('year_of_completion') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="programme" class="form-label">Programme *</label>
                        <input type="text" id="programme" name="programme" required class="form-input" placeholder="e.g., BSc. Computer Science" value="{{ old('programme') }}">
                    </div>

                    <div>
                        <label for="qualification" class="form-label">Qualification *</label>
                        <select id="qualification" name="qualification" required class="form-input">
                            <option value="">Select Qualification</option>
                            <option value="Certificate" {{ old('qualification') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                            <option value="Diploma" {{ old('qualification') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                            <option value="Bachelor" {{ old('qualification') == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                            <option value="Master" {{ old('qualification') == 'Master' ? 'selected' : '' }}>Master</option>
                            <option value="PhD" {{ old('qualification') == 'PhD' ? 'selected' : '' }}>PhD</option>
                        </select>
                    </div>

                    <!-- Document Upload -->
                    <div class="md:col-span-2 mt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Document Verification</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Please upload a clear photo or scan of one of the following documents for verification:
                            <ul class="list-disc list-inside text-sm text-gray-600 mt-2 space-y-1">
                                <li>STU Certificate or Transcript</li>
                                <li>National ID Card</li>
                                <li>Passport</li>
                            </ul>
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="proof_document" class="form-label">Proof Document *</label>
                        <input type="file" id="proof_document" name="proof_document" required 
                               accept=".pdf,.jpg,.jpeg,.png" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-sm text-gray-500">PDF, JPG, or PNG files only. Maximum size: 5MB</p>
                    </div>

                    <!-- Account Security -->
                    <div class="md:col-span-2 mt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Account Security</h3>
                    </div>

                    <div>
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" id="password" name="password" required class="form-input" minlength="8">
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Confirm Password *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input">
                    </div>

                    <!-- Terms Agreement -->
                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="agree_terms" name="agree_terms" required 
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="agree_terms" class="ml-2 block text-sm text-gray-900">
                                I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">Terms of Service</a> 
                                and <a href="#" class="text-blue-600 hover:text-blue-500">Privacy Policy</a>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full btn-success">
                        <span id="submit-text">Submit Registration</span>
                        <span id="submit-spinner" class="hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                        </span>
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:text-blue-500 inline-flex items-center">
                    <i class="fas fa-arrow-left mr-1"></i> Back to registration options
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('manual-registration-form');
    
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const submitText = document.getElementById('submit-text');
        const submitSpinner = document.getElementById('submit-spinner');
        
        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitSpinner.classList.remove('hidden');
    });
});
</script>
@endsection
