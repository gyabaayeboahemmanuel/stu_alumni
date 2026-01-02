@extends('layouts.app')

@section('title', 'My Businesses')

@section('content')
<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
    <!-- Enhanced Header -->
    <div class="mb-10 animate-fade-in-up">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-stu-green via-stu-green-light to-stu-green-dark p-8 text-white shadow-2xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-briefcase text-3xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">My Businesses</h1>
                            <p class="text-green-100 mt-1 text-lg">Manage your business listings in the alumni directory</p>
                        </div>
                    </div>
                    <a href="{{ route('alumni.businesses.create') }}" class="bg-white text-stu-green-dark hover:text-stu-green px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 border-2 border-stu-green">
                        <i class="fas fa-plus mr-2"></i>Add Business
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 alert-success rounded-xl animate-fade-in-up">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Business List -->
    <div class="grid grid-cols-1 gap-6">
        @forelse($businesses as $index => $business)
        <div class="card p-6 hover-lift animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-6 flex-1">
                    @if($business->logo_path)
                    <div class="relative">
                        <div class="absolute -inset-2 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-xl opacity-20 blur"></div>
                        <img src="{{ asset('storage/' . $business->logo_path) }}" 
                             alt="{{ $business->name }}" 
                             class="relative w-20 h-20 rounded-xl object-cover border-4 border-white shadow-lg">
                    </div>
                    @else
                    <div class="w-20 h-20 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                    @endif
                    
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $business->name }}</h3>
                                <p class="text-sm text-gray-600 font-medium">{{ $business->industry }}</p>
                            </div>
                        </div>
                        
                        <!-- Status Badges -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if($business->status === 'active' && $business->is_verified)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border-2 border-green-300">
                                <i class="fas fa-check-circle mr-1"></i>Verified & Active
                            </span>
                            @elseif($business->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border-2 border-yellow-300">
                                <i class="fas fa-clock mr-1"></i>Pending Approval
                            </span>
                            @elseif($business->status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border-2 border-red-300">
                                <i class="fas fa-times-circle mr-1"></i>Rejected
                            </span>
                            @endif

                            @if($business->is_featured)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-stu-red to-stu-red-light text-white">
                                <i class="fas fa-star mr-1"></i>Featured
                            </span>
                            @endif
                        </div>

                        <!-- Business Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            @if($business->city && $business->country)
                            <div class="flex items-center text-gray-600">
                                <div class="w-8 h-8 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-map-marker-alt text-white text-xs"></i>
                                </div>
                                <span>{{ $business->city }}, {{ $business->country }}</span>
                            </div>
                            @endif
                            
                            @if($business->website)
                            <div class="flex items-center text-gray-600">
                                <div class="w-8 h-8 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-globe text-white text-xs"></i>
                                </div>
                                <a href="{{ $business->website }}" target="_blank" class="text-stu-green hover:text-stu-green-dark font-medium">
                                    {{ parse_url($business->website, PHP_URL_HOST) }}
                                </a>
                            </div>
                            @endif
                            
                            @if($business->phone)
                            <div class="flex items-center text-gray-600">
                                <div class="w-8 h-8 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-phone text-white text-xs"></i>
                                </div>
                                <span>{{ $business->phone }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Description -->
                        @if($business->description)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-gray-700 leading-relaxed">{{ Str::limit($business->description, 150) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col space-y-2 ml-4">
                    <a href="{{ route('alumni.businesses.edit', $business) }}" 
                       class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-stu-green to-stu-green-dark text-white rounded-xl hover:shadow-lg transform hover:scale-110 transition-all duration-200"
                       title="Edit Business">
                        <i class="fas fa-edit"></i>
                    </a>
                    
                    <form action="{{ route('alumni.businesses.destroy', $business) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this business?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-stu-red to-stu-red-light text-white rounded-xl hover:shadow-lg transform hover:scale-110 transition-all duration-200"
                                title="Delete Business">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Created Date -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-xs text-gray-500 flex items-center">
                    <i class="fas fa-calendar mr-1"></i>
                    Listed {{ $business->created_at->diffForHumans() }}
                    @if($business->verified_at)
                    <span class="mx-2">•</span>
                    <i class="fas fa-check-circle mr-1"></i>
                    Verified {{ $business->verified_at->diffForHumans() }}
                    @endif
                </p>
            </div>
        </div>
        @empty
        <div class="card p-16 text-center animate-fade-in-up">
            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-building text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No businesses yet</h3>
            <p class="text-gray-600 text-lg mb-6">Start by adding your first business to the alumni directory.</p>
            <a href="{{ route('alumni.businesses.create') }}" class="btn-primary inline-flex items-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>Add Your First Business
            </a>
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
