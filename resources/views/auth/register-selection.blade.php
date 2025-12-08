@extends('layouts.app')

@section('title', 'Join Alumni Network')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Join STU Alumni Network
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Choose your registration method based on your graduation year
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <!-- SIS Registration Option -->
            <div class="card p-6 mb-6 border-2 border-blue-200 hover:border-blue-400 transition duration-200">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Graduated 2014 or Later</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Quick verification using your student ID and personal details. 
                            Your information will be automatically verified through our system.
                        </p>
                        <ul class="mt-2 text-sm text-gray-600 space-y-1">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Instant verification</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Auto-filled information</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Quick setup</li>
                        </ul>
                        <a href="{{ route('register.sis') }}" class="mt-4 btn-primary inline-block">
                            Register with SIS Verification
                        </a>
                    </div>
                </div>
            </div>

            <!-- Manual Registration Option -->
            <div class="card p-6 border-2 border-green-200 hover:border-green-400 transition duration-200">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-edit text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Graduated Before 2014</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Manual registration with document verification. 
                            Please have your certificate or transcript ready for upload.
                        </p>
                        <ul class="mt-2 text-sm text-gray-600 space-y-1">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>For pre-2014 graduates</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Document verification</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Admin approval required</li>
                        </ul>
                        <a href="{{ route('register.manual') }}" class="mt-4 btn-success inline-block">
                            Register Manually
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
