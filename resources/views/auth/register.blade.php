@extends('layouts.app')

@section('title', 'Register - STU Alumni')

@section('content')
<div class="min-h-screen flex flex-col justify-center py-8 sm:py-12 relative overflow-hidden">
    <!-- Campus Background -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('stu_campus.jpg') }}" alt="STU Campus" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-white/95 via-white/90 to-gray-100/95"></div>
    </div>
    
    <div class="w-full max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Enhanced Logo Section -->
        <div class="flex justify-center mb-6">
            <div class="relative">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg border-2 border-stu-green/20">
                    <img src="{{ asset('stu_logo.png') }}" alt="STU Logo" class="w-14 h-14">
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

    <div class="w-full max-w-2xl mx-auto mt-6 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="bg-white/95 backdrop-blur-md py-8 px-6 sm:px-8 shadow-2xl border border-white/20 rounded-3xl">
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
                    <div class="w-14 h-14 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-id-card text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 tracking-tight">Verify Your Identity</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Enter your Student ID or Phone Number to verify your alumni status
                    </p>
                </div>

                <form id="sisVerificationForm">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">
                                Student ID or Phone Number <span class="text-stu-red">*</span>
                            </label>
                            <div class="relative group">
                                <input type="text" id="student_id" name="student_id" required
                                       class="w-full px-4 py-3 pl-11 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm group-hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                       placeholder="STU123456 or +233 XX XXX XXXX"
                                       value="{{ old('student_id') }}">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">You can enter either your Student ID or registered phone number</p>
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
                                       min="1968" max="2013"
                                       placeholder="2010">
                                <p class="mt-1 text-xs text-gray-600">
                                    <i class="fas fa-info-circle text-stu-green mr-1"></i>
                                    Manual registration is only for alumni who graduated in 2013 or earlier. If you graduated in 2014 or later, please use SIS verification.
                                </p>
                                <div id="manual_graduation_year_error" class="hidden mt-2 text-sm text-red-600 tracking-wide"></div>
                            </div>
                        </div>

                        <div>
                            <label for="manual_programme" class="block text-sm font-medium text-gray-700 mb-2 tracking-wide">Programme *</label>
                            <input type="text" id="manual_programme" name="programme" required
                                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-stu-green/20 focus:border-stu-green transition-all duration-200 shadow-sm hover:shadow-md placeholder-gray-400 text-gray-900 tracking-wide"
                                   value="{{ old('programme') }}"
                                   placeholder="e.g., BSc. Computer Science">
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

.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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

    // Input formatting - allow Student ID or Phone Number
    const studentIdInput = document.getElementById('student_id');
    studentIdInput.addEventListener('input', function() {
        // Allow alphanumeric for Student ID or phone number format
        // Don't restrict to uppercase only, allow phone numbers with +, spaces, dashes
        const value = this.value;
        // If it starts with + or contains digits, it's likely a phone number
        if (value.startsWith('+') || /^\d/.test(value)) {
            // Allow phone number format: +233 XX XXX XXXX or 0XX XXX XXXX
            this.value = value.replace(/[^\d+\s-]/g, '');
        } else {
            // For Student ID, allow alphanumeric
            this.value = value.replace(/[^A-Za-z0-9]/g, '');
        }
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
            showError('student_id', 'Please enter your Student ID or Phone Number');
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
            
            // Always try to parse JSON response, even for error status codes
            // This allows us to get error messages and server connection status
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
                
                // If we can't parse JSON and it's an error status, throw generic error
                if (!response.ok) {
                    if (response.status === 503) {
                        throw new Error('Service temporarily unavailable. Please try alternative registration.');
                    }
                    if (response.status === 404) {
                        throw new Error('Student ID or Phone Number not found. Please use alternative registration.');
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                throw new Error('Invalid response from server. Please try again.');
            }
            
            // If response is not ok and we have parsed data, use the error message from server
            if (!response.ok && data) {
                // Use server-provided error message if available
                let errorMessage = data.message || 
                    (response.status === 404 ? 'Student ID or Phone Number not found. Please use alternative registration.' : 
                     response.status === 503 ? 'Service temporarily unavailable. Please try alternative registration.' :
                     `HTTP error! status: ${response.status}`);
                
                // Add server connection status if available
                if (data.server_response) {
                    errorMessage += `\n\n${data.server_response}`;
                }
                
                // Create error object with additional info
                const error = new Error(errorMessage);
                error.fromSchoolServer = data.from_school_server;
                error.serverResponse = data.server_response;
                throw error;
            }

            if (data.success) {
                verifyButton.classList.add('bg-green-500');
                
                // Show success message with server connection status
                let successMessage = 'SIS verification successful! Your information has been auto-populated.';
                if (data.from_school_server !== undefined) {
                    const serverStatus = data.from_school_server 
                        ? '✓ Response verified from school server' 
                        : '⚠ Response may not be from school server';
                    successMessage += `\n\n${serverStatus}`;
                }
                
                setTimeout(() => {
                    verificationSection.classList.add('hidden');
                    sisSection.classList.remove('hidden');
                    // data.data contains the full API response like IRMTS: {status: 200, desc: "...", detail: {...}}
                    populateSISForm(data.data);
                    updateProgress(2);
                }, 500);
            } else {
                verificationSection.classList.add('hidden');
                manualSection.classList.remove('hidden');
                updateProgress(2);
                
                // Show error message with server connection status
                let errorMessage = data.message || 'Verification failed. Please use alternative registration.';
                if (data.from_school_server !== undefined) {
                    const serverStatus = data.from_school_server 
                        ? '✓ Response from school server' 
                        : '⚠ Connection issue - Request did not reach school server';
                    errorMessage += `\n\n${serverStatus}`;
                }
                
                showAlert(data.from_school_server === false ? 'warning' : 'error', errorMessage);
            }
        } catch (error) {
            console.error('Verification Error:', error);
            verificationSection.classList.add('hidden');
            manualSection.classList.remove('hidden');
            updateProgress(2);
            
            // Check if error has server connection info (from parsed response)
            let errorMessage = error.message || 'Verification service unavailable. Please use alternative registration or try again later.';
            
            // If error has server connection status, include it
            if (error.serverResponse) {
                errorMessage += `\n\n${error.serverResponse}`;
            }
            
            // Determine alert type based on connection status
            const alertType = error.fromSchoolServer === false ? 'warning' : 'error';
            showAlert(alertType, errorMessage);
        } finally {
            verifyText.classList.remove('hidden');
            verifyingSpinner.classList.add('hidden');
            verifyButton.disabled = false;
            verifyButton.classList.remove('opacity-75', 'bg-green-500');
        }
    });

    function populateSISForm(sisData) {
        // Handle response structure: {success: true, data: {detail: {...}} or {data: {...}}}
        // School API returns: {status: 200, desc: "...", detail: {...student data...}}
        let data = sisData;
        
        // If data has a 'data' property, use it (backend wraps it)
        if (sisData.data) {
            data = sisData.data;
        }
        
        // If data has a 'detail' property, use it (direct API response)
        if (data.detail) {
            data = data.detail;
        }
        
        // Personal Information - handle both API formats
        // API returns: fullname, surname, othernames
        // Also check for: full_name, first_name, last_name, other_names
        if (data.fullname) {
            // API format: "PEPRAH , LAWRENCE " or "SURNAME, FIRSTNAME"
            const fullName = data.fullname.trim();
            if (fullName.includes(',')) {
                // Format: "SURNAME, FIRSTNAME"
                const parts = fullName.split(',').map(p => p.trim());
                setValue('last_name', parts[0] || '');
                setValue('first_name', parts[1] || '');
            } else {
                // Format: "FIRSTNAME LASTNAME"
                const nameParts = fullName.split(' ').filter(p => p);
                setValue('first_name', nameParts[0] || '');
                setValue('last_name', nameParts.slice(1).join(' ') || '');
            }
        } else if (data.full_name) {
            const nameParts = data.full_name.split(' ');
            setValue('first_name', nameParts[0] || '');
            setValue('last_name', nameParts.slice(1).join(' ') || '');
        } else {
            // Use separate fields
            setValue('first_name', data.first_name || '');
            setValue('last_name', data.last_name || data.surname || '');
        }
        
        // Other names - API uses 'othernames', also check 'other_names'
        setValue('other_names', data.othernames || data.other_names || '');
        
        // Contact Information
        setValue('email', data.email || '');
        setValue('phone', data.phone || '');
        
        // Academic Information
        // API uses 'index_number', also check 'student_id'
        setValue('student_id_reg', data.index_number || data.student_id || data.index_staff_id || studentIdInput.value);
        
        // API uses 'program', also check 'programme', 'department'
        setValue('programme', data.program || data.programme || data.department || '');
        
        // Graduation year - API might not have this, check various fields
        setValue('graduation_year', data.graduation_year || data.year_of_completion || data.graduation || '');
        
        // Qualification - API might not have this directly
        setValue('qualification', data.qualification || data.certification || data.program || '');
        
        // Store SIS data in hidden field (store the full response)
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

    // Add graduation year validation for manual registration
    const manualGradYearInput = document.getElementById('manual_graduation_year');
    if (manualGradYearInput) {
        manualGradYearInput.addEventListener('input', function() {
            const year = parseInt(this.value);
            const errorDiv = document.getElementById('manual_graduation_year_error');
            
            if (year && year >= 2014) {
                this.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                errorDiv.classList.remove('hidden');
                errorDiv.textContent = 'Graduates from 2014 onwards must use SIS verification. Manual registration is only for 2013 and earlier.';
            } else {
                this.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                errorDiv.classList.add('hidden');
                errorDiv.textContent = '';
            }
        });
    }

    // Add form submission handling for both forms
    const forms = ['sisRegistrationForm', 'manualRegistrationForm'];
    
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Additional validation for manual registration
                if (formId === 'manualRegistrationForm') {
                    const gradYear = parseInt(document.getElementById('manual_graduation_year').value);
                    if (gradYear && gradYear >= 2014) {
                        showAlert('error', 'Alumni who graduated in 2014 or later must use SIS verification. Manual registration is only available for those who graduated in 2013 or earlier, as the school system started in 2014.');
                        return;
                    }
                }
                
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

    // HTML Modal Alert System
    function showAlert(type, message) {
        // Remove existing modal if any
        const existingModal = document.getElementById('alertModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Create modal overlay
        const modal = document.createElement('div');
        modal.id = 'alertModal';
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4';
        modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        modal.style.backdropFilter = 'blur(4px)';
        
        // Determine icon and colors based on type
        let iconClass, bgColor, iconBg, textColor, borderColor;
        switch(type.toLowerCase()) {
            case 'success':
                iconClass = 'fa-check-circle';
                bgColor = 'bg-green-50';
                iconBg = 'bg-green-100';
                textColor = 'text-green-800';
                borderColor = 'border-green-200';
                break;
            case 'error':
                iconClass = 'fa-exclamation-circle';
                bgColor = 'bg-red-50';
                iconBg = 'bg-red-100';
                textColor = 'text-red-800';
                borderColor = 'border-red-200';
                break;
            case 'warning':
                iconClass = 'fa-exclamation-triangle';
                bgColor = 'bg-yellow-50';
                iconBg = 'bg-yellow-100';
                textColor = 'text-yellow-800';
                borderColor = 'border-yellow-200';
                break;
            case 'info':
            default:
                iconClass = 'fa-info-circle';
                bgColor = 'bg-blue-50';
                iconBg = 'bg-blue-100';
                textColor = 'text-blue-800';
                borderColor = 'border-blue-200';
                break;
        }

        // Create modal content
        modal.innerHTML = `
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all animate-fade-in-up ${bgColor} border-2 ${borderColor}">
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 ${iconBg} rounded-full flex items-center justify-center">
                                <i class="fas ${iconClass} text-xl ${textColor}"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold ${textColor} mb-2">${type.charAt(0).toUpperCase() + type.slice(1)}</h3>
                            <p class="text-sm ${textColor} leading-relaxed">${message}</p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button onclick="closeAlertModal()" class="px-6 py-2 bg-stu-green hover:bg-stu-green-dark text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Add to body
        document.body.appendChild(modal);

        // Animate in
        setTimeout(() => {
            modal.querySelector('div.bg-white').style.transform = 'scale(1)';
        }, 10);

        // Auto close after 5 seconds for info/success, 8 seconds for errors
        const autoCloseTime = (type === 'error' || type === 'warning') ? 8000 : 5000;
        setTimeout(() => {
            closeAlertModal();
        }, autoCloseTime);
    }

    // Close modal function
    window.closeAlertModal = function() {
        const modal = document.getElementById('alertModal');
        if (modal) {
            modal.querySelector('div.bg-white').style.transform = 'scale(0.95)';
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.remove();
            }, 200);
        }
    };

    // Close on overlay click
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('alertModal');
        if (modal && e.target === modal) {
            closeAlertModal();
        }
    });
});
</script>
@endpush