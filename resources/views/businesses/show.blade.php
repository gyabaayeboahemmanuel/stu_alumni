@extends('layouts.app')

@section('title', $business->name)

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Business Header -->
    <div class="card p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex items-start space-x-6">
                @if($business->logo_path)
                <img src="{{ asset('storage/' . $business->logo_path) }}" 
                     alt="{{ $business->name }}" 
                     class="w-24 h-24 rounded-lg object-cover">
                @else
                <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-gray-400 text-3xl"></i>
                </div>
                @endif
                
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $business->name }}</h1>
                    <p class="text-lg text-gray-600 mt-1">{{ $business->industry }}</p>
                    
                    <!-- Status Badges -->
                    <div class="mt-3 flex flex-wrap gap-2">
                        @if($business->is_verified)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Verified
                        </span>
                        @endif
                        
                        @if($business->is_featured)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-star mr-1"></i>Featured
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Back Button -->
            <a href="{{ route('businesses.public.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Directory
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Business Description -->
            <div class="card p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">About</h2>
                <div class="prose max-w-none text-gray-700">
                    {{ $business->description }}
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
                <div class="space-y-4">
                    @if($business->website)
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-globe text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Website</p>
                            <a href="{{ $business->website }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-500 text-sm">
                                {{ $business->website }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($business->email)
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-envelope text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Email</p>
                            <a href="mailto:{{ $business->email }}" 
                               class="text-blue-600 hover:text-blue-500 text-sm">
                                {{ $business->email }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($business->phone)
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-phone text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Phone</p>
                            <a href="tel:{{ $business->phone }}" 
                               class="text-blue-600 hover:text-blue-500 text-sm">
                                {{ $business->phone }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($business->address || $business->city || $business->country)
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3 mt-1">
                            <i class="fas fa-map-marker-alt text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Location</p>
                            <p class="text-sm text-gray-600">
                                @if($business->address)
                                    {{ $business->address }}<br>
                                @endif
                                @if($business->city && $business->country)
                                    {{ $business->city }}, {{ $business->country }}
                                @elseif($business->city)
                                    {{ $business->city }}
                                @elseif($business->country)
                                    {{ $business->country }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Business Owner -->
            <div class="card p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Alumni Owner</h2>
                <div class="flex items-center space-x-3">
                    <img src="{{ $business->alumni->profile_photo_path ? asset('storage/' . $business->alumni->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($business->alumni->full_name) . '&color=FFFFFF&background=1E40AF' }}" 
                         alt="{{ $business->alumni->full_name }}" 
                         class="w-12 h-12 rounded-full">
                    <div>
                        <p class="font-medium text-gray-900">{{ $business->alumni->full_name }}</p>
                        <p class="text-sm text-gray-600">{{ $business->alumni->programme }}</p>
                        <p class="text-xs text-gray-500">Class of {{ $business->alumni->year_of_completion }}</p>
                    </div>
                </div>
            </div>

            <!-- Business Stats -->
            <div class="card p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Business Information</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Industry</span>
                        <span class="font-medium text-gray-900">{{ $business->industry }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Listed</span>
                        <span class="font-medium text-gray-900">{{ $business->created_at->format('M j, Y') }}</span>
                    </div>
                    @if($business->verified_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Verified</span>
                        <span class="font-medium text-gray-900">{{ $business->verified_at->format('M j, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Report Button -->
            @auth
            <div class="card p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Found an Issue?</h2>
                <p class="text-sm text-gray-600 mb-4">If this business listing contains incorrect information or violates our guidelines, please report it.</p>
                <button class="w-full btn-secondary text-sm">
                    <i class="fas fa-flag mr-2"></i>Report Business
                </button>
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection
