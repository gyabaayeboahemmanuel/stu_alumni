<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'STU Alumni Portal') - Sunyani Technical University</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Styles -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'stu-green': '#1B5E20',
                        'stu-green-light': '#2E7D32',
                        'stu-green-dark': '#0D3C11',
                        'stu-red': '#B71C1C',
                        'stu-red-light': '#D32F2F',
                        'stu-white': '#FFFFFF',
                    }
                }
            }
        }
    </script>
    <style>
        .btn-primary {
            @apply bg-stu-green hover:bg-stu-green-dark text-white font-medium py-2 px-4 rounded-lg transition duration-200 shadow-sm hover:shadow-md;
        }
        .btn-secondary {
            @apply bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 shadow-sm hover:shadow-md;
        }
        .btn-success {
            @apply bg-stu-green hover:bg-stu-green-dark text-white font-medium py-2 px-4 rounded-lg transition duration-200 shadow-sm hover:shadow-md;
        }
        .btn-danger {
            @apply bg-stu-red hover:bg-stu-red-light text-white font-medium py-2 px-4 rounded-lg transition duration-200 shadow-sm hover:shadow-md;
        }
        .btn-accent {
            @apply bg-stu-red hover:bg-stu-red-light text-white font-medium py-2 px-4 rounded-lg transition duration-200 shadow-sm hover:shadow-md;
        }
        .btn-outline {
            @apply border border-stu-green text-stu-green hover:bg-stu-green hover:text-white font-medium py-2 px-4 rounded-lg transition duration-200;
        }
        .card {
            @apply bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition duration-200;
        }
        .form-input {
            @apply w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-stu-green focus:border-transparent;
        }
        .form-select {
            @apply w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-stu-green focus:border-transparent;
        }
        .form-textarea {
            @apply w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-stu-green focus:border-transparent;
        }
        .form-label {
            @apply block text-sm font-medium text-gray-700 mb-1;
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
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed top-4 right-4 z-50 alert-success max-w-sm" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600"></i>
                <span class="flex-1">{{ session('success') }}</span>
                <button @click="show = false" class="ml-4 text-green-600 hover:text-green-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 z-50 alert-error max-w-sm" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2 text-red-600"></i>
                <span class="flex-1">{{ session('error') }}</span>
                <button @click="show = false" class="ml-4 text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="fixed top-4 right-4 z-50 alert-warning max-w-sm" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2 text-yellow-600"></i>
                <span class="flex-1">{{ session('warning') }}</span>
                <button @click="show = false" class="ml-4 text-yellow-600 hover:text-yellow-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="fixed top-4 right-4 z-50 alert-info max-w-sm" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                <span class="flex-1">{{ session('info') }}</span>
                <button @click="show = false" class="ml-4 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @auth
        <!-- Navigation -->
        <nav class="bg-stu-green text-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="shrink-0 flex items-center">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mr-3">
                                <span class="text-stu-green font-bold text-lg">STU</span>
                            </div>
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
                                <a href="{{ route('businesses.my-businesses') }}" class="px-3 py-2 rounded-md text-sm font-medium transition duration-200 {{ request()->routeIs('businesses.*') ? 'nav-active' : 'nav-hover' }}">
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
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95">
                                <div class="py-2 px-4 border-b border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                </div>
                                <div class="max-h-60 overflow-y-auto">
                                    <!-- Notification Items -->
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-calendar text-stu-green text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm text-gray-800">New event: Annual Alumni Dinner</p>
                                                <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-bullhorn text-blue-600 text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm text-gray-800">New announcement from STU</p>
                                                <p class="text-xs text-gray-500 mt-1">1 day ago</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="py-2 px-4 border-t border-gray-200">
                                    <a href="#" class="text-sm text-stu-green hover:text-stu-green-dark font-medium">View all notifications</a>
                                </div>
                            </div>
                        </div>

                        <!-- User Dropdown -->
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open" class="max-w-xs flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-white">
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
                                    <span class="ml-2 hidden md:block">{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down ml-1 text-sm"></i>
                                </button>
                            </div>
                            
                            <div x-show="open" @click.away="open = false" 
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95">
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2 text-stu-green"></i>Admin Dashboard
                                    </a>
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2 text-stu-green"></i>Settings
                                    </a>
                                @elseif(Auth::user()->alumni)
                                    <a href="{{ route('alumni.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2 text-stu-green"></i>Dashboard
                                    </a>
                                    <a href="{{ route('alumni.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2 text-stu-green"></i>Profile
                                    </a>
                                    <a href="{{ route('alumni.connections') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-users mr-2 text-stu-green"></i>Connections
                                    </a>
                                @endif
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2 text-stu-red"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

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
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mr-3">
                            <span class="text-stu-green font-bold text-lg">STU</span>
                        </div>
                        <span class="text-xl font-bold">STU Alumni Portal</span>
                    </div>
                    <p class="text-green-200 max-w-md">
                        Connecting graduates of Sunyani Technical University for networking, opportunities, and lifelong connections.
                    </p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="w-8 h-8 bg-stu-green-light rounded-full flex items-center justify-center hover:bg-stu-red transition-colors">
                            <i class="fab fa-facebook-f text-white text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-stu-green-light rounded-full flex items-center justify-center hover:bg-stu-red transition-colors">
                            <i class="fab fa-twitter text-white text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-stu-green-light rounded-full flex items-center justify-center hover:bg-stu-red transition-colors">
                            <i class="fab fa-linkedin-in text-white text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-stu-green-light rounded-full flex items-center justify-center hover:bg-stu-red transition-colors">
                            <i class="fab fa-instagram text-white text-sm"></i>
                        </a>
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
                                <li><a href="{{ route('businesses.my-businesses') }}" class="text-green-200 hover:text-white transition-colors">Business Directory</a></li>
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
                        <p class="flex items-start">
                            <i class="fas fa-map-marker-alt mr-2 mt-1 text-stu-red-light"></i>
                            <span>Alumni Office<br>Sunyani Technical University</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-stu-red-light"></i>
                            <span>alumni@stu.edu.gh</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-phone mr-2 text-stu-red-light"></i>
                            <span>+233 (0) 35 209 1234</span>
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
</body>
</html>