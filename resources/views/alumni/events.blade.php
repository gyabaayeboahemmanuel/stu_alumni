@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Upcoming Events</h1>
        <p class="text-gray-600 mt-2">Discover and register for upcoming alumni events</p>
    </div>

    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
        <div class="card p-6 hover:shadow-lg transition duration-200">
            <!-- Event Header -->
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                <div class="flex items-center text-sm text-gray-600 mt-2">
                    <i class="fas fa-calendar mr-2"></i>
                    {{ $event->event_date->format('M j, Y \\a\\t g:i A') }}
                </div>
                @if($event->venue)
                <div class="flex items-center text-sm text-gray-600 mt-1">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    {{ $event->venue }}
                </div>
                @elseif($event->online_link)
                <div class="flex items-center text-sm text-gray-600 mt-1">
                    <i class="fas fa-video mr-2"></i>
                    Online Event
                </div>
                @endif
            </div>

            <!-- Event Description -->
            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($event->description, 120) }}</p>

            <!-- Event Details -->
            <div class="space-y-2 text-sm text-gray-600 mb-4">
                @if($event->max_attendees)
                <div class="flex justify-between">
                    <span>Available Spaces:</span>
                    <span class="font-medium">{{ $event->available_spaces }} / {{ $event->max_attendees }}</span>
                </div>
                @endif
                
                @if($event->price > 0)
                <div class="flex justify-between">
                    <span>Price:</span>
                    <span class="font-medium">{{ $event->currency }} {{ number_format($event->price, 2) }}</span>
                </div>
                @else
                <div class="flex justify-between">
                    <span>Price:</span>
                    <span class="font-medium text-green-600">Free</span>
                </div>
                @endif

                @if($event->registration_deadline)
                <div class="flex justify-between">
                    <span>Registration Deadline:</span>
                    <span class="font-medium">{{ $event->registration_deadline->format('M j, Y') }}</span>
                </div>
                @endif
            </div>

            <!-- Registration Status -->
            @if(isset($myRegistrations[$event->id]))
                @php $registration = $myRegistrations[$event->id] @endphp
                <div class="mb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium 
                            {{ $registration->status === 'confirmed' ? 'text-green-600' : 
                               ($registration->status === 'pending' ? 'text-yellow-600' : 'text-gray-600') }}">
                            {{ ucfirst($registration->status) }}
                        </span>
                        @if($registration->status === 'confirmed')
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                            Registered
                        </span>
                        @elseif($registration->status === 'pending')
                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
                            Pending Approval
                        </span>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex space-x-2">
                @if(isset($myRegistrations[$event->id]))
                    <button class="flex-1 btn-secondary text-sm py-2" disabled>
                        Already Registered
                    </button>
                @elseif($event->isRegistrationOpen())
                    <form action="{{ route('alumni.events.register', $event) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full btn-primary text-sm py-2">
                            Register Now
                        </button>
                    </form>
                @else
                    <button class="flex-1 btn-secondary text-sm py-2" disabled>
                        Registration Closed
                    </button>
                @endif
                
                <a href="{{ route('alumni.events') }}?event={{ $event->id }}" 
                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition duration-200">
                    Details
                </a>
            </div>
        </div>
        @empty
        <div class="md:col-span-3 text-center py-12">
            <i class="fas fa-calendar-times text-gray-300 text-4xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No upcoming events</h3>
            <p class="text-gray-600">Check back later for new events and activities.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($events->hasPages())
    <div class="mt-8">
        {{ $events->links() }}
    </div>
    @endif

    <!-- My Registrations Section -->
    @if($myRegistrations->count() > 0)
    <div class="mt-12">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">My Event Registrations</h2>
        <div class="card p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($myRegistrations as $registration)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $registration->event->title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $registration->event->event_date->format('M j, Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $registration->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                       ($registration->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($registration->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $registration->registration_date->format('M j, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
