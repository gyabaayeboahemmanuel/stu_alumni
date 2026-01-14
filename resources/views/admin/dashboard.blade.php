@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back, {{ Auth::user()->name }}! Here's your system overview.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Alumni -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Alumni</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_alumni'] }}</p>
                </div>
            </div>
        </div>

        <!-- Verified Alumni -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-badge-check text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Verified Alumni</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['verified_alumni'] }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Verification -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Verification</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_alumni'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Businesses -->
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-briefcase text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Business Listings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_businesses'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pending Alumni for Review -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Pending Verification</h2>
                <a href="{{ route('admin.alumni.index') }}?verification_status=pending" class="text-sm text-blue-600 hover:text-blue-500">
                    View All
                </a>
            </div>
            <div class="space-y-4">
                @forelse($pendingAlumni as $alumni)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $alumni->full_name }}</h3>
                            <p class="text-sm text-gray-600">{{ $alumni->programme }} â€¢ {{ $alumni->year_of_completion }}</p>
                            <p class="text-xs text-gray-500 mt-1">Registered: {{ $alumni->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.alumni.show', $alumni) }}" 
                               class="text-blue-600 hover:text-blue-900" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.alumni.verify', $alumni) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900" title="Verify">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No pending verifications.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Announcements</h2>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-500">
                    View All
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentAnnouncements as $announcement)
                    <div class="border-l-4 border-blue-500 pl-4 py-1">
                        <h3 class="font-medium text-gray-900">{{ $announcement->title }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement->excerpt, 100) }}</p>
                        <div class="flex items-center text-xs text-gray-500 mt-2">
                            <i class="fas fa-user mr-1"></i>
                            {{ $announcement->author->name }}
                            <i class="fas fa-calendar ml-3 mr-1"></i>
                            {{ $announcement->published_at->format('M j, Y') }}
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No announcements yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- In-Kind Donations Section -->
    <div class="mt-8 card p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">In-Kind Donations</h2>
                @if(isset($pendingDonationsCount) && $pendingDonationsCount > 0)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                        {{ $pendingDonationsCount }} Pending
                    </span>
                @endif
            </div>
            <a href="{{ route('admin.donations.index') }}" class="text-sm text-blue-600 hover:text-blue-500">
                View All
            </a>
        </div>
        <div class="space-y-4">
            @forelse($recentDonations ?? [] as $donation)
                <div class="border-l-4 border-green-500 pl-4 py-3 bg-gray-50 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="font-medium text-gray-900">
                                    @if($donation->alumni)
                                        {{ $donation->alumni->full_name }}
                                    @elseif($donation->user)
                                        {{ $donation->user->name }}
                                    @else
                                        Anonymous
                                    @endif
                                </h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    {{ $donation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($donation->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">
                                <strong>Items:</strong> {{ \Illuminate\Support\Str::limit($donation->items, 80) }}
                            </p>
                            <p class="text-sm text-gray-600 mb-1">
                                <strong>Location:</strong> {{ $donation->city }}, {{ $donation->country }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Contact:</strong> {{ $donation->contact }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $donation->created_at->format('M j, Y g:i A') }}
                            </p>
                        </div>
                        <a href="{{ route('admin.donations.show', $donation) }}" 
                           class="ml-4 text-blue-600 hover:text-blue-900" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No in-kind donations yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 card p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.alumni.index') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-2">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Manage Alumni</span>
            </a>

            <a href="#" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-2">
                    <i class="fas fa-bullhorn text-green-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Create Announcement</span>
            </a>

            <a href="#" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-2">
                    <i class="fas fa-calendar-plus text-purple-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Add Event</span>
            </a>

            <a href="#" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-2">
                    <i class="fas fa-chart-bar text-orange-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">View Reports</span>
            </a>
        </div>
    </div>
</div>
@endsection
