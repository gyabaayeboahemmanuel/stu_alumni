@extends('layouts.app')

@section('title', 'My Event Registrations')

@section('content')
<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
    <!-- Enhanced Header -->
    <div class="mb-10 animate-fade-in-up">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-stu-green via-stu-green-light to-stu-green-dark p-8 text-white shadow-2xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-calendar-check text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">My Event Registrations</h1>
                        <p class="text-green-100 mt-1 text-lg">View and manage your event registrations</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registrations Table -->
    <div class="card p-6 animate-fade-in-up" style="animation-delay: 0.1s">
        @if($registrations->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-stu-green to-stu-green-dark">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Event</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Venue</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Registration Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($registrations as $registration)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $registration->event->title }}</div>
                            <div class="text-sm text-gray-500 mt-1">{{ Str::limit($registration->event->description, 80) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-stu-green mr-2"></i>
                                {{ $registration->event->event_date->format('M j, Y') }}
                            </div>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-clock text-stu-green mr-2"></i>
                                {{ $registration->event->event_date->format('g:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($registration->event->venue)
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt text-stu-green mr-2"></i>
                                    {{ $registration->event->venue }}
                                </div>
                            @elseif($registration->event->online_link)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-stu-green to-stu-green-dark text-white">
                                    <i class="fas fa-video mr-1"></i>Online Event
                                </span>
                            @else
                                <span class="text-gray-400">TBA</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                {{ $registration->status === 'confirmed' ? 'bg-green-100 text-green-800 border-2 border-green-300' : 
                                   ($registration->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-2 border-yellow-300' : 
                                   ($registration->status === 'cancelled' ? 'bg-red-100 text-red-800 border-2 border-red-300' : 
                                   'bg-gray-100 text-gray-800 border-2 border-gray-300')) }}">
                                <i class="fas {{ $registration->status === 'confirmed' ? 'fa-check-circle' : ($registration->status === 'pending' ? 'fa-clock' : 'fa-times-circle') }} mr-1"></i>
                                {{ ucfirst($registration->status) }}
                            </span>
                            @if($registration->attended)
                            <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-stu-green to-stu-green-dark text-white">
                                <i class="fas fa-check mr-1"></i>Attended
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $registration->registration_date->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <a href="{{ route('alumni.events') }}?event={{ $registration->event->id }}" 
                               class="inline-flex items-center px-3 py-1 rounded-lg text-stu-green hover:bg-stu-green hover:text-white transition-all duration-200 mr-2">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                            @if($registration->status === 'confirmed' && $registration->event->event_date->isFuture())
                            <form action="#" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1 rounded-lg text-stu-red hover:bg-stu-red hover:text-white transition-all duration-200" 
                                        onclick="return confirm('Are you sure you want to cancel this registration?')">
                                    <i class="fas fa-times mr-1"></i>Cancel
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($registrations->hasPages())
        <div class="mt-6 flex justify-center">
            <div class="bg-white rounded-xl shadow-lg p-4">
                {{ $registrations->links() }}
            </div>
        </div>
        @endif

        @else
        <div class="text-center py-16 animate-fade-in-up">
            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-calendar-times text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No event registrations</h3>
            <p class="text-gray-600 text-lg mb-6">You haven't registered for any events yet.</p>
            <a href="{{ route('alumni.events') }}" class="btn-primary inline-flex items-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                <i class="fas fa-calendar-alt mr-2"></i>Browse Events
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
