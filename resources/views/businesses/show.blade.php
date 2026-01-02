@extends('layouts.app')

@section('title', $business->name)

@section('content')
<div class="max-w-5xl mx-auto py-8 sm:px-6 lg:px-8">
    <!-- Enhanced Business Header -->
    <div class="mb-8 animate-fade-in-up">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-stu-green via-stu-green-light to-stu-green-dark p-8 text-white shadow-2xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-6">
                        @if($business->logo_path)
                        <div class="relative">
                            <div class="absolute -inset-2 bg-white rounded-2xl opacity-20 blur"></div>
                            <img src="{{ asset('storage/' . $business->logo_path) }}" 
                                 alt="{{ $business->name }}" 
                                 class="relative w-24 h-24 rounded-2xl object-cover border-4 border-white shadow-xl">
                        </div>
                        @else
                        <div class="w-24 h-24 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm border-4 border-white border-opacity-30">
                            <i class="fas fa-building text-white text-4xl"></i>
                        </div>
                        @endif
                        
                        <div>
                            <h1 class="text-3xl font-bold mb-2">{{ $business->name }}</h1>
                            <p class="text-green-100 text-lg mb-4">{{ $business->industry }}</p>
                            
                            <!-- Status Badges -->
                            <div class="flex flex-wrap gap-2">
                                @if($business->is_verified)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white bg-opacity-20 backdrop-blur-sm text-white border-2 border-white border-opacity-30">
                                    <i class="fas fa-check-circle mr-2"></i>Verified
                                </span>
                                @endif
                                
                                @if($business->is_featured)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-stu-red to-stu-red-light text-white border-2 border-white border-opacity-30">
                                    <i class="fas fa-star mr-2"></i>Featured
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Back Button -->
                    <a href="{{ route('businesses.public.index') }}" class="bg-white text-stu-green px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Directory
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="card p-6 hover-lift animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="flex items-center mb-4">
                    <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                    <h2 class="text-xl font-bold text-gray-900">About</h2>
                </div>
                <p class="text-gray-700 leading-relaxed">{{ $business->description }}</p>
            </div>

            <!-- Contact Information -->
            <div class="card p-6 hover-lift animate-fade-in-up" style="animation-delay: 0.2s">
                <div class="flex items-center mb-6">
                    <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                    <h2 class="text-xl font-bold text-gray-900">Contact Information</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($business->email)
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-envelope text-white"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <a href="mailto:{{ $business->email }}" class="text-stu-green hover:text-stu-green-dark font-medium">
                                {{ $business->email }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($business->phone)
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-phone text-white"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Phone</p>
                            <a href="tel:{{ $business->phone }}" class="text-stu-green hover:text-stu-green-dark font-medium">
                                {{ $business->phone }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($business->website)
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-globe text-white"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Website</p>
                            <a href="{{ $business->website }}" target="_blank" class="text-stu-green hover:text-stu-green-dark font-medium">
                                Visit Website <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="card p-6 hover-lift animate-fade-in-up" style="animation-delay: 0.3s">
                <div class="flex items-center mb-6">
                    <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                    <h2 class="text-xl font-bold text-gray-900">Location</h2>
                </div>
                <div class="space-y-4">
                    @if($business->address)
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Address</p>
                            <p class="text-gray-900 font-medium">{{ $business->address }}</p>
                        </div>
                    </div>
                    @endif

                    @if($business->city && $business->country)
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-city text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Location</p>
                            <p class="text-gray-900 font-medium">{{ $business->city }}, {{ $business->country }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Owner Information -->
            <div class="card p-6 mt-6 hover-lift animate-fade-in-up" style="animation-delay: 0.4s">
                <div class="flex items-center mb-6">
                    <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
                    <h2 class="text-xl font-bold text-gray-900">Owner</h2>
                </div>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Alumni Member</p>
                        <p class="text-gray-900 font-semibold">{{ $business->alumni->full_name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
