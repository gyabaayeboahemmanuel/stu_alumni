@extends('layouts.admin')

@section('title', 'Event Details')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h1>
            <p class="text-gray-600 mt-2">Event details and registrations</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.events.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <a href="{{ route('admin.events.edit', $event) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Event Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Event Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Description</label>
                        <div class="mt-1 text-gray-900 prose max-w-none">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Event Type</label>
                            <p class="mt-1 text-gray-900 capitalize">{{ $event->event_type }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $event->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $event->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date & Time -->
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Date & Time</h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Start Date</label>
                        <p class="mt-1 text-gray-900">{{ $event->event_date->format('F j, Y g:i A') }}</p>
                    </div>
                    @if($event->event_end_date)
                    <div>
                        <label class="text-sm font-medium text-gray-500">End Date</label>
                        <p class="mt-1 text-gray-900">{{ $event->event_end_date->format('F j, Y g:i A') }}</p>
                    </div>
                    @endif
                    @if($event->registration_deadline)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Registration Deadline</label>
                        <p class="mt-1 text-gray-900">{{ $event->registration_deadline->format('F j, Y g:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Location -->
            @if($event->venue || $event->online_link)
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Location</h2>
                <div class="space-y-3">
                    @if($event->venue)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Venue</label>
                        <p class="mt-1 text-gray-900">{{ $event->venue }}</p>
                    </div>
                    @endif
                    @if($event->online_link)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Online Link</label>
                        <p class="mt-1">
                            <a href="{{ $event->online_link }}" target="_blank" class="text-stu-green hover:text-stu-green-dark">
                                {{ $event->online_link }}
                            </a>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Registrations</span>
                        <span class="font-semibold text-gray-900">{{ $event->registrations_count ?? $event->registrations->count() }}</span>
                    </div>
                    @if($event->max_attendees)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Max Attendees</span>
                        <span class="font-semibold text-gray-900">{{ $event->max_attendees }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Available Spaces</span>
                        <span class="font-semibold text-gray-900">{{ $event->max_attendees - ($event->registrations_count ?? $event->registrations->count()) }}</span>
                    </div>
                    @endif
                    @if($event->price > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Price</span>
                        <span class="font-semibold text-gray-900">GHS {{ number_format($event->price, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="space-y-2">
                    <form action="{{ route('admin.events.toggle-publish', $event) }}" method="POST" class="inline w-full">
                        @csrf
                        <button type="submit" class="btn-secondary w-full text-left">
                            <i class="fas {{ $event->is_published ? 'fa-eye-slash' : 'fa-eye' }} mr-2"></i>
                            {{ $event->is_published ? 'Unpublish' : 'Publish' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.events.toggle-feature', $event) }}" method="POST" class="inline w-full">
                        @csrf
                        <button type="submit" class="btn-secondary w-full text-left">
                            <i class="fas fa-star mr-2"></i>
                            {{ $event->is_featured ? 'Unfeature' : 'Feature' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.events.export-registrations', $event) }}" class="btn-secondary w-full text-left block">
                        <i class="fas fa-download mr-2"></i>Export Registrations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Registrations -->
    <div class="mt-6 card">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Registrations ({{ $registrations->total() }})</h2>
        
        @if($registrations->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alumni</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registered</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attendance</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($registrations as $registration)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $registration->alumni->first_name }} {{ $registration->alumni->last_name }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $registration->alumni->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.events.registrations.status', $registration) }}" method="POST" class="inline">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded">
                                    <option value="pending" {{ $registration->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $registration->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ $registration->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="waitlisted" {{ $registration->status == 'waitlisted' ? 'selected' : '' }}>Waitlisted</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $registration->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($registration->attended)
                                <span class="text-green-600"><i class="fas fa-check-circle"></i> Attended</span>
                            @else
                                <form action="{{ route('admin.events.registrations.attendance', $registration) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-900">Mark Attended</button>
                                </form>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.alumni.show', $registration->alumni) }}" class="text-blue-600 hover:text-blue-900">
                                View Profile
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($registrations->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $registrations->links() }}
        </div>
        @endif
        @else
        <p class="text-center text-gray-500 py-8">No registrations yet.</p>
        @endif
    </div>
</div>
@endsection
