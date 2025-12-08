@extends('layouts.app')

@section('title', 'Alumni Dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">
            Welcome back, {{ $alumni->first_name }}!
        </h1>
        <p class="text-gray-600 mt-2">
            Here's what's happening in your alumni network today.
        </p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Verification Status -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-badge-check text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Verification Status</p>
                    <p class="text-lg font-semibold text-gray-900 capitalize">
                        {{ $alumni->verification_status }}
                    </p>
                </div>
            </div>
        </div>

        <!-- My Businesses -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-briefcase text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">My Businesses</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $myBusinesses }}</p>
                </div>
            </div>
        </div>

        <!-- Event Registrations -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">My Events</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $myEvents }}</p>
                </div>
            </div>
        </div>

        <!-- Year of Completion -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Graduation Year</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $alumni->year_of_completion }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Announcements -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Announcements</h2>
                <a href="{{ route('alumni.announcements') }}" class="text-sm text-blue-600 hover:text-blue-500">
                    View All
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentAnnouncements as $announcement)
                    <div class="border-l-4 border-blue-500 pl-4 py-1">
                        <h3 class="font-medium text-gray-900">{{ $announcement->title }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement->excerpt, 100) }}</p>
                        <div class="flex items-center text-xs text-gray-500 mt-2">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $announcement->published_at->format('M j, Y') }}
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No announcements yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Upcoming Events</h2>
                <a href="{{ route('alumni.events') }}" class="text-sm text-blue-600 hover:text-blue-500">
                    View All
                </a>
            </div>
            <div class="space-y-4">
                @forelse($upcomingEvents as $event)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-medium text-gray-900">{{ $event->title }}</h3>
                        <div class="flex items-center text-sm text-gray-600 mt-2">
                            <i class="fas fa-calendar mr-2"></i>
                            {{ $event->event_date->format('M j, Y \\a\\t g:i A') }}
                        </div>
                        @if($event->venue)
                            <div class="flex items-center text-sm text-gray-600 mt-1">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ $event->venue }}
                            </div>
                        @endif
                        <div class="mt-3">
                            <a href="{{ route('alumni.events') }}" class="text-sm btn-primary py-1 px-3">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No upcoming events.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('alumni.profile') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-2">
                    <i class="fas fa-user text-blue-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Update Profile</span>
            </a>

            <a href="{{ route('alumni.businesses.create') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-2">
                    <i class="fas fa-briefcase text-green-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Add Business</span>
            </a>

            <a href="{{ route('alumni.events') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-2">
                    <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Browse Events</span>
            </a>

            <a href="{{ route('businesses.public.index') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-2">
                    <i class="fas fa-search text-orange-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Find Alumni</span>
            </a>
        </div>
    </div>
</div>
@endsection
