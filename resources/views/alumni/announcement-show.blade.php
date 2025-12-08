@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('alumni.announcements') }}" class="inline-flex items-center text-blue-600 hover:text-blue-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Announcements
        </a>
    </div>

    <article class="card p-8">
        <header class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <span class="px-3 py-1 text-sm font-medium rounded-full" 
                      style="background-color: {{ $announcement->category->color }}20; color: {{ $announcement->category->color }};">
                    {{ $announcement->category->name }}
                </span>
                @if($announcement->is_pinned)
                <span class="flex items-center text-yellow-600 text-sm">
                    <i class="fas fa-thumbtack mr-1"></i>
                    Pinned
                </span>
                @endif
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $announcement->title }}</h1>
            
            <div class="flex items-center space-x-6 text-sm text-gray-500">
                <div class="flex items-center">
                    <i class="fas fa-user mr-2"></i>
                    <span>By {{ $announcement->author->name }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-calendar mr-2"></i>
                    <span>Published {{ $announcement->published_at->format('F j, Y') }}</span>
                </div>
            </div>
        </header>

        @if($announcement->featured_image)
        <div class="mb-8">
            <img src="{{ asset('storage/' . $announcement->featured_image) }}" 
                 alt="{{ $announcement->title }}" 
                 class="w-full h-64 object-cover rounded-lg">
        </div>
        @endif

        <div class="prose max-w-none">
            {!! $announcement->content !!}
        </div>
    </article>
</div>
@endsection
