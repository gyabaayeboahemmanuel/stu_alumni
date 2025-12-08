<form id="{{ $method }}RegistrationForm" 
      action="{{ $method === 'sis' ? route('register.sis.complete') : route('register.manual.process') }}" 
      method="POST"
      enctype="{{ $method === 'manual' ? 'multipart/form-data' : 'application/x-www-form-urlencoded' }}">
    @csrf
    
    @if($method === 'sis')
        <input type="hidden" name="sis_data" id="sis_data">
        <input type="hidden" name="student_id" id="student_id_reg">
    @endif

    <div class="space-y-4">
        <!-- Personal Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="form-label">First Name *</label>
                <input type="text" id="first_name" name="first_name" required
                       class="form-input" value="{{ old('first_name') }}">
            </div>
            <div>
                <label for="last_name" class="form-label">Last Name *</label>
                <input type="text" id="last_name" name="last_name" required
                       class="form-input" value="{{ old('last_name') }}">
            </div>
        </div>

        <div>
            <label for="other_names" class="form-label">Other Names</label>
            <input type="text" id="other_names" name="other_names"
                   class="form-input" value="{{ old('other_names') }}">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="email" class="form-label">Email Address *</label>
                <input type="email" id="email" name="email" required
                       class="form-input" value="{{ old('email') }}">
            </div>
            <div>
                <label for="phone" class="form-label">Phone Number *</label>
                <input type="tel" id="phone" name="phone" required
                       class="form-input" value="{{ old('phone') }}">
            </div>
        </div>

        @if($method === 'manual')
            <div>
                <label for="student_id" class="form-label">Student ID (if known)</label>
                <input type="text" id="student_id" name="student_id"
                       class="form-input" value="{{ old('student_id') }}"
                       placeholder="Optional">
            </div>
        @endif

        <!-- Academic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="programme" class="form-label">Programme *</label>
                <input type="text" id="programme" name="programme" required
                       class="form-input" value="{{ old('programme') }}"
                       placeholder="e.g., BSc. Computer Science">
            </div>
            <div>
                <label for="graduation_year" class="form-label">Graduation Year *</label>
                <input type="number" id="graduation_year" name="graduation_year" required
                       class="form-input" value="{{ old('graduation_year') }}"
                       min="1990" max="{{ date('Y') + 1 }}">
            </div>
        </div>

        <div>
            <label for="qualification" class="form-label">Qualification *</label>
            <input type="text" id="qualification" name="qualification" required
                   class="form-input" value="{{ old('qualification') }}"
                   placeholder="e.g., Bachelor, Diploma, Certificate">
        </div>

        @if($method === 'manual')
            <div>
                <label for="proof_document" class="form-label">Proof Document *</label>
                <input type="file" id="proof_document" name="proof_document"
                       class="form-input" accept=".pdf,.jpg,.jpeg,.png">
                <p class="text-xs text-gray-500 mt-1">
                    Upload a scanned copy of your certificate, transcript, or student ID (PDF, JPG, PNG, max 5MB)
                </p>
            </div>
        @endif

        <!-- Account Security -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="password" class="form-label">Password *</label>
                <input type="password" id="password" name="password" required
                       class="form-input" minlength="8">
            </div>
            <div>
                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="form-input">
            </div>
        </div>

        <!-- Terms Agreement -->
        <div class="flex items-center">
            <input type="checkbox" id="agree_terms" name="agree_terms" required
                   class="h-4 w-4 text-stu-green focus:ring-stu-green border-gray-300 rounded">
            <label for="agree_terms" class="ml-2 block text-sm text-gray-900">
                I agree to the <a href="#" class="text-stu-green hover:text-stu-green-dark">Terms and Conditions</a> *
            </label>
        </div>

        <!-- Form Actions -->
        <div class="flex space-x-3">
            <button type="button" id="backToVerification" class="flex-1 btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </button>
            <button type="submit" class="flex-1 btn-primary">
                Complete Registration
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
// Add form submission handling for both SIS and manual forms
document.addEventListener('DOMContentLoaded', function() {
    const forms = ['sisRegistrationForm', 'manualRegistrationForm'];
    
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                submitRegistrationForm(this);
            });
        }
    });

    function submitRegistrationForm(form) {
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;

        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message and redirect
                showAlert('success', data.message);
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            } else {
                // Show errors
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
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred. Please try again.');
        })
        .finally(() => {
            // Reset button state
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        });
    }

    function createErrorElement(input) {
        const errorElement = document.createElement('div');
        errorElement.className = 'form-error';
        input.parentNode.appendChild(errorElement);
        return errorElement;
    }

    function showAlert(type, message) {
        // Implement your alert system here
        alert(message); // Replace with your preferred alert system
    }
});
</script>
@endpush