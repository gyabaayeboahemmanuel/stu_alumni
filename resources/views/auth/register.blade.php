@extends('layouts.app')

@section('title', 'Register - STU Alumni')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 flex flex-col justify-center py-8 sm:py-12">
    <div class="w-full max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Enhanced Logo Section -->
        <div class="flex justify-center mb-6">
            <div class="relative">
                <div class="w-16 h-16 bg-stu-green rounded-2xl flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-xl">STU</span>
                </div>
                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-stu-red rounded-full flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-xs"></i>
                </div>
            </div>
        </div>
        
        <h2 class="text-center text-3xl font-bold text-gray-900 tracking-tight">
            Join STU Alumni
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 max-w-md mx-auto leading-relaxed">
            Connect with your fellow graduates and access exclusive benefits
        </p>
    </div>

    <div class="w-full max-w-2xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white/80 backdrop-blur-sm py-8 px-6 sm:px-8 shadow-2xl border border-white/20 rounded-3xl">
            <!-- Compact Progress Bar -->
            <div class="flex justify-center mb-8">
                <div class="flex items-center space-x-2">
                    <div class="flex flex-col items-center">
                        <div id="step1" class="w-8 h-8 bg-stu-green text-white rounded-full flex items-center justify-center text-sm font-medium transition-all duration-300">
                            1
                        </div>
                        <span class="text-xs text-stu-green font-medium mt-1 tracking-wide">Verify</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300 rounded-full"></div>
                    <div class="flex flex-col items-center">
                        <div id="step2" class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium transition-all duration-300">
                            2
                        </div>
                        <span class="text-xs text-gray-500 font-medium mt-1 tracking-wide">Complete</span>
                    </div>
                </div>
            </div>

            <!-- SIS Verification Section -->
            <div id="sisVerificationSection" class="fade-in">
                <div class="text-center mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-id-card text-stu-green text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 tracking-tight">Verify Your Identity</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Enter your Student ID to verify your alumni status
                    </p>
                </div>

                <form id="sisVerificationForm">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">
                                Student ID <span class="text-stu-red">*</span>
                            </label>
                            <div class="relative group">
                                <input type="text" id="student_id" name="student_id" required
                                       class="w-full px-4 py-3 pl-11 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm group-hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       placeholder="STU123456"
                                       value="{{ old('student_id') }}">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                            <div id="student_id_error" class="hidden mt-2 text-sm text-red-600 tracking-wide"></div>
                        </div>

                        <div>
                            <button type="submit" id="verifyButton" 
                                    class="w-full bg-gradient-to-r from-stu-green to-stu-green-light hover:from-stu-green-dark hover:to-stu-green text-white py-3 text-base font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                                <span id="verifyText" class="flex items-center justify-center tracking-wide">
                                    <i class="fas fa-shield-check mr-2"></i>
                                    Verify My Identity
                                </span>
                                <span id="verifyingSpinner" class="hidden items-center justify-center tracking-wide">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Verifying...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-200/60">
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-3 leading-relaxed">
                            Don't have your Student ID?
                        </p>
                        <button type="button" id="showManualRegistration" 
                                class="inline-flex items-center text-stu-green hover:text-stu-green-dark font-medium text-sm transition-all duration-200 tracking-wide">
                            <i class="fas fa-edit mr-2 text-sm"></i>
                            Use alternative registration
                        </button>
                    </div>
                </div>
            </div>

            <!-- Manual Registration Section -->
            <div id="manualRegistrationSection" class="hidden slide-in">
                <div class="text-center mb-6">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-edit text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 tracking-tight">Alternative Registration</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Complete the form below for manual verification
                    </p>
                </div>

                <form id="manualRegistrationForm" action="{{ route('register.manual.process') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Personal Information -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="manual_first_name" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">First Name *</label>
                                <input type="text" id="manual_first_name" name="first_name" required
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       value="{{ old('first_name') }}"
                                       placeholder="John">
                            </div>
                            <div>
                                <label for="manual_last_name" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Last Name *</label>
                                <input type="text" id="manual_last_name" name="last_name" required
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       value="{{ old('last_name') }}"
                                       placeholder="Doe">
                            </div>
                        </div>

                        <div>
                            <label for="manual_other_names" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Other Names</label>
                            <input type="text" id="manual_other_names" name="other_names"
                                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                   value="{{ old('other_names') }}"
                                   placeholder="Optional">
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="manual_email" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Email Address *</label>
                                <input type="email" id="manual_email" name="email" required
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       value="{{ old('email') }}"
                                       placeholder="john.doe@example.com">
                            </div>
                            <div>
                                <label for="manual_phone" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Phone Number *</label>
                                <input type="tel" id="manual_phone" name="phone" required
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       value="{{ old('phone') }}"
                                       placeholder="+233 XX XXX XXXX">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="manual_student_id" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Student ID (if known)</label>
                                <input type="text" id="manual_student_id" name="student_id"
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       value="{{ old('student_id') }}"
                                       placeholder="Optional">
                            </div>
                            <div>
                                <label for="manual_graduation_year" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Graduation Year *</label>
                                <input type="number" id="manual_graduation_year" name="graduation_year" required
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       value="{{ old('graduation_year') }}"
                                       min="1968" max="{{ date('Y') + 1 }}"
                                       placeholder="2020">
                            </div>
                        </div>

                        <div>
                            <label for="manual_programme" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Programme *</label>
                            <select id="manual_programme" name="programme" required 
                                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md text-gray-900 tracking-wide appearance-none cursor-pointer">
                                <option value="">Select Programme</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Business Administration">Business Administration</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Health Sciences">Health Sciences</option>
                                <option value="Education">Education</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- Password Notice -->
                        <div class="bg-green-50 border border-green-200 rounded-xl p-3">
                            <div class="flex items-start">
                                <i class="fas fa-key text-green-500 mt-0.5 mr-3"></i>
                                <div class="text-xs text-green-700 leading-relaxed">
                                    <p class="font-medium tracking-wide">Auto-Generated Password</p>
                                    <p class="mt-1">A secure password will be sent to your email</p>
                                </div>
                            </div>
                        </div>

                        <!-- Terms Agreement -->
                        <div class="flex items-start">
                            <input type="checkbox" id="manual_agree_terms" name="agree_terms" required
                                   class="h-4 w-4 text-stu-green focus:ring-stu-green border-gray-300 rounded mt-0.5 cursor-pointer transition-all duration-200">
                            <label for="manual_agree_terms" class="ml-2 block text-xs text-gray-700 leading-relaxed tracking-wide">
                                I agree to the <a href="#" class="text-stu-green hover:text-stu-green-dark font-medium transition-colors">Terms and Conditions</a> *
                            </label>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex space-x-3 pt-2">
                            <button type="button" id="backToVerificationFromManual" 
                                    class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 tracking-wide">
                                <i class="fas fa-arrow-left mr-2"></i>Back
                            </button>
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-stu-green to-stu-green-light hover:from-stu-green-dark hover:to-stu-green text-white font-semibold py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 tracking-wide">
                                Complete Registration
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- SIS Registration Section -->
            <div id="sisRegistrationSection" class="hidden slide-in">
                <div class="text-center mb-6">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 tracking-tight">Complete Registration</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Your information has been verified. Please review and complete.
                    </p>
                </div>

                <form id="sisRegistrationForm" action="{{ route('register.sis.complete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="sis_data" id="sis_data">
                    <input type="hidden" name="student_id" id="student_id_reg">
                    
                    <div class="space-y-4">
                        <!-- Personal Information -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">First Name *</label>
                                <input type="text" id="first_name" name="first_name" required
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       value="{{ old('first_name') }}">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" required
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       value="{{ old('last_name') }}">
                            </div>
                        </div>

                        <div>
                            <label for="other_names" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Other Names</label>
                            <input type="text" id="other_names" name="other_names"
                                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                   value="{{ old('other_names') }}">
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Email Address *</label>
                                <input type="email" id="email" name="email" required
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       value="{{ old('email') }}">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       value="{{ old('phone') }}">
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="programme" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Programme *</label>
                                <input type="text" id="programme" name="programme" required
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       value="{{ old('programme') }}">
                            </div>
                            <div>
                                <label for="graduation_year" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Graduation Year *</label>
                                <input type="number" id="graduation_year" name="graduation_year" required
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       value="{{ old('graduation_year') }}"
                                       min="1990" max="{{ date('Y') + 1 }}">
                            </div>
                        </div>

                        <div>
                            <label for="qualification" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Qualification *</label>
                            <input type="text" id="qualification" name="qualification" required
                                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                   value="{{ old('qualification') }}">
                        </div>

                        <!-- Password Notice -->
                        <div class="bg-green-50 border border-green-200 rounded-xl p-3">
                            <div class="flex items-start">
                                <i class="fas fa-key text-green-500 mt-0.5 mr-3"></i>
                                <div class="text-xs text-green-700 leading-relaxed">
                                    <p class="font-medium tracking-wide">Auto-Generated Password</p>
                                    <p class="mt-1">A secure password will be sent to your email</p>
                                </div>
                            </div>
                        </div>

                        <!-- Terms Agreement -->
                        <div class="flex items-start">
                            <input type="checkbox" id="agree_terms" name="agree_terms" required
                                   class="h-4 w-4 text-stu-green focus:ring-stu-green border-gray-300 rounded mt-0.5 cursor-pointer transition-all duration-200">
                            <label for="agree_terms" class="ml-2 block text-xs text-gray-700 leading-relaxed tracking-wide">
                                I agree to the <a href="#" class="text-stu-green hover:text-stu-green-dark font-medium transition-colors">Terms and Conditions</a> *
                            </label>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex space-x-3 pt-2">
                            <button type="button" id="backToVerification" 
                                    class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 tracking-wide">
                                <i class="fas fa-arrow-left mr-2"></i>Back
                            </button>
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-stu-green to-stu-green-light hover:from-stu-green-dark hover:to-stu-green text-white font-semibold py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 tracking-wide">
                                Complete Registration
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 leading-relaxed">
                    Already part of our community?
                    <a href="{{ route('login') }}" class="font-medium text-stu-green hover:text-stu-green-dark transition-all duration-200 tracking-wide">
                        Sign in to your account
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

body {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.fade-in {
    animation: fadeIn 0.4s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

.slide-in {
    animation: slideIn 0.4s ease-out;
}

@keyframes slideIn {
    from { transform: translateX(16px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const verificationForm = document.getElementById('sisVerificationForm');
    const verificationSection = document.getElementById('sisVerificationSection');
    const manualSection = document.getElementById('manualRegistrationSection');
    const sisSection = document.getElementById('sisRegistrationSection');
    const verifyButton = document.getElementById('verifyButton');
    const verifyText = document.getElementById('verifyText');
    const verifyingSpinner = document.getElementById('verifyingSpinner');
    const showManualBtn = document.getElementById('showManualRegistration');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');

    // Update progress bar
    function updateProgress(currentStep) {
        if (currentStep === 1) {
            step1.classList.remove('bg-gray-200', 'text-gray-500');
            step1.classList.add('bg-stu-green', 'text-white');
            step2.classList.remove('bg-stu-green', 'text-white');
            step2.classList.add('bg-gray-200', 'text-gray-500');
        } else {
            step1.classList.add('bg-green-500', 'text-white');
            step2.classList.remove('bg-gray-200', 'text-gray-500');
            step2.classList.add('bg-stu-green', 'text-white');
        }
    }

    // Add input formatting for Student ID
    const studentIdInput = document.getElementById('student_id');
    studentIdInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });

    // Show manual registration form
    showManualBtn.addEventListener('click', function() {
        verificationSection.classList.add('hidden');
        manualSection.classList.remove('hidden');
        updateProgress(2);
    });

    // Back to verification from manual
    document.getElementById('backToVerificationFromManual').addEventListener('click', function() {
        manualSection.classList.add('hidden');
        verificationSection.classList.remove('hidden');
        updateProgress(1);
    });

    verificationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const studentId = studentIdInput.value.trim();
        
        if (!studentId) {
            showError('student_id', 'Please enter your Student ID');
            return;
        }

        // Reset errors
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });

        // Show loading state
        verifyText.classList.add('hidden');
        verifyingSpinner.classList.remove('hidden');
        verifyButton.disabled = true;
        verifyButton.classList.add('opacity-75');

        try {
            // Use the correct endpoint
            const response = await fetch('/verify-sis', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    student_id: studentId
                })
            });

            console.log('Response status:', response.status);
            
            if (!response.ok) {
                // Handle HTTP errors
                if (response.status === 503) {
                    throw new Error('Service temporarily unavailable. Please try alternative registration.');
                }
                if (response.status === 404) {
                    throw new Error('Student ID not found. Please use alternative registration.');
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const responseText = await response.text();
            console.log('Raw response:', responseText);
            
            // Handle BOM character if present
            const cleanResponseText = responseText.replace(/^\uFEFF/, '');
            
            let data;
            try {
                data = JSON.parse(cleanResponseText);
            } catch (parseError) {
                console.error('JSON Parse Error:', parseError);
                console.error('Response text that failed to parse:', responseText);
                throw new Error('Invalid response from server. Please try again.');
            }

            if (data.success) {
                verifyButton.classList.add('bg-green-500');
                setTimeout(() => {
                    verificationSection.classList.add('hidden');
                    sisSection.classList.remove('hidden');
                    populateSISForm(data.data);
                    updateProgress(2);
                }, 500);
            } else {
                verificationSection.classList.add('hidden');
                manualSection.classList.remove('hidden');
                updateProgress(2);
                if (data.message) {
                    showAlert('info', data.message);
                }
            }
        } catch (error) {
            console.error('Verification Error:', error);
            verificationSection.classList.add('hidden');
            manualSection.classList.remove('hidden');
            updateProgress(2);
            showAlert('error', error.message || 'Verification service unavailable. Please use alternative registration.');
        } finally {
            verifyText.classList.remove('hidden');
            verifyingSpinner.classList.add('hidden');
            verifyButton.disabled = false;
            verifyButton.classList.remove('opacity-75', 'bg-green-500');
        }
    });

    function populateSISForm(sisData) {
        const data = sisData.data || sisData;
        
        // Personal Information
        if (data.full_name) {
            const nameParts = data.full_name.split(' ');
            setValue('first_name', nameParts[0] || '');
            setValue('last_name', nameParts.slice(1).join(' ') || '');
        } else {
            setValue('first_name', data.first_name || '');
            setValue('last_name', data.last_name || '');
        }
        
        setValue('other_names', data.other_names || '');
        setValue('email', data.email || '');
        setValue('phone', data.phone || '');
        
        // Academic Information
        setValue('student_id_reg', data.student_id || studentIdInput.value);
        setValue('programme', data.programme || data.department || '');
        setValue('graduation_year', data.graduation_year || data.year_of_completion || '');
        setValue('qualification', data.qualification || data.certification || '');
        
        // Store SIS data in hidden field
        setValue('sis_data', JSON.stringify(sisData));
    }

    function setValue(elementId, value) {
        const element = document.getElementById(elementId);
        if (element) {
            element.value = value;
        }
    }

    function showError(field, message) {
        const errorElement = document.getElementById(field + '_error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
            
            const inputElement = document.getElementById(field);
            if (inputElement) {
                inputElement.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            }
        }
    }

    // Back to verification functionality
    document.addEventListener('click', function(e) {
        if (e.target.id === 'backToVerification' || e.target.closest('#backToVerification')) {
            sisSection.classList.add('hidden');
            manualSection.classList.add('hidden');
            verificationSection.classList.remove('hidden');
            updateProgress(1);
            
            // Reset forms and clear errors
            document.getElementById('sisRegistrationForm')?.reset();
            document.getElementById('manualRegistrationForm')?.reset();
            document.querySelectorAll('[id$="_error"]').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.border-red-500').forEach(el => 
                el.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500')
            );
        }
    });

    // Add form submission handling for both forms
    const forms = ['sisRegistrationForm', 'manualRegistrationForm'];
    
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                await submitRegistrationForm(this);
            });
        }
    });

    async function submitRegistrationForm(form) {
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;

        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const responseText = await response.text();
            const cleanResponseText = responseText.replace(/^\uFEFF/, '');
            
            let data;
            try {
                data = JSON.parse(cleanResponseText);
            } catch (parseError) {
                console.error('JSON Parse Error:', parseError);
                throw new Error('Invalid response from server');
            }

            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const input = form.querySelector(`[name="${field}"]`);
                        const errorElement = document.getElementById(`${field}_error`) || createErrorElement(input);
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                } else if (data.message) {
                    showAlert('error', data.message);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('error', 'An error occurred. Please try again.');
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    }

    function createErrorElement(input) {
        const errorElement = document.createElement('div');
        errorElement.className = 'mt-2 text-sm text-red-600 tracking-wide';
        input.parentNode.appendChild(errorElement);
        return errorElement;
    }

    function showAlert(type, message) {
        // Simple alert implementation
        alert(`${type.toUpperCase()}: ${message}`);
    }
});
</script>
@endpush