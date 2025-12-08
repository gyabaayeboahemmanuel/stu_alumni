@extends('layouts.app')

@section('title', 'My Event Registrations')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">My Event Registrations</h1>
        <p class="text-gray-600 mt-2">View and manage your event registrations</p>
    </div>

    <!-- Registrations Table -->
    <div class="card p-6">
        @if($registrations->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($registrations as $registration)
                    <tr>
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $registration->event->title }}</div>
                            <div class="text-sm text-gray-500 mt-1">{{ Str::limit($registration->event->description, 80) }}</div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            {{ $registration->event->event_date->format('M j, Y \\a\\t g:i A') }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            @if($registration->event->venue)
                                {{ $registration->event->venue }}
                            @elseif($registration->event->online_link)
                                <span class="text-blue-600">Online Event</span>
                            @else
                                <span class="text-gray-400">TBA</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $registration->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                   ($registration->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($registration->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                   'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($registration->status) }}
                            </span>
                            @if($registration->attended)
                            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Attended
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            {{ $registration->registration_date->format('M j, Y') }}
                        </td>
                        <td class="px-4 py-4 text-sm font-medium">
                            <a href="{{ route('alumni.events') }}?event={{ $registration->event->id }}" 
                               class="text-blue-600 hover:text-blue-900 mr-3">View Event</a>
                            @if($registration->status === 'confirmed' && $registration->event->event_date->isFuture())
                            <form action="#" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                        onclick="return confirm('Are you sure you want to cancel this registration?')">
                                    Cancel
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
        <div class="mt-6">
            {{ $registrations->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-12">
            <i class="fas fa-calendar-times text-gray-300 text-4xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No event registrations</h3>
            <p class="text-gray-600 mb-4">You haven't registered for any events yet.</p>
            <a href="{{ route('alumni.events') }}" class="btn-primary">
                Browse Events
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
