@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Hero Section -->
    <div class="relative bg-stu-green text-white">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover mix-blend-overlay opacity-20" src="{{ asset('stu_campus.jpg') }}" alt="STU Campus">
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                    About <span class="text-stu-red-light">STU Alumni</span>
                </h1>
                <p class="mt-6 text-xl max-w-3xl mx-auto">
                    Connecting graduates of Sunyani Technical University for networking, opportunities, and lifelong connections.
                </p>
            </div>
        </div>
    </div>

    <!-- Mission Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Our Mission</h2>
                    <p class="mt-4 text-lg text-gray-600">
                        The STU Alumni Association is dedicated to fostering a strong, vibrant community of graduates who support each other's professional growth, maintain connections with the university, and contribute to the continued success of Sunyani Technical University.
                    </p>
                    <p class="mt-4 text-lg text-gray-600">
                        We strive to create meaningful opportunities for networking, career development, and lifelong learning while preserving the bonds formed during our time at STU.
                    </p>
                </div>
                <div class="bg-gradient-to-br from-stu-green to-stu-green-dark rounded-2xl p-8 text-white">
                    <h3 class="text-2xl font-bold mb-4">Our Vision</h3>
                    <p class="text-lg">
                        To be the premier alumni network that empowers graduates to achieve their professional goals while strengthening the legacy and impact of Sunyani Technical University.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900">Our Core Values</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                    The principles that guide our community and shape our interactions
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="card bg-white p-8 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-handshake text-stu-green text-2xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">Community</h3>
                    <p class="mt-4 text-gray-600">
                        Building strong, supportive relationships among alumni that last a lifetime.
                    </p>
                </div>

                <div class="card bg-white p-8 text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-graduation-cap text-stu-red text-2xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">Excellence</h3>
                    <p class="mt-4 text-gray-600">
                        Maintaining the high standards of education and professionalism instilled at STU.
                    </p>
                </div>

                <div class="card bg-white p-8 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-seedling text-stu-green text-2xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">Growth</h3>
                    <p class="mt-4 text-gray-600">
                        Supporting continuous learning and professional development for all members.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- What We Do Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900">What We Do</h2>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-stu-green rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-semibold text-gray-900">Networking Events</h3>
                        <p class="mt-2 text-gray-600">
                            Organize regular meetups, reunions, and networking events to help alumni connect and build professional relationships.
                        </p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-stu-red rounded-lg flex items-center justify-center">
                            <i class="fas fa-briefcase text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-semibold text-gray-900">Career Support</h3>
                        <p class="mt-2 text-gray-600">
                            Provide job opportunities, mentorship programs, and career development resources to help alumni advance their careers.
                        </p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-stu-green rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-semibold text-gray-900">Business Directory</h3>
                        <p class="mt-2 text-gray-600">
                            Maintain a directory of alumni-owned businesses to promote entrepreneurship and support fellow graduates.
                        </p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-stu-red rounded-lg flex items-center justify-center">
                            <i class="fas fa-university text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-semibold text-gray-900">University Partnership</h3>
                        <p class="mt-2 text-gray-600">
                            Collaborate with the university to support current students, contribute to development projects, and strengthen the STU community.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="py-16 bg-gradient-to-r from-stu-green to-stu-green-dark text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-extrabold">Join Our Community</h2>
            <p class="mt-4 text-xl max-w-2xl mx-auto">
                Become part of a growing network of STU graduates making a difference in their communities and industries.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('alumni.dashboard') }}" class="btn-accent text-center">
                        <i class="fas fa-tachometer-alt mr-2"></i>Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-accent text-center">
                        <i class="fas fa-user-plus mr-2"></i>Join Alumni Network
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary text-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
