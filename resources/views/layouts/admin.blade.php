<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50" style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('stu_logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('stu_logo.png') }}">
    <title>@yield('title', 'Admin Dashboard') - STU Alumni Portal</title>
    
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
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            transition: all 0.3s ease-in-out;
        }
        
        .sidebar-collapsed {
            width: 80px;
        }
        
        .sidebar-link {
            transition: all 0.2s ease;
        }
        
        .sidebar-link:hover {
            background-color: rgba(46, 125, 50, 0.1);
            transform: translateX(4px);
        }
        
        .sidebar-link.active {
            background-color: #1B5E20;
            color: white;
            font-weight: 600;
        }
        
        .main-content {
            margin-left: 280px;
            transition: margin-left 0.3s ease-in-out;
        }
        
        .main-content-expanded {
            margin-left: 80px;
        }
        
        /* Mobile Sidebar */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
            }
            
            .sidebar-open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Smooth scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: #1B5E20;
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #0D3C11;
        }
    </style>
    
    @stack('styles')
</head>
<body class="h-full" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
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

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 md:hidden">
    </div>

    <!-- Sidebar -->
    <aside class="sidebar fixed top-0 left-0 h-screen bg-white shadow-xl overflow-y-auto"
           :class="{ 'sidebar-open': sidebarOpen, 'sidebar-collapsed': sidebarCollapsed }"
           x-cloak>
        
        <!-- Sidebar Header -->
        <div class="sticky top-0 bg-gradient-to-br from-stu-green to-stu-green-dark p-6 z-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('stu_logo.png') }}" alt="STU Logo" class="h-10 w-10">
                    <div x-show="!sidebarCollapsed" class="text-white">
                        <div class="font-bold text-lg leading-tight">STU Alumni</div>
                        <div class="text-xs text-green-200">Admin Portal</div>
                    </div>
                </a>
                <button @click="sidebarCollapsed = !sidebarCollapsed" 
                        class="hidden md:block text-white hover:text-green-200 transition-colors">
                    <i class="fas" :class="sidebarCollapsed ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
                </button>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="px-3 py-6 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Dashboard</span>
            </a>

            <!-- Alumni Management -->
            <a href="{{ route('admin.alumni.index') }}" 
               class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.alumni.*') ? 'active' : '' }}">
                <i class="fas fa-users w-5"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Alumni</span>
            </a>

            <!-- Announcements -->
            <a href="{{ route('admin.announcements.index') }}" 
               class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn w-5"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Announcements</span>
            </a>

            <!-- Events -->
            <a href="{{ route('admin.events.index') }}" 
               class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt w-5"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Events</span>
            </a>

            <!-- Reports -->
            <a href="{{ route('admin.reports.index') }}" 
               class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar w-5"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Reports</span>
            </a>

            <!-- Year Groups -->
            <a href="{{ route('admin.year-groups.index') }}" 
               class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.year-groups.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog w-5"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Year Groups</span>
            </a>

            <!-- Chapters -->
            <a href="{{ route('admin.chapters.index') }}" 
               class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.chapters.*') ? 'active' : '' }}">
                <i class="fas fa-map-marker-alt w-5"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Chapters</span>
            </a>

            <!-- Divider -->
            <div x-show="!sidebarCollapsed" class="border-t border-gray-200 my-4"></div>
            <div x-show="sidebarCollapsed" class="border-t border-gray-200 my-2 mx-4"></div>

            <!-- Broadcast -->
            <a href="{{ route('admin.broadcast.index') }}" 
               class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.broadcast.*') ? 'active' : '' }}">
                <i class="fas fa-broadcast-tower w-5"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Broadcast</span>
            </a>

            <!-- Settings -->
            <a href="{{ route('admin.settings.index') }}" 
               class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog w-5"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Settings</span>
            </a>
        </nav>

        <!-- User Profile (Bottom) -->
        <div class="absolute bottom-0 left-0 right-0 border-t border-gray-200 bg-white">
            <div class="p-4" x-data="{ profileOpen: false }">
                <button @click="profileOpen = !profileOpen" 
                        class="w-full flex items-center space-x-3 hover:bg-gray-100 rounded-lg p-2 transition-colors">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=FFFFFF&background=1B5E20" 
                         alt="{{ Auth::user()->name }}" 
                         class="h-10 w-10 rounded-full">
                    <div x-show="!sidebarCollapsed" class="flex-1 text-left">
                        <div class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500">Administrator</div>
                    </div>
                    <i x-show="!sidebarCollapsed" class="fas fa-chevron-up text-gray-400 text-sm" :class="{'fa-chevron-down': !profileOpen, 'fa-chevron-up': profileOpen}"></i>
                </button>
                
                <div x-show="profileOpen && !sidebarCollapsed" 
                     x-transition
                     class="mt-2 space-y-1">
                    <a href="{{ url('/') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-home mr-2"></i>View Website
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content" :class="{ 'main-content-expanded': sidebarCollapsed }">
        <!-- Top Bar -->
        <header class="bg-white shadow-sm sticky top-0 z-30">
            <div class="flex items-center justify-between px-6 py-4">
                <!-- Mobile Menu Button -->
                <button @click="sidebarOpen = true" class="md:hidden text-gray-600 hover:text-gray-900">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Page Title (Optional) -->
                <div class="hidden md:block">
                    <h1 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                </div>

                <!-- Right Side Items -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative" x-data="{ notifOpen: false }">
                        <button @click="notifOpen = !notifOpen" 
                                class="relative text-gray-600 hover:text-gray-900 transition-colors">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                        </button>
                    </div>

                    <!-- Quick Actions -->
                    <a href="{{ url('/') }}" 
                       class="hidden md:inline-flex items-center px-4 py-2 bg-stu-green text-white rounded-lg hover:bg-stu-green-dark transition-colors text-sm">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        View Site
                    </a>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="min-h-screen">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>

