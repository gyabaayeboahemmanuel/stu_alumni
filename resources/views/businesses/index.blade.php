@extends('layouts.app')

@section('title', 'Alumni Business Directory')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Alumni Business Directory</h1>
        <p class="text-gray-600 mt-2">Discover and support businesses owned by STU alumni</p>
    </div>

    <!-- Featured Businesses -->
    @if($featuredBusinesses->count() > 0)
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Featured Businesses</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredBusinesses as $business)
            <div class="card p-6 border-2 border-yellow-300">
                <div class="flex items-start mb-4">
                    @if($business->logo_path)
                    <img src="{{ asset('storage/' . $business->logo_path) }}" alt="{{ $business->name }}" 
                         class="w-16 h-16 rounded-lg object-cover mr-4">
                    @else
                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-building text-gray-400 text-xl"></i>
                    </div>
                    @endif
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $business->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $business->industry }}</p>
                        <span class="inline-block mt-1 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">
                            Featured
                        </span>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($business->description, 100) }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">{{ $business->city }}, {{ $business->country }}</span>
                    <a href="{{ route('businesses.public.show', $business->slug) }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                        View Details â†’
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Search and Filters -->
    <div class="card p-6 mb-6">
        <form action="{{ route('businesses.public.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="form-label">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       class="form-input" placeholder="Business name or industry...">
            </div>
            <div>
                <label for="industry" class="form-label">Industry</label>
                <select id="industry" name="industry" class="form-input">
                    <option value="">All Industries</option>
                    <option value="Technology" {{ request('industry') == 'Technology' ? 'selected' : '' }}>Technology</option>
                    <option value="Healthcare" {{ request('industry') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                    <option value="Education" {{ request('industry') == 'Education' ? 'selected' : '' }}>Education</option>
                    <option value="Finance" {{ request('industry') == 'Finance' ? 'selected' : '' }}>Finance</option>
                    <option value="Retail" {{ request('industry') == 'Retail' ? 'selected' : '' }}>Retail</option>
                    <option value="Manufacturing" {{ request('industry') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                    <option value="Services" {{ request('industry') == 'Services' ? 'selected' : '' }}>Services</option>
                </select>
            </div>
            <div>
                <label for="country" class="form-label">Country</label>
                <input type="text" id="country" name="country" value="{{ request('country') }}" 
                       class="form-input" placeholder="Country...">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>
    </div>

    <!-- Business List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($businesses as $business)
        <div class="card p-6 hover:shadow-lg transition duration-200">
            <div class="flex items-start mb-4">
                @if($business->logo_path)
                <img src="{{ asset('storage/' . $business->logo_path) }}" alt="{{ $business->name }}" 
                     class="w-12 h-12 rounded-lg object-cover mr-4">
                @else
                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-building text-gray-400"></i>
                </div>
                @endif
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $business->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $business->industry }}</p>
                </div>
            </div>
            
            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($business->description, 120) }}</p>
            
            <div class="space-y-2 text-sm text-gray-600 mb-4">
                @if($business->city && $business->country)
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 w-4"></i>
                    <span>{{ $business->city }}, {{ $business->country }}</span>
                </div>
                @endif
                @if($business->website)
                <div class="flex items-center">
                    <i class="fas fa-globe mr-2 w-4"></i>
                    <a href="{{ $business->website }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                        Visit Website
                    </a>
                </div>
                @endif
            </div>

            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">
                    By {{ $business->alumni->first_name }} {{ $business->alumni->last_name }}
                </span>
                <a href="{{ route('businesses.public.show', $business->slug) }}" 
                   class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                    View Details
                </a>
            </div>
        </div>
        @empty
        <div class="md:col-span-3 text-center py-12">
            <i class="fas fa-building text-gray-300 text-4xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No businesses found</h3>
            <p class="text-gray-600">Try adjusting your search criteria or check back later.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($businesses->hasPages())
    <div class="mt-8">
        {{ $businesses->links() }}
    </div>
    @endif
</div>
@endsection
