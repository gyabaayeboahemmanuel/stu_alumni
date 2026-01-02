@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
<div class="max-w-5xl mx-auto py-8 sm:px-6 lg:px-8">
    <div class="mb-6 animate-fade-in-up">
        <a href="{{ route('alumni.announcements') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-stu-green hover:bg-stu-green hover:text-white transition-all duration-200 border-2 border-stu-green">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Announcements
        </a>
    </div>

    <article class="card p-8 hover-lift animate-fade-in-up" style="animation-delay: 0.1s">
        <header class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <span class="px-4 py-2 text-sm font-semibold rounded-full border-2" 
                      style="background-color: {{ $announcement->category->color }}20; color: {{ $announcement->category->color }}; border-color: {{ $announcement->category->color }}40;">
                    <i class="fas fa-tag mr-2"></i>{{ $announcement->category->name }}
                </span>
                @if($announcement->is_pinned)
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border-2 border-yellow-300">
                    <i class="fas fa-thumbtack mr-2"></i>Pinned
                </span>
                @endif
            </div>
            
            <h1 class="text-4xl font-bold text-gray-900 mb-6 leading-tight">{{ $announcement->title }}</h1>
            
            <div class="flex items-center flex-wrap gap-6 text-sm">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Author</div>
                        <div class="font-semibold text-gray-900">{{ $announcement->author->name }}</div>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-stu-green to-stu-green-dark rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calendar text-white"></i>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Published</div>
                        <div class="font-semibold text-gray-900">{{ $announcement->published_at->format('F j, Y') }}</div>
                    </div>
                </div>
            </div>
        </header>

        @if($announcement->featured_image)
        <div class="mb-8 rounded-2xl overflow-hidden shadow-xl">
            <img src="{{ asset('storage/' . $announcement->featured_image) }}" 
                 alt="{{ $announcement->title }}" 
                 class="w-full h-96 object-cover">
        </div>
        @endif

        <div class="prose prose-lg max-w-none">
            {!! $announcement->content !!}
        </div>
    </article>
</div>
@endsection
