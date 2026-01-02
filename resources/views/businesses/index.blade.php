@extends('layouts.app')

@section('title', 'Alumni Business Directory')

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
                        <i class="fas fa-building text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Alumni Business Directory</h1>
                        <p class="text-green-100 mt-1 text-lg">Discover and support businesses owned by STU alumni</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Businesses -->
    @if($featuredBusinesses->count() > 0)
    <div class="mb-10 animate-fade-in-up" style="animation-delay: 0.1s">
        <div class="flex items-center mb-6">
            <div class="w-1 h-8 bg-gradient-to-b from-stu-red to-stu-red-light rounded-full mr-4"></div>
            <h2 class="text-2xl font-bold text-gray-900">Featured Businesses</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredBusinesses as $index => $business)
            <div class="card p-6 border-2 border-stu-red border-opacity-50 hover-lift animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
                <div class="flex items-start mb-4">
                    @if($business->logo_path)
                    <div class="relative">
                        <div class="absolute -inset-2 bg-gradient-to-br from-stu-red to-stu-red-light rounded-xl opacity-20 blur"></div>
                        <img src="{{ asset('storage/' . $business->logo_path) }}" alt="{{ $business->name }}" 
                             class="relative w-16 h-16 rounded-xl object-cover border-4 border-white shadow-lg">
                    </div>
                    @else
                    <div class="w-16 h-16 bg-gradient-to-br from-stu-red to-stu-red-light rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-building text-white text-xl"></i>
                    </div>
                    @endif
                    <div class="ml-4 flex-1">
                        <h3 class="font-bold text-gray-900 mb-1">{{ $business->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $business->industry }}</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-stu-red to-stu-red-light text-white">
                            <i class="fas fa-star mr-1"></i>Featured
                        </span>
                    </div>
                </div>
                <p class="text-sm text-gray-700 mb-4 leading-relaxed">{{ Str::limit($business->description, 100) }}</p>
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <span class="text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $business->city }}, {{ $business->country }}
                    </span>
                    <a href="{{ route('businesses.public.show', $business->slug) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-stu-green to-stu-green-dark text-white text-sm font-medium rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                        View Details <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Enhanced Search and Filters -->
    <div class="card p-6 mb-8 hover-lift animate-fade-in-up" style="animation-delay: 0.2s">
        <div class="flex items-center mb-6">
            <div class="w-1 h-8 bg-gradient-to-b from-stu-green to-stu-green-dark rounded-full mr-4"></div>
            <h3 class="text-xl font-bold text-gray-900">Search & Filter</h3>
        </div>
        <form action="{{ route('businesses.public.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="form-label">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           class="form-input pl-10 focus:ring-2 focus:ring-stu-green" 
                           placeholder="Business name or industry...">
                </div>
            </div>
            <div>
                <label for="industry" class="form-label">Industry</label>
                <select id="industry" name="industry" class="form-input focus:ring-2 focus:ring-stu-green">
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
                       class="form-input focus:ring-2 focus:ring-stu-green" 
                       placeholder="Country...">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full btn-primary rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>
    </div>

    <!-- Business List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($businesses as $index => $business)
        <div class="card p-6 hover-lift animate-fade-in-up" style="animation-delay: {{ $index * 0.05 }}s">
            <div class="flex items-start mb-4">
                @if($business->logo_path)
                <div class="relative">
                    <div class="absolute -inset-2 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-xl opacity-20 blur"></div>
                    <img src="{{ asset('storage/' . $business->logo_path) }}" alt="{{ $business->name }}" 
                         class="relative w-16 h-16 rounded-xl object-cover border-4 border-white shadow-lg">
                </div>
                @else
                <div class="w-16 h-16 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-building text-white text-xl"></i>
                </div>
                @endif
                <div class="ml-4 flex-1">
                    <h3 class="font-bold text-gray-900 mb-1">{{ $business->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $business->industry }}</p>
                </div>
            </div>
            
            <p class="text-sm text-gray-700 mb-4 leading-relaxed">{{ Str::limit($business->description, 120) }}</p>
            
            <div class="space-y-2 text-sm mb-4">
                @if($business->city && $business->country)
                <div class="flex items-center text-gray-600">
                    <div class="w-6 h-6 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-2">
                        <i class="fas fa-map-marker-alt text-white text-xs"></i>
                    </div>
                    <span>{{ $business->city }}, {{ $business->country }}</span>
                </div>
                @endif
                @if($business->website)
                <div class="flex items-center text-gray-600">
                    <div class="w-6 h-6 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-2">
                        <i class="fas fa-globe text-white text-xs"></i>
                    </div>
                    <a href="{{ $business->website }}" target="_blank" class="text-stu-green hover:text-stu-green-dark font-medium">
                        Visit Website
                    </a>
                </div>
                @endif
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <span class="text-xs text-gray-500">
                    By {{ $business->alumni->first_name }} {{ $business->alumni->last_name }}
                </span>
                <a href="{{ route('businesses.public.show', $business->slug) }}" 
                   class="inline-flex items-center px-3 py-1 rounded-lg text-stu-green hover:bg-stu-green hover:text-white transition-all duration-200 text-sm font-medium">
                    View <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="md:col-span-3 text-center py-16 animate-fade-in-up">
            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-building text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No businesses found</h3>
            <p class="text-gray-600 text-lg">Try adjusting your search criteria or check back later.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($businesses->hasPages())
    <div class="mt-10 flex justify-center">
        <div class="bg-white rounded-xl shadow-lg p-4">
            {{ $businesses->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
