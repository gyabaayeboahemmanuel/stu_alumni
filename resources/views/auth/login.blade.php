@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Campus Background -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('stu_campus.jpg') }}" alt="STU Campus" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-white/95 via-white/90 to-gray-100/95"></div>
    </div>
    
    <div class="sm:mx-auto sm:w-full sm:max-w-md animate-fade-in-up relative z-10">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-xl mb-4 border-2 border-stu-green/20">
                <img src="{{ asset('stu_logo.png') }}" alt="STU Logo" class="w-16 h-16">
            </div>
            <h2 class="text-3xl font-bold text-gray-900">
                Welcome Back
            </h2>
            <p class="mt-2 text-gray-600">
                Sign in to your STU Alumni account using email, phone, or student ID
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md animate-fade-in-up relative z-10" style="animation-delay: 0.1s">
        <div class="bg-white/95 backdrop-blur-md py-8 px-6 shadow-2xl rounded-2xl border border-gray-100 hover-lift">
            <form class="space-y-6" action="{{ route('login.process') }}" method="POST" style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
                @csrf

                <div>
                    <label for="identifier" class="form-label flex items-center">
                        <i class="fas fa-user text-stu-green mr-2"></i>Email, Phone, or Student ID
                    </label>
                    <input id="identifier" name="identifier" type="text" autocomplete="username" required
                           class="form-input focus:ring-2 focus:ring-stu-green"
                           value="{{ old('identifier') }}"
                           placeholder="Enter your email, phone, or student ID">
                    <div class="mt-1 text-xs text-gray-500">
                        You can login using your email address, phone number, or student ID
                    </div>
                    @error('identifier')
                        <p class="mt-1 text-sm text-stu-red flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="form-label flex items-center">
                        <i class="fas fa-lock text-stu-green mr-2"></i>Password
                    </label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                           class="form-input focus:ring-2 focus:ring-stu-green"
                           placeholder="Enter your password">
                    @error('password')
                        <p class="mt-1 text-sm text-stu-red flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-4 w-4 text-stu-green focus:ring-stu-green border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-stu-green hover:text-stu-green-dark transition-colors">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full btn-primary rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 py-3">
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign in
                    </button>
                </div>
            </form>

            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500 font-medium">
                            New to STU Alumni?
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('register') }}" class="w-full btn-outline inline-flex items-center justify-center py-3 rounded-xl font-semibold">
                        <i class="fas fa-user-plus mr-2"></i>Create Alumni Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
