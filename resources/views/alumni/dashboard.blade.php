@extends('layouts.app')

@section('title', 'Alumni Dashboard')

@push('styles')
<style>
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
    
    .gradient-card {
        background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
    }
    
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
@endpush

@section('content')
<!-- Include Profile Reminder Modal -->
@if(isset($showProfileReminder) && $showProfileReminder)
    @include('components.profile-reminder-modal', ['showProfileReminder' => true])
@endif

<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
    <!-- Enhanced Welcome Section with Gradient -->
    <div class="mb-10 animate-fade-in-up">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-stu-green via-stu-green-light to-stu-green-dark p-8 text-white shadow-2xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="flex items-center space-x-4 mb-2">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-user-circle text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">
                            Welcome back, {{ $alumni->first_name }}! 👋
                        </h1>
                        <p class="text-blue-100 mt-1 text-lg">
                            Here's what's happening in your alumni network today
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Grid with Modern Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-10">
        <!-- Verification Status Card -->
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg hover-lift border border-gray-100 animate-fade-in-up" style="animation-delay: 0.1s">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100 rounded-full -mr-16 -mt-16 opacity-50 group-hover:opacity-75 transition-opacity"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-badge-check text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $alumni->verification_status === 'verified' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ ucfirst($alumni->verification_status) }}
                    </span>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Verification</p>
                <p class="text-2xl font-bold text-gray-900 capitalize">{{ $alumni->verification_status }}</p>
            </div>
        </div>

        <!-- My Businesses Card -->
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg hover-lift border border-gray-100 animate-fade-in-up" style="animation-delay: 0.2s">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-100 rounded-full -mr-16 -mt-16 opacity-50 group-hover:opacity-75 transition-opacity"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-briefcase text-white text-xl"></i>
                    </div>
                    <a href="{{ route('alumni.businesses.my-businesses') }}" class="text-xs text-stu-green hover:text-stu-green-dark font-medium">
                        View All →
                    </a>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">My Businesses</p>
                <p class="text-2xl font-bold text-gray-900">{{ $myBusinesses }}</p>
            </div>
        </div>

        <!-- Event Registrations Card -->
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg hover-lift border border-gray-100 animate-fade-in-up" style="animation-delay: 0.3s">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-100 rounded-full -mr-16 -mt-16 opacity-50 group-hover:opacity-75 transition-opacity"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                    <a href="{{ route('alumni.events.my-registrations') }}" class="text-xs text-stu-green hover:text-stu-green-dark font-medium">
                        View All →
                    </a>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">My Events</p>
                <p class="text-2xl font-bold text-gray-900">{{ $myEvents }}</p>
            </div>
        </div>

        <!-- Year of Completion Card -->
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg hover-lift border border-gray-100 animate-fade-in-up" style="animation-delay: 0.4s">
            <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-100 rounded-full -mr-16 -mt-16 opacity-50 group-hover:opacity-75 transition-opacity"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Graduation Year</p>
                <p class="text-2xl font-bold text-gray-900">{{ $alumni->year_of_completion }}</p>
            </div>
        </div>
    </div>

    <!-- Year Groups Section -->
    @if($yearGroups->isNotEmpty())
    <div class="mb-10 animate-fade-in-up" style="animation-delay: 0.4s">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-users mr-2"></i>
                    Your Year Groups
                </h2>
                <p class="text-purple-100 text-sm mt-1">Connect with alumni from your graduation cohort</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($yearGroups as $group)
                        <div class="border border-gray-200 rounded-xl p-4 hover:border-stu-green hover:shadow-md transition-all">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $group->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $group->start_year }} - {{ $group->end_year }}</p>
                                    @if($group->description)
                                        <p class="text-xs text-gray-500 mt-2">{{ $group->description }}</p>
                                    @endif
                                </div>
                                <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-users text-purple-600"></i>
                                </div>
                            </div>
                            
                            @if($group->hasSocialLinks())
                                <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100">
                                    @if($group->whatsapp_link)
                                        <a href="{{ $group->whatsapp_link }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors">
                                            <i class="fab fa-whatsapp mr-2"></i>
                                            Join WhatsApp
                                        </a>
                                    @endif
                                    
                                    @if($group->telegram_link)
                                        <a href="{{ $group->telegram_link }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
                                            <i class="fab fa-telegram mr-2"></i>
                                            Join Telegram
                                        </a>
                                    @endif
                                    
                                    @if($group->gekychat_link)
                                        <a href="{{ $group->gekychat_link }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center px-3 py-2 bg-purple-500 hover:bg-purple-600 text-white text-sm font-medium rounded-lg transition-colors">
                                            <i class="fas fa-comments mr-2"></i>
                                            Join GekyChat
                                        </a>
                                    @endif
                                </div>
                            @else
                                <p class="text-xs text-gray-400 italic mt-4 pt-4 border-t border-gray-100">
                                    Group links will be available soon
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Content Grid with Enhanced Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <!-- Recent Announcements Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover-lift animate-fade-in-up" style="animation-delay: 0.5s">
            <div class="bg-gradient-to-r from-stu-green to-stu-green-dark px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-bullhorn mr-2"></i>
                        Recent Announcements
                    </h2>
                    <a href="{{ route('alumni.announcements') }}" class="text-sm text-white hover:text-green-100 font-medium transition-colors">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentAnnouncements as $announcement)
                        <div class="group border-l-4 border-stu-green pl-4 py-3 rounded-r-lg hover:bg-green-50 transition-colors cursor-pointer" onclick="window.location='{{ route('alumni.announcements.show', $announcement->slug) }}'">
                            <h3 class="font-semibold text-gray-900 group-hover:text-stu-green transition-colors">{{ $announcement->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ Str::limit($announcement->excerpt ?? strip_tags($announcement->content), 100) }}</p>
                            <div class="flex items-center text-xs text-gray-500 mt-2">
                                <i class="fas fa-calendar mr-1"></i>
                                <span>{{ $announcement->published_at->format('M j, Y') }}</span>
                                @if($announcement->is_pinned)
                                    <span class="ml-3 px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">
                                        <i class="fas fa-thumbtack mr-1"></i>Pinned
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-bullhorn text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500">No announcements yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Upcoming Events Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover-lift animate-fade-in-up" style="animation-delay: 0.6s">
            <div class="bg-gradient-to-r from-stu-green to-stu-green-dark px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Upcoming Events
                    </h2>
                    <a href="{{ route('alumni.events') }}" class="text-sm text-white hover:text-purple-100 font-medium transition-colors">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($upcomingEvents as $event)
                        <div class="border border-gray-200 rounded-xl p-4 hover:border-stu-green hover:shadow-md transition-all group">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 group-hover:text-stu-green transition-colors">{{ $event->title }}</h3>
                                    <div class="flex items-center text-sm text-gray-600 mt-2 space-x-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar text-stu-green mr-2"></i>
                                            <span>{{ $event->event_date->format('M j, Y') }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-clock text-stu-green mr-2"></i>
                                            <span>{{ $event->event_date->format('g:i A') }}</span>
                                        </div>
                                    </div>
                                    @if($event->venue)
                                        <div class="flex items-center text-sm text-gray-600 mt-2">
                                            <i class="fas fa-map-marker-alt text-stu-green mr-2"></i>
                                            <span>{{ $event->venue }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('alumni.events') }}?event={{ $event->id }}" 
                                   class="inline-flex items-center text-sm font-medium text-stu-green hover:text-stu-green-dark transition-colors">
                                    View Details <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500">No upcoming events.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Quick Actions -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 animate-fade-in-up" style="animation-delay: 0.7s">
        <div class="flex items-center mb-6">
            <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
            <h2 class="text-2xl font-bold text-gray-900">Quick Actions</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <a href="{{ route('alumni.profile') }}" 
               class="group flex flex-col items-center p-6 rounded-xl border-2 border-gray-200 hover:border-stu-green hover:bg-gradient-to-br hover:from-green-50 hover:to-green-100 transition-all duration-300 hover-lift">
                <div class="w-16 h-16 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-2xl flex items-center justify-center mb-3 shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-stu-green transition-colors">Update Profile</span>
            </a>

            <a href="{{ route('alumni.businesses.create') }}" 
               class="group flex flex-col items-center p-6 rounded-xl border-2 border-gray-200 hover:border-stu-green hover:bg-gradient-to-br hover:from-green-50 hover:to-green-100 transition-all duration-300 hover-lift">
                <div class="w-16 h-16 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-2xl flex items-center justify-center mb-3 shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-briefcase text-white text-2xl"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-stu-green transition-colors">Add Business</span>
            </a>

            <a href="{{ route('alumni.events') }}" 
               class="group flex flex-col items-center p-6 rounded-xl border-2 border-gray-200 hover:border-stu-green hover:bg-gradient-to-br hover:from-green-50 hover:to-green-100 transition-all duration-300 hover-lift">
                <div class="w-16 h-16 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-2xl flex items-center justify-center mb-3 shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-stu-green transition-colors">Browse Events</span>
            </a>

            <a href="{{ route('businesses.public.index') }}" 
               class="group flex flex-col items-center p-6 rounded-xl border-2 border-gray-200 hover:border-stu-red hover:bg-gradient-to-br hover:from-red-50 hover:to-red-100 transition-all duration-300 hover-lift">
                <div class="w-16 h-16 bg-gradient-to-br from-stu-red to-stu-red-light rounded-2xl flex items-center justify-center mb-3 shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-search text-white text-2xl"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-stu-red transition-colors">Find Alumni</span>
            </a>
        </div>
    </div>
</div>
@endsection
