@extends('layouts.admin')

@section('title', 'Business: ' . $business->name)

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <a href="{{ route('admin.reports.businesses') }}" class="text-sm text-gray-600 hover:text-gray-900 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i>Back to Business Report
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $business->name }}</h1>
            <p class="text-gray-600 mt-1">{{ $business->industry }}</p>
        </div>
        @if($business->status === 'pending')
            <form action="{{ route('admin.reports.businesses.approve', $business) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check mr-2"></i>Approve listing
                </button>
            </form>
        @endif
    </div>

    <div class="card p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium {{ $business->status === 'active' ? 'bg-green-100 text-green-800' : ($business->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                {{ ucfirst($business->status) }}
            </span>
            @if($business->is_verified)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Verified</span>
            @endif
        </div>
        <p class="text-gray-700">{{ $business->description }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact</h2>
            <ul class="space-y-2 text-sm">
                @if($business->email)<li><span class="text-gray-500">Email:</span> {{ $business->email }}</li>@endif
                @if($business->phone)<li><span class="text-gray-500">Phone:</span> {{ $business->phone }}</li>@endif
                @if($business->website)<li><span class="text-gray-500">Website:</span> <a href="{{ $business->website }}" target="_blank" class="text-blue-600">{{ $business->website }}</a></li>@endif
                @if($business->address)<li><span class="text-gray-500">Address:</span> {{ $business->address }}</li>@endif
                @if($business->city || $business->country)<li><span class="text-gray-500">Location:</span> {{ trim(($business->city ?? '') . ', ' . ($business->country ?? ''), ', ') }}</li>@endif
            </ul>
        </div>
        <div class="card p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Owner</h2>
            @if($business->alumni)
                <p class="font-medium text-gray-900">{{ $business->alumni->full_name }}</p>
                <p class="text-sm text-gray-500">{{ $business->alumni->email }}</p>
                <a href="{{ route('admin.alumni.show', $business->alumni) }}" class="text-sm text-blue-600 hover:text-blue-800 mt-2 inline-block">View alumni profile</a>
            @else
                <p class="text-gray-500">No owner linked</p>
            @endif
        </div>
    </div>

    @if($business->status === 'active' && $business->is_verified)
        <div class="mt-6">
            <a href="{{ route('businesses.public.show', $business->slug) }}" target="_blank" class="btn-secondary">
                <i class="fas fa-external-link-alt mr-2"></i>View on public directory
            </a>
        </div>
    @endif
</div>
@endsection
