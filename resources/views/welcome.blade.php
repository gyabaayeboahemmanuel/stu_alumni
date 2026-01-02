@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Hero Section -->
    <div class="relative bg-stu-green text-white">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover mix-blend-overlay opacity-20" src="{{ asset('stu_campus.jpg') }}" alt="STU Campus">
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                        STU Alumni <span class="text-stu-red-light">Portal</span>
                    </h1>
                    <p class="mt-6 text-xl max-w-3xl">
                        Welcome to the Sunyani Technical University Alumni Network. 
                        Connect with fellow graduates, explore opportunities, and stay updated with university events.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4">
                        @auth
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="btn-accent text-center">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                                </a>
                            @else
                                <a href="{{ route('alumni.dashboard') }}" class="btn-accent text-center">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Go to Dashboard
                                </a>
                            @endif
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
                <div class="hidden lg:block">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <div class="text-3xl font-bold">2,500+</div>
                                <div class="text-white/80">Alumni Members</div>
                            </div>
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <div class="text-3xl font-bold">150+</div>
                                <div class="text-white/80">Business Listings</div>
                            </div>
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <div class="text-3xl font-bold">50+</div>
                                <div class="text-white/80">Events Yearly</div>
                            </div>
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <div class="text-3xl font-bold">10+</div>
                                <div class="text-white/80">Programmes</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Stay Connected, <span class="text-stu-green">Grow Together</span>
                </h2>
                <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                    Our alumni platform offers everything you need to stay connected with your alma mater and fellow graduates.
                </p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Feature 1 -->
                <div class="card bg-white p-8 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-users text-stu-green text-2xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">Network & Connect</h3>
                    <p class="mt-4 text-gray-600">
                        Connect with alumni across different years and programmes. Expand your professional network and build lasting relationships.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card bg-white p-8 text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-briefcase text-stu-red text-2xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">Career Opportunities</h3>
                    <p class="mt-4 text-gray-600">
                        Discover job opportunities, business partnerships, and career advancement resources from fellow alumni.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card bg-white p-8 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-calendar-alt text-stu-green text-2xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">Events & Reunions</h3>
                    <p class="mt-4 text-gray-600">
                        Stay updated with reunions, webinars, workshops, and other alumni events organized by the university.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Options -->
    @guest
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Join Our <span class="text-stu-green">Alumni Network</span></h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    Choose your preferred registration method to join the STU Alumni community
                </p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-2 max-w-4xl mx-auto">
                <!-- SIS Registration -->
                <div class="card bg-white p-8 text-center">
                    <div class="w-16 h-16 bg-stu-green rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">SIS Verification</h3>
                    <p class="mt-4 text-gray-600">
                        Quick registration using your Student Information System credentials. Verify your identity instantly.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('register') }}" class="btn-primary inline-block">
                            <i class="fas fa-bolt mr-2"></i>Register with SIS
                        </a>
                    </div>
                </div>

                <!-- Manual Registration -->
                <div class="card bg-white p-8 text-center">
                    <div class="w-16 h-16 bg-stu-red rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-user-edit text-white text-2xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">Manual Registration</h3>
                    <p class="mt-4 text-gray-600">
                        Complete the registration form manually. Perfect if you don't have access to your SIS credentials.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('register') }}" class="btn-accent inline-block">
                            <i class="fas fa-edit mr-2"></i>Register Manually
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <!-- Stats Section -->
    <div class="py-16 bg-gradient-to-r from-stu-green to-stu-green-dark text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-8 md:grid-cols-4 text-center">
                <div>
                    <div class="text-4xl font-bold">2,500+</div>
                    <div class="text-green-200 mt-2">Alumni Members</div>
                </div>
                <div>
                    <div class="text-4xl font-bold">150+</div>
                    <div class="text-green-200 mt-2">Business Listings</div>
                </div>
                <div>
                    <div class="text-4xl font-bold">50+</div>
                    <div class="text-green-200 mt-2">Events Yearly</div>
                </div>
                <div>
                    <div class="text-4xl font-bold">10+</div>
                    <div class="text-green-200 mt-2">Programmes</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Business Directory Preview -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Alumni <span class="text-stu-green">Business Directory</span></h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    Support businesses owned and operated by STU alumni. Discover services and products from your fellow graduates.
                </p>
            </div>

            <div class="mt-12">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Sample Business Cards -->
                    <div class="card bg-white rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-laptop-code text-stu-green"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Tech Solutions Ltd</h3>
                                <p class="text-gray-600 text-sm">IT Services & Software Development</p>
                                <div class="flex mt-2">
                                    <span class="text-xs bg-green-100 text-stu-green px-2 py-1 rounded">Class of 2015</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-white rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-utensils text-stu-red"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Urban Bites Restaurant</h3>
                                <p class="text-gray-600 text-sm">Fine Dining & Catering</p>
                                <div class="flex mt-2">
                                    <span class="text-xs bg-green-100 text-stu-green px-2 py-1 rounded">Class of 2018</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-white rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-heartbeat text-stu-green"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Wellness Center</h3>
                                <p class="text-gray-600 text-sm">Healthcare & Medical Services</p>
                                <div class="flex mt-2">
                                    <span class="text-xs bg-green-100 text-stu-green px-2 py-1 rounded">Class of 2012</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-12">
                    <a href="{{ route('businesses.public.index') }}" class="btn-primary">
                        <i class="fas fa-building mr-2"></i>View All Businesses
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Upcoming <span class="text-stu-green">Events</span></h2>
                <p class="mt-4 text-lg text-gray-600">
                    Join fellow alumni at these upcoming events and stay connected
                </p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Event 1 -->
                <div class="card bg-white rounded-xl overflow-hidden">
                    <div class="h-40 bg-gradient-to-r from-stu-green to-stu-green-dark flex items-center justify-center text-white">
                        <div class="text-center">
                            <div class="text-3xl font-bold">15</div>
                            <div class="text-lg">OCT</div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold">Annual Alumni Dinner</h3>
                        <p class="mt-2 text-gray-600">Join us for an evening of networking and reminiscing with fellow graduates.</p>
                        <div class="mt-4 flex items-center text-gray-500">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>STU Main Auditorium</span>
                        </div>
                        @auth
                            <div class="mt-6">
                                <a href="{{ route('alumni.events') }}" class="text-stu-green font-medium hover:text-stu-green-dark">
                                    <i class="fas fa-calendar-plus mr-1"></i>Register Now
                                </a>
                            </div>
                        @else
                            <div class="mt-6">
                                <a href="{{ route('login') }}" class="text-stu-green font-medium hover:text-stu-green-dark">
                                    <i class="fas fa-sign-in-alt mr-1"></i>Login to Register
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Event 2 -->
                <div class="card bg-white rounded-xl overflow-hidden">
                    <div class="h-40 bg-gradient-to-r from-stu-red to-stu-red-light flex items-center justify-center text-white">
                        <div class="text-center">
                            <div class="text-3xl font-bold">22</div>
                            <div class="text-lg">NOV</div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold">Career Development Workshop</h3>
                        <p class="mt-2 text-gray-600">Enhance your professional skills with our expert-led workshop series.</p>
                        <div class="mt-4 flex items-center text-gray-500">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>Online Event</span>
                        </div>
                        @auth
                            <div class="mt-6">
                                <a href="{{ route('alumni.events') }}" class="text-stu-green font-medium hover:text-stu-green-dark">
                                    <i class="fas fa-calendar-plus mr-1"></i>Register Now
                                </a>
                            </div>
                        @else
                            <div class="mt-6">
                                <a href="{{ route('login') }}" class="text-stu-green font-medium hover:text-stu-green-dark">
                                    <i class="fas fa-sign-in-alt mr-1"></i>Login to Register
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Event 3 -->
                <div class="card bg-white rounded-xl overflow-hidden">
                    <div class="h-40 bg-gradient-to-r from-stu-green to-stu-green-light flex items-center justify-center text-white">
                        <div class="text-center">
                            <div class="text-3xl font-bold">05</div>
                            <div class="text-lg">DEC</div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold">Tech Innovation Summit</h3>
                        <p class="mt-2 text-gray-600">Explore the latest trends in technology and innovation with industry leaders.</p>
                        <div class="mt-4 flex items-center text-gray-500">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>STU Engineering Block</span>
                        </div>
                        @auth
                            <div class="mt-6">
                                <a href="{{ route('alumni.events') }}" class="text-stu-green font-medium hover:text-stu-green-dark">
                                    <i class="fas fa-calendar-plus mr-1"></i>Register Now
                                </a>
                            </div>
                        @else
                            <div class="mt-6">
                                <a href="{{ route('login') }}" class="text-stu-green font-medium hover:text-stu-green-dark">
                                    <i class="fas fa-sign-in-alt mr-1"></i>Login to Register
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            @auth
                <div class="text-center mt-12">
                    <a href="{{ route('alumni.events') }}" class="btn-secondary">
                        <i class="fas fa-calendar-alt mr-2"></i>View All Events
                    </a>
                </div>
            @else
                <div class="text-center mt-12">
                    <a href="{{ route('register') }}" class="btn-primary">
                        <i class="fas fa-user-plus mr-2"></i>Join to Access Events
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection