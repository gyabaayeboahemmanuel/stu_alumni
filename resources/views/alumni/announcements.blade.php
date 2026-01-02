@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<div class="max-w-5xl mx-auto py-8 sm:px-6 lg:px-8">
    <!-- Enhanced Header -->
    <div class="mb-10 animate-fade-in-up">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-stu-green via-stu-green-light to-stu-green-dark p-8 text-white shadow-2xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-bullhorn text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Alumni Announcements</h1>
                        <p class="text-green-100 mt-1 text-lg">Stay updated with the latest news and opportunities</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($announcements as $index => $announcement)
        <div class="card p-6 hover-lift animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <h2 class="text-xl font-bold text-gray-900">{{ $announcement->title }}</h2>
                        @if($announcement->is_pinned)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border-2 border-yellow-300">
                            <i class="fas fa-thumbtack mr-1"></i>Pinned
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center flex-wrap gap-4 text-sm text-gray-600 mb-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-2">
                                <i class="fas fa-user text-white text-xs"></i>
                            </div>
                            <span class="font-medium">{{ $announcement->author->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-2">
                                <i class="fas fa-calendar text-white text-xs"></i>
                            </div>
                            <span>{{ $announcement->published_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-2">
                                <i class="fas fa-tag text-white text-xs"></i>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium" style="background-color: {{ $announcement->category->color }}20; color: {{ $announcement->category->color }}; border: 1px solid {{ $announcement->category->color }}40;">
                                {{ $announcement->category->name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="prose max-w-none mb-6">
                @if($announcement->excerpt)
                    <p class="text-gray-700 leading-relaxed">{{ $announcement->excerpt }}</p>
                @else
                    <p class="text-gray-700 leading-relaxed">{{ Str::limit(strip_tags($announcement->content), 200) }}</p>
                @endif
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <a href="{{ route('alumni.announcements.show', $announcement->slug) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-stu-green to-stu-green-dark text-white font-medium rounded-xl hover:from-stu-green-dark hover:to-stu-green shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    Read Full Story <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <div class="text-sm text-gray-500 flex items-center">
                    <i class="fas fa-clock mr-1"></i>
                    {{ $announcement->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @empty
        <div class="card p-16 text-center animate-fade-in-up">
            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-bullhorn text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No announcements yet</h3>
            <p class="text-gray-600 text-lg">Check back later for news and updates.</p>
        </div>
        @endforelse
    </div>

    @if($announcements->hasPages())
    <div class="mt-10 flex justify-center">
        <div class="bg-white rounded-xl shadow-lg p-4">
            {{ $announcements->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
