@extends('layouts.app')

@section('title', 'My Businesses')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Businesses</h1>
                <p class="text-gray-600 mt-2">Manage your business listings in the alumni directory</p>
            </div>
            <a href="{{ route('alumni.businesses.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Add Business
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Business List -->
    <div class="grid grid-cols-1 gap-6">
        @forelse($businesses as $business)
        <div class="card p-6">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    @if($business->logo_path)
                    <img src="{{ asset('storage/' . $business->logo_path) }}" 
                         alt="{{ $business->name }}" 
                         class="w-16 h-16 rounded-lg object-cover">
                    @else
                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-gray-400 text-xl"></i>
                    </div>
                    @endif
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $business->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $business->industry }}</p>
                        
                        <!-- Status Badge -->
                        <div class="mt-2">
                            @if($business->status === 'active' && $business->is_verified)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Verified & Active
                            </span>
                            @elseif($business->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Pending Approval
                            </span>
                            @elseif($business->status === 'rejected')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>Rejected
                            </span>
                            @endif

                            @if($business->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 ml-2">
                                <i class="fas fa-star mr-1"></i>Featured
                            </span>
                            @endif
                        </div>

                        <!-- Business Details -->
                        <div class="mt-3 space-y-1 text-sm text-gray-600">
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
                                    {{ parse_url($business->website, PHP_URL_HOST) }}
                                </a>
                            </div>
                            @endif
                            
                            @if($business->phone)
                            <div class="flex items-center">
                                <i class="fas fa-phone mr-2 w-4"></i>
                                <span>{{ $business->phone }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    <a href="{{ route('alumni.businesses.edit', $business) }}" 
                       class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50"
                       title="Edit Business">
                        <i class="fas fa-edit"></i>
                    </a>
                    
                    <form action="{{ route('alumni.businesses.destroy', $business) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this business?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50"
                                title="Delete Business">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Description -->
            @if($business->description)
            <div class="mt-4">
                <p class="text-gray-700">{{ $business->description }}</p>
            </div>
            @endif

            <!-- Created Date -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    Listed {{ $business->created_at->diffForHumans() }}
                    @if($business->verified_at)
                    â€¢ Verified {{ $business->verified_at->diffForHumans() }}
                    @endif
                </p>
            </div>
        </div>
        @empty
        <div class="card p-12 text-center">
            <i class="fas fa-building text-gray-300 text-4xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No businesses yet</h3>
            <p class="text-gray-600 mb-6">Start by adding your first business to the alumni directory.</p>
            <a href="{{ route('alumni.businesses.create') }}" class="btn-primary inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>Add Your First Business
            </a>
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
