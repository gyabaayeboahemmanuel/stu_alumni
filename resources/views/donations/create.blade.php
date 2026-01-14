@extends('layouts.app')

@section('title', 'Make a Donation')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                Support <span class="text-stu-green">STU Alumni</span>
            </h1>
            <p class="text-lg text-gray-600">
                Your generous contribution helps us maintain and improve the alumni network
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-2 border-green-200 rounded-xl p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-2 border-red-200 rounded-xl p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                    <span class="text-red-800">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Donation Type Selection -->
        <div class="card p-8 mb-8">
            <form action="{{ route('donations.store') }}" method="POST" id="donation-form">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 mb-4">Select Donation Type</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Cash Donation -->
                        <div class="relative">
                            <input type="radio" id="type_cash" name="type" value="cash" class="peer hidden" checked>
                            <label for="type_cash" class="flex flex-col items-center p-6 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-stu-green transition-all peer-checked:border-stu-green peer-checked:bg-green-50">
                                <div class="w-16 h-16 bg-stu-green rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Cash Donation</h3>
                                <p class="text-sm text-gray-600 text-center">Make a monetary contribution via our secure payment gateway</p>
                            </label>
                        </div>

                        <!-- In-Kind Donation -->
                        <div class="relative">
                            <input type="radio" id="type_in_kind" name="type" value="in_kind" class="peer hidden">
                            <label for="type_in_kind" class="flex flex-col items-center p-6 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-stu-green transition-all peer-checked:border-stu-green peer-checked:bg-green-50">
                                <div class="w-16 h-16 bg-stu-red rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-gift text-white text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">In-Kind Donation</h3>
                                <p class="text-sm text-gray-600 text-center">Donate items, services, or resources</p>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- In-Kind Donation Fields (Hidden by default) -->
                <div id="in-kind-fields" class="hidden space-y-6">
                    <div class="border-t-2 border-gray-200 pt-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">In-Kind Donation Details</h3>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" name="description" rows="4" 
                                      class="form-input w-full @error('description') border-red-500 @enderror"
                                      placeholder="Please describe what you are donating...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="items" class="block text-sm font-semibold text-gray-900 mb-2">
                                Items <span class="text-red-500">*</span>
                            </label>
                            <textarea id="items" name="items" rows="3" 
                                      class="form-input w-full @error('items') border-red-500 @enderror"
                                      placeholder="List the items you are donating (e.g., Books, Equipment, Services)...">{{ old('items') }}</textarea>
                            @error('items')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="country" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Country <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="country" name="country" 
                                       value="{{ old('country') }}"
                                       class="form-input w-full @error('country') border-red-500 @enderror"
                                       placeholder="e.g., Ghana">
                                @error('country')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-semibold text-gray-900 mb-2">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="city" name="city" 
                                       value="{{ old('city') }}"
                                       class="form-input w-full @error('city') border-red-500 @enderror"
                                       placeholder="e.g., Accra">
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="contact" class="block text-sm font-semibold text-gray-900 mb-2">
                                Contact Information <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="contact" name="contact" 
                                   value="{{ old('contact') }}"
                                   class="form-input w-full @error('contact') border-red-500 @enderror"
                                   placeholder="Phone number or email address">
                            <p class="mt-1 text-xs text-gray-500">We'll use this to contact you about your donation</p>
                            @error('contact')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 btn-primary py-3 rounded-xl">
                        <i class="fas fa-heart mr-2"></i>
                        <span id="submit-text">Proceed to Payment</span>
                    </button>
                    <a href="{{ url('/') }}" class="btn-secondary py-3 rounded-xl text-center">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Section -->
        <div class="card p-6 bg-blue-50 border-2 border-blue-200">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 text-xl mr-3 mt-1"></i>
                <div>
                    <h4 class="font-semibold text-blue-900 mb-2">About Donations</h4>
                    <p class="text-sm text-blue-800">
                        Your donations help support alumni events, networking opportunities, and the maintenance of this platform. 
                        All donations are greatly appreciated and contribute to the growth of the STU Alumni community.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cashRadio = document.getElementById('type_cash');
        const inKindRadio = document.getElementById('type_in_kind');
        const inKindFields = document.getElementById('in-kind-fields');
        const submitText = document.getElementById('submit-text');

        function toggleFields() {
            if (inKindRadio.checked) {
                inKindFields.classList.remove('hidden');
                submitText.textContent = 'Submit Donation';
            } else {
                inKindFields.classList.add('hidden');
                submitText.textContent = 'Proceed to Payment';
            }
        }

        cashRadio.addEventListener('change', toggleFields);
        inKindRadio.addEventListener('change', toggleFields);
        
        // Initial state
        toggleFields();
    });
</script>
@endpush
@endsection
