@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Alumni Announcements</h1>
        <p class="text-gray-600 mt-2">Stay updated with the latest news and opportunities</p>
    </div>

    <div class="space-y-6">
        @forelse($announcements as $announcement)
        <div class="card p-6 hover:shadow-lg transition duration-200">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $announcement->title }}</h2>
                    <div class="flex items-center mt-2 space-x-4 text-sm text-gray-500">
                        <div class="flex items-center">
                            <i class="fas fa-user mr-1"></i>
                            <span>{{ $announcement->author->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            <span>{{ $announcement->published_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-tag mr-1"></i>
                            <span class="px-2 py-1 text-xs rounded-full" style="background-color: {{ $announcement->category->color }}20; color: {{ $announcement->category->color }};">
                                {{ $announcement->category->name }}
                            </span>
                        </div>
                    </div>
                </div>
                @if($announcement->is_pinned)
                <div class="flex items-center text-yellow-600">
                    <i class="fas fa-thumbtack mr-1"></i>
                    <span class="text-sm font-medium">Pinned</span>
                </div>
                @endif
            </div>

            <div class="prose max-w-none mb-4">
                @if($announcement->excerpt)
                    <p class="text-gray-600">{{ $announcement->excerpt }}</p>
                @else
                    <p class="text-gray-600">{{ Str::limit(strip_tags($announcement->content), 200) }}</p>
                @endif
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('alumni.announcements.show', $announcement->slug) }}" 
                   class="text-blue-600 hover:text-blue-500 font-medium">
                    Read Full Story â†’
                </a>
                <div class="text-sm text-gray-500">
                    {{ $announcement->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @empty
        <div class="card p-12 text-center">
            <i class="fas fa-bullhorn text-gray-300 text-4xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No announcements yet</h3>
            <p class="text-gray-600">Check back later for news and updates.</p>
        </div>
        @endforelse
    </div>

    @if($announcements->hasPages())
    <div class="mt-8">
        {{ $announcements->links() }}
    </div>
    @endif
</div>
@endsection
