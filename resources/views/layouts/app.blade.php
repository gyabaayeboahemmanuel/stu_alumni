<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50" style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('stu_logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('stu_logo.png') }}">
    <title>@yield('title', 'STU Alumni Portal') - Sunyani Technical University</title>
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0ea5e9">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="apple-touch-icon" href="{{ asset('stu_logo.png') }}">
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Compiled Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom Styles -->
    <style>
        /* Global Typography */
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            letter-spacing: -0.01em;
            line-height: 1.6;
            color: #1F2937;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.2;
            color: #111827;
        }
        
        p {
            line-height: 1.7;
            color: #374151;
        }
        
        /* Ensure text on dark green backgrounds is white */
        .bg-stu-green,
        .bg-stu-green-dark,
        .bg-stu-green-light,
        [class*="from-stu-green"],
        [class*="to-stu-green"] {
            color: white !important;
        }
        
        .bg-stu-green *,
        .bg-stu-green-dark *,
        .bg-stu-green-light *,
        [class*="from-stu-green"] *,
        [class*="to-stu-green"] * {
            color: white !important;
        }
        
        /* Exception for icons and specific elements that should remain their color */
        .bg-stu-green i,
        .bg-stu-green-dark i,
        .bg-stu-green-light i,
        [class*="from-stu-green"] i,
        [class*="to-stu-green"] i {
            color: white !important;
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
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .gradient-stu-green {
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
        }
        
        .gradient-stu-red {
            background: linear-gradient(135deg, #D32F2F 0%, #B71C1C 100%);
        }
        
        /* Enhanced Input Focus States */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        input[type="url"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        textarea,
        select {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            line-height: 1.5;
        }
        
        input::placeholder,
        textarea::placeholder {
            color: #9CA3AF;
            opacity: 1;
            font-weight: 400;
        }
        
        /* File Input Styling */
        input[type="file"] {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        input[type="file"]::file-selector-button {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            border: none;
            background: linear-gradient(135deg, #1B5E20 0%, #2E7D32 100%);
            color: white;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        input[type="file"]::file-selector-button:hover {
            background: linear-gradient(135deg, #0D3C11 0%, #1B5E20 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            @apply bg-stu-green hover:bg-stu-green-dark text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98];
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            letter-spacing: -0.01em;
        }
        .btn-secondary {
            @apply bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98];
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            letter-spacing: -0.01em;
        }
        .btn-success {
            @apply bg-stu-green hover:bg-stu-green-dark text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98];
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            letter-spacing: -0.01em;
        }
        .btn-danger {
            @apply bg-stu-red hover:bg-stu-red-light text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98];
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            letter-spacing: -0.01em;
        }
        .btn-accent {
            @apply bg-stu-red hover:bg-stu-red-light text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98];
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            letter-spacing: -0.01em;
        }
        .btn-outline {
            @apply border-2 border-stu-green text-stu-green hover:bg-stu-green hover:text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-[1.02] active:scale-[0.98];
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            letter-spacing: -0.01em;
        }
        .card {
            @apply bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition duration-300;
        }
        .form-input {
            @apply w-full px-4 py-3 text-base border-2 border-gray-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-stu-green focus:ring-opacity-30 focus:border-stu-green transition-all duration-200 bg-white text-gray-900 placeholder-gray-500 shadow-sm hover:border-gray-500;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            border-color: #9CA3AF;
        }
        .form-input:focus {
            border-color: #1B5E20 !important;
            box-shadow: 0 0 0 3px rgba(27, 94, 32, 0.15), 0 0 0 1px #1B5E20;
        }
        .form-input:hover:not(:focus):not([readonly]):not([disabled]) {
            border-color: #6B7280;
        }
        .form-input[readonly] {
            @apply bg-gray-50 cursor-not-allowed;
            border-color: #9CA3AF !important;
            border-width: 2px;
        }
        .form-input[readonly]:hover {
            border-color: #9CA3AF !important;
        }
        .form-input[disabled] {
            @apply bg-gray-100 border-gray-300 cursor-not-allowed opacity-60;
            border-color: #D1D5DB !important;
        }
        .form-select {
            @apply w-full px-4 py-3 text-base border-2 border-gray-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-stu-green focus:ring-opacity-30 focus:border-stu-green transition-all duration-200 bg-white text-gray-900 shadow-sm hover:border-gray-500 cursor-pointer;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            border-color: #9CA3AF;
        }
        .form-select:focus {
            border-color: #1B5E20 !important;
            box-shadow: 0 0 0 3px rgba(27, 94, 32, 0.15), 0 0 0 1px #1B5E20;
        }
        .form-select:hover:not(:focus) {
            border-color: #6B7280;
        }
        .form-textarea {
            @apply w-full px-4 py-3 text-base border-2 border-gray-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-stu-green focus:ring-opacity-30 focus:border-stu-green transition-all duration-200 bg-white text-gray-900 placeholder-gray-500 shadow-sm hover:border-gray-500 resize-y;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100px;
            border-color: #9CA3AF;
        }
        .form-textarea:focus {
            border-color: #1B5E20 !important;
            box-shadow: 0 0 0 3px rgba(27, 94, 32, 0.15), 0 0 0 1px #1B5E20;
        }
        .form-textarea:hover:not(:focus) {
            border-color: #6B7280;
        }
        .form-label {
            @apply block text-sm font-semibold text-gray-800 mb-2 tracking-wide;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .form-error {
            @apply text-stu-red text-sm mt-1;
        }
        .alert-success {
            @apply bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg;
        }
        .alert-error {
            @apply bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg;
        }
        .alert-warning {
            @apply bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg;
        }
        .alert-info {
            @apply bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg;
        }
        .nav-active {
            @apply bg-stu-green-dark text-white;
        }
        .nav-hover {
            @apply hover:bg-stu-green-light hover:text-white transition duration-200;
        }
        .badge-success {
            @apply bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded;
        }
        .badge-warning {
            @apply bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded;
        }
        .badge-error {
            @apply bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded;
        }
        .badge-info {
            @apply bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded;
        }
        .table-header {
            @apply bg-stu-green text-white;
        }
    </style>
    
    @stack('styles')
</head>
<body class="h-full">
    <!-- Flash Messages with SweetAlert2 -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: {!! json_encode(session('success')) !!},
                    timer: 5000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    confirmButtonColor: '#1B5E20',
                    toast: true,
                    position: 'top-end'
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: {!! json_encode(session('error')) !!},
                    timer: 7000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    confirmButtonColor: '#B71C1C',
                    toast: true,
                    position: 'top-end'
                });
            });
        </script>
    @endif

    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: {!! json_encode(session('warning')) !!},
                    timer: 6000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    confirmButtonColor: '#F59E0B',
                    toast: true,
                    position: 'top-end'
                });
            });
        </script>
    @endif

    @if(session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: {!! json_encode(session('info')) !!},
                    timer: 5000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    confirmButtonColor: '#3B82F6',
                    toast: true,
                    position: 'top-end'
                });
            });
        </script>
    @endif

        <!-- Navigation -->
        <nav class="bg-stu-green text-white shadow-lg">
        @guest
            <!-- Public Navigation (for unauthenticated users) -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="shrink-0 flex items-center">
                            <img src="{{ asset('stu_logo.png') }}" alt="STU Logo" class="h-10 w-10 mr-3">
                            <span class="text-xl font-bold">STU Alumni</span>
                        </a>
                        
                        <div class="hidden md:ml-6 md:flex md:space-x-1">
                            <a href="{{ url('/') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->is('/') ? 'nav-active' : 'nav-hover' }}">
                                <i class="fas fa-home mr-1"></i>Home
                            </a>
                            <a href="{{ route('about') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('about') ? 'nav-active' : 'nav-hover' }}">
                                <i class="fas fa-info-circle mr-1"></i>About
                            </a>
                            <a href="{{ route('executives') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('executives') ? 'nav-active' : 'nav-hover' }}">
                                <i class="fas fa-users-cog mr-1"></i>Executives
                            </a>
                            <a href="{{ route('businesses.public.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('businesses.public.*') ? 'nav-active' : 'nav-hover' }}">
                                <i class="fas fa-briefcase mr-1"></i>Business Directory
                            </a>
                        </div>
                    </div>

                    <!-- Auth Links -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('donations.create') }}" class="px-4 py-2 rounded-md text-sm font-medium transition duration-200 bg-stu-red hover:bg-stu-red-light text-white">
                            <i class="fas fa-heart mr-1"></i>Donate
                        </a>
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-md text-sm font-medium transition duration-200 hover:bg-stu-green-light">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-md text-sm font-medium transition duration-200 bg-stu-green hover:bg-stu-green-light">
                            <i class="fas fa-user-plus mr-1"></i>Register
                        </a>
                    </div>
                </div>
                            </div>
        @else
            <!-- Authenticated User Navigation -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="shrink-0 flex items-center">
                            <img src="{{ asset('stu_logo.png') }}" alt="STU Logo" class="h-10 w-10 mr-3">
                            <span class="text-xl font-bold">STU Alumni</span>
                        </a>
                        
                        @if(Auth::user()->isAdmin())
                            <!-- Admin Navigation -->
                            <div class="hidden md:ml-6 md:flex md:space-x-1">
                                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'nav-active' : 'nav-hover' }}">
                                    <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                                </a>
                                <a href="{{ route('admin.alumni.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('admin.alumni.*') ? 'nav-active' : 'nav-hover' }}">
                                    <i class="fas fa-users mr-1"></i>Alumni
                                </a>
                                <a href="{{ route('admin.announcements.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('admin.announcements.*') ? 'nav-active' : 'nav-hover' }}">
                                    <i class="fas fa-bullhorn mr-1"></i>Announcements
                                </a>
                                <a href="{{ route('admin.events.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('admin.events.*') ? 'nav-active' : 'nav-hover' }}">
                                    <i class="fas fa-calendar-alt mr-1"></i>Events
                                </a>
                                <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('admin.reports.*') ? 'nav-active' : 'nav-hover' }}">
                                    <i class="fas fa-chart-bar mr-1"></i>Reports
                                </a>
                                <a href="{{ route('admin.year-groups.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('admin.year-groups.*') ? 'nav-active' : 'nav-hover' }}">
                                    <i class="fas fa-users-cog mr-1"></i>Year Groups
                                </a>
                                <a href="{{ route('admin.settings.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('admin.settings.*') ? 'nav-active' : 'nav-hover' }}">
                                    <i class="fas fa-cog mr-1"></i>Settings
                                </a>
                            </div>
                        @elseif(Auth::user()->alumni)
                            <!-- Alumni Navigation -->
                            <div class="hidden md:ml-6 md:flex md:space-x-1">
                                <a href="{{ route('alumni.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('alumni.dashboard') ? 'nav-active' : 'nav-hover' }}">
                                    <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                                </a>
                                <a href="{{ route('alumni.profile') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('alumni.profile*') ? 'nav-active' : 'nav-hover' }}">
                                    <i class="fas fa-user mr-1"></i>Profile
                                </a>
                                <a href="{{ route('alumni.announcements') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('alumni.announcements*') ? 'nav-active' : 'nav-hover' }}">
                                    <i class="fas fa-bullhorn mr-1"></i>Announcements
                                </a>
                                <a href="{{ route('alumni.events') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('alumni.events*') ? 'nav-active' : 'nav-hover' }}">
                                    <i class="fas fa-calendar-alt mr-1"></i>Events
                                </a>
                                <a href="{{ route('alumni.businesses.my-businesses') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('alumni.businesses.*') ? 'nav-active' : 'nav-hover' }}">
                                    <i class="fas fa-briefcase mr-1"></i>Business Directory
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center">
                        <!-- Notifications Bell -->
                        <div class="relative mr-4" x-data="{ open: false }">
                            <button @click="open = !open" class="text-white hover:text-gray-200 transition duration-200">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute -top-1 -right-1 bg-stu-red text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                            </button>
                            
                            <!-- Notifications Dropdown -->
                            <div x-show="open" @click.away="open = false" 
                                 class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                 style="background-color: white; color: #1F2937;"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95">
                                <div class="py-2 px-4 border-b border-gray-200 bg-white">
                                    <h3 class="text-sm font-semibold text-gray-900" style="color: #111827;">Notifications</h3>
                                </div>
                                <div class="max-h-60 overflow-y-auto bg-white">
                                    <!-- Notification Items -->
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 bg-white">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-calendar text-stu-green text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm text-gray-800" style="color: #1F2937;">New event: Annual Alumni Dinner</p>
                                                <p class="text-xs text-gray-500 mt-1" style="color: #6B7280;">2 hours ago</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 bg-white">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-bullhorn text-blue-600 text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm text-gray-800" style="color: #1F2937;">New announcement from STU</p>
                                                <p class="text-xs text-gray-500 mt-1" style="color: #6B7280;">1 day ago</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="py-2 px-4 border-t border-gray-200 bg-white">
                                    <a href="#" class="text-sm text-stu-green hover:text-stu-green-dark font-medium" style="color: #1B5E20;">View all notifications</a>
                                </div>
                            </div>
                        </div>

                        <!-- User Dropdown -->
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open" class="max-w-xs flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-white text-white hover:text-gray-200">
                                    <span class="sr-only">Open user menu</span>
                                    @if(Auth::user()->alumni && Auth::user()->alumni->profile_photo_path)
                                        <img class="h-8 w-8 rounded-full bg-white" 
                                             src="{{ asset('storage/' . Auth::user()->alumni->profile_photo_path) }}" 
                                             alt="{{ Auth::user()->name }}">
                                    @else
                                        <img class="h-8 w-8 rounded-full bg-white" 
                                             src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=FFFFFF&background=1B5E20" 
                                             alt="{{ Auth::user()->name }}">
                                    @endif
                                    <span class="ml-2 hidden md:block text-white">{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down ml-1 text-sm text-white"></i>
                                </button>
                            </div>
                            
                            <div x-show="open" @click.away="open = false" 
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                 style="background-color: white !important;"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95">
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-100" style="color: #1B5E20 !important;">
                                        <i class="fas fa-tachometer-alt mr-2" style="color: #1B5E20;"></i>Admin Dashboard
                                    </a>
                                    <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100" style="color: #1B5E20 !important;">
                                        <i class="fas fa-cog mr-2" style="color: #1B5E20;"></i>Settings
                                    </a>
                                @elseif(Auth::user()->alumni)
                                    <a href="{{ route('alumni.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-100" style="color: #1B5E20 !important;">
                                        <i class="fas fa-tachometer-alt mr-2" style="color: #1B5E20;"></i>Dashboard
                                    </a>
                                    <a href="{{ route('alumni.profile') }}" class="block px-4 py-2 text-sm hover:bg-gray-100" style="color: #1B5E20 !important;">
                                        <i class="fas fa-user mr-2" style="color: #1B5E20;"></i>Profile
                                    </a>
                                    {{-- Connections feature not yet implemented --}}
                                    {{-- <a href="{{ route('alumni.connections') }}" class="block px-4 py-2 text-sm hover:bg-gray-100" style="color: #1B5E20 !important;">
                                        <i class="fas fa-users mr-2" style="color: #1B5E20;"></i>Connections
                                    </a> --}}
                                @endif
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100" style="color: #DC2626 !important;">
                                        <i class="fas fa-sign-out-alt mr-2" style="color: #DC2626;"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endguest
        </nav>

    <!-- Page Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-stu-green text-white mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('stu_logo.png') }}" alt="STU Logo" class="h-10 w-10 mr-3">
                        <span class="text-xl font-bold">STU Alumni Portal</span>
                    </div>
                    <p class="text-green-200 max-w-md">
                        Connecting graduates of Sunyani Technical University for networking, opportunities, and lifelong connections.
                    </p>
                    <div class="flex space-x-4 mt-4">
                        @php
                            $facebook = \App\Models\SiteSetting::get('facebook_url');
                            $twitter = \App\Models\SiteSetting::get('twitter_url');
                            $linkedin = \App\Models\SiteSetting::get('linkedin_url');
                            $instagram = \App\Models\SiteSetting::get('instagram_url');
                            $youtube = \App\Models\SiteSetting::get('youtube_url');
                        @endphp
                        
                        @if($facebook)
                            <a href="{{ $facebook }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 bg-stu-green-light rounded-full flex items-center justify-center hover:bg-stu-red transition-colors">
                            <i class="fab fa-facebook-f text-white text-sm"></i>
                        </a>
                        @endif
                        
                        @if($twitter)
                            <a href="{{ $twitter }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 bg-stu-green-light rounded-full flex items-center justify-center hover:bg-stu-red transition-colors">
                            <i class="fab fa-twitter text-white text-sm"></i>
                        </a>
                        @endif
                        
                        @if($linkedin)
                            <a href="{{ $linkedin }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 bg-stu-green-light rounded-full flex items-center justify-center hover:bg-stu-red transition-colors">
                            <i class="fab fa-linkedin-in text-white text-sm"></i>
                        </a>
                        @endif
                        
                        @if($instagram)
                            <a href="{{ $instagram }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 bg-stu-green-light rounded-full flex items-center justify-center hover:bg-stu-red transition-colors">
                            <i class="fab fa-instagram text-white text-sm"></i>
                        </a>
                        @endif
                        
                        @if($youtube)
                            <a href="{{ $youtube }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 bg-stu-green-light rounded-full flex items-center justify-center hover:bg-stu-red transition-colors">
                                <i class="fab fa-youtube text-white text-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/') }}" class="text-green-200 hover:text-white transition-colors">Home</a></li>
                        @auth
                            @if(Auth::user()->isAdmin())
                                <li><a href="{{ route('admin.dashboard') }}" class="text-green-200 hover:text-white transition-colors">Admin Dashboard</a></li>
                            @else
                                <li><a href="{{ route('alumni.dashboard') }}" class="text-green-200 hover:text-white transition-colors">Dashboard</a></li>
                                <li><a href="{{ route('alumni.events') }}" class="text-green-200 hover:text-white transition-colors">Events</a></li>
                                <li><a href="{{ route('alumni.businesses.my-businesses') }}" class="text-green-200 hover:text-white transition-colors">Business Directory</a></li>
                            @endif
                        @else
                            <li><a href="{{ route('login') }}" class="text-green-200 hover:text-white transition-colors">Login</a></li>
                            <li><a href="{{ route('register') }}" class="text-green-200 hover:text-white transition-colors">Register</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <div class="space-y-2 text-green-200">
                        @php
                            $contactAddress = \App\Models\SiteSetting::get('contact_address', 'Alumni Office, Sunyani Technical University');
                            $contactEmail = \App\Models\SiteSetting::get('contact_email', 'alumni@stu.edu.gh');
                            $contactPhone = \App\Models\SiteSetting::get('contact_phone', '+233 (0) 35 209 1234');
                        @endphp
                        <p class="flex items-start">
                            <i class="fas fa-map-marker-alt mr-2 mt-1 text-stu-red-light"></i>
                            <span>{!! nl2br(e($contactAddress)) !!}</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-stu-red-light"></i>
                            <a href="mailto:{{ $contactEmail }}" class="hover:text-white transition-colors">{{ $contactEmail }}</a>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-phone mr-2 text-stu-red-light"></i>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}" class="hover:text-white transition-colors">{{ $contactPhone }}</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="border-t border-stu-green-light mt-8 pt-8 text-center text-green-200">
                <p>&copy; {{ date('Y') }} Sunyani Technical University Alumni Association. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(err => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
</body>
</html>