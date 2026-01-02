@extends('layouts.app')

@section('title', 'Events')

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
                        <i class="fas fa-calendar-alt text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Upcoming Events</h1>
                        <p class="text-green-100 mt-1 text-lg">Discover and register for upcoming alumni events</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        @forelse($events as $index => $event)
        <div class="card p-6 hover-lift animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
            <!-- Event Header -->
            <div class="mb-4">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-bold text-gray-900 flex-1">{{ $event->title }}</h3>
                    @if($event->is_featured)
                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-stu-red to-stu-red-light text-white">
                        <i class="fas fa-star mr-1"></i>Featured
                    </span>
                    @endif
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="w-6 h-6 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-calendar text-white text-xs"></i>
                        </div>
                        <span class="font-medium">{{ $event->event_date->format('M j, Y \\a\\t g:i A') }}</span>
                    </div>
                    @if($event->venue)
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="w-6 h-6 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-map-marker-alt text-white text-xs"></i>
                        </div>
                        <span>{{ $event->venue }}</span>
                    </div>
                    @elseif($event->online_link)
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="w-6 h-6 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-video text-white text-xs"></i>
                        </div>
                        <span>Online Event</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Event Description -->
            <p class="text-sm text-gray-700 mb-4 leading-relaxed">{{ Str::limit($event->description, 120) }}</p>

            <!-- Event Details -->
            <div class="bg-gray-50 rounded-xl p-4 mb-4 space-y-2 text-sm">
                @if($event->max_attendees)
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Available Spaces:</span>
                    <span class="font-bold text-gray-900">{{ $event->available_spaces }} / {{ $event->max_attendees }}</span>
                </div>
                @endif
                
                @if($event->price > 0)
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Price:</span>
                    <span class="font-bold text-gray-900">{{ $event->currency }} {{ number_format($event->price, 2) }}</span>
                </div>
                @else
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Price:</span>
                    <span class="font-bold text-stu-green">Free</span>
                </div>
                @endif

                @if($event->registration_deadline)
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Deadline:</span>
                    <span class="font-medium text-gray-900">{{ $event->registration_deadline->format('M j, Y') }}</span>
                </div>
                @endif
            </div>

            <!-- Registration Status -->
            @if(isset($myRegistrations[$event->id]))
                @php $registration = $myRegistrations[$event->id] @endphp
                <div class="mb-4 p-3 rounded-xl {{ $registration->status === 'confirmed' ? 'bg-green-50 border-2 border-green-200' : 'bg-yellow-50 border-2 border-yellow-200' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold {{ $registration->status === 'confirmed' ? 'text-green-800' : 'text-yellow-800' }}">
                            <i class="fas {{ $registration->status === 'confirmed' ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                            {{ ucfirst($registration->status) }}
                        </span>
                        @if($registration->status === 'confirmed')
                        <span class="text-xs bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">
                            Registered
                        </span>
                        @elseif($registration->status === 'pending')
                        <span class="text-xs bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-semibold">
                            Pending Approval
                        </span>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex space-x-2">
                @if(isset($myRegistrations[$event->id]))
                    <button class="flex-1 btn-secondary text-sm py-2 rounded-xl" disabled>
                        <i class="fas fa-check mr-1"></i>Already Registered
                    </button>
                @elseif($event->isRegistrationOpen())
                    <form action="{{ route('alumni.events.register', $event) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full btn-primary text-sm py-2 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-user-plus mr-1"></i>Register Now
                        </button>
                    </form>
                @else
                    <button class="flex-1 btn-secondary text-sm py-2 rounded-xl" disabled>
                        <i class="fas fa-lock mr-1"></i>Registration Closed
                    </button>
                @endif
                
                <a href="{{ route('alumni.events') }}?event={{ $event->id }}" 
                   class="px-4 py-2 border-2 border-stu-green text-stu-green rounded-xl text-sm font-medium hover:bg-stu-green hover:text-white transition-all duration-200">
                    <i class="fas fa-info-circle"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="md:col-span-3 text-center py-16 animate-fade-in-up">
            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-calendar-times text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No upcoming events</h3>
            <p class="text-gray-600 text-lg">Check back later for new events and activities.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($events->hasPages())
    <div class="mt-10 flex justify-center">
        <div class="bg-white rounded-xl shadow-lg p-4">
            {{ $events->links() }}
        </div>
    </div>
    @endif

    <!-- My Registrations Section -->
    @if($myRegistrations->count() > 0)
    <div class="mt-12 animate-fade-in-up">
        <div class="mb-6">
            <div class="flex items-center">
                <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                <h2 class="text-2xl font-bold text-gray-900">My Event Registrations</h2>
            </div>
        </div>
        <div class="card p-6 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-stu-green to-stu-green-dark">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Event</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Registration Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($myRegistrations as $registration)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $registration->event->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $registration->event->event_date->format('M j, Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                    {{ $registration->status === 'confirmed' ? 'bg-green-100 text-green-800 border-2 border-green-300' : 
                                       ($registration->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-2 border-yellow-300' : 
                                       'bg-gray-100 text-gray-800 border-2 border-gray-300') }}">
                                    <i class="fas {{ $registration->status === 'confirmed' ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                    {{ ucfirst($registration->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $registration->registration_date->format('M j, Y') }}</td>
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
