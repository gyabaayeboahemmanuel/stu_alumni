@extends('layouts.app')

@section('title', 'Executive Committee')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Hero Section -->
    <div class="relative bg-stu-green text-white">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover mix-blend-overlay opacity-20" src="{{ asset('stu_campus.jpg') }}" alt="STU Campus">
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                    Executive <span class="text-stu-red-light">Committee</span>
                </h1>
                <p class="mt-6 text-xl max-w-3xl mx-auto">
                    Meet the dedicated alumni leaders who guide and represent the STU Alumni Association.
                </p>
            </div>
        </div>
    </div>

    <!-- Executives Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($executives->count() > 0)
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($executives as $executive)
                        <div class="card bg-white rounded-xl overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="bg-gradient-to-br from-stu-green to-stu-green-dark p-6 text-center">
                                @if($executive->alumni && $executive->alumni->profile_photo_path)
                                    <img src="{{ asset('storage/' . $executive->alumni->profile_photo_path) }}" 
                                         alt="{{ $executive->alumni->first_name }} {{ $executive->alumni->last_name }}"
                                         class="w-24 h-24 rounded-full mx-auto border-4 border-white shadow-lg object-cover">
                                @else
                                    <div class="w-24 h-24 rounded-full mx-auto border-4 border-white shadow-lg bg-white/20 flex items-center justify-center">
                                        <i class="fas fa-user text-white text-4xl"></i>
                                    </div>
                                @endif
                                <h3 class="mt-4 text-xl font-bold text-white">
                                    {{ $executive->alumni ? $executive->alumni->first_name . ' ' . $executive->alumni->last_name : 'N/A' }}
                                </h3>
                                <p class="text-stu-red-light font-semibold mt-1">{{ $executive->position }}</p>
                            </div>
                            <div class="p-6">
                                @if($executive->bio)
                                    <p class="text-gray-600 mb-4">{{ \Illuminate\Support\Str::limit($executive->bio, 150) }}</p>
                                @endif
                                
                                <div class="space-y-2 text-sm">
                                    @if($executive->term_year)
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-calendar-alt text-stu-green mr-2"></i>
                                            <span>Term: {{ $executive->term_year }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($executive->alumni && $executive->alumni->year_of_completion)
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-graduation-cap text-stu-green mr-2"></i>
                                            <span>Class of {{ $executive->alumni->year_of_completion }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($executive->alumni && $executive->alumni->programme)
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-book text-stu-green mr-2"></i>
                                            <span>{{ $executive->alumni->programme }}</span>
                                        </div>
                                    @endif
                                </div>

                                @if($executive->achievements)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Achievements</h4>
                                        <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($executive->achievements, 100) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Executives Found</h3>
                    <p class="text-gray-600">
                        Executive committee information will be available soon.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Contact Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Get in Touch</h2>
                <p class="text-gray-600 mb-6">
                    Have questions or want to connect with the executive committee? We'd love to hear from you.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @php
                        $contactEmail = \App\Models\SiteSetting::get('contact_email', 'alumni@stu.edu.gh');
                        $contactPhone = \App\Models\SiteSetting::get('contact_phone', '+233 (0) 35 209 1234');
                    @endphp
                    <a href="mailto:{{ $contactEmail }}" class="btn-primary inline-flex items-center justify-center">
                        <i class="fas fa-envelope mr-2"></i>Email Us
                    </a>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}" class="btn-secondary inline-flex items-center justify-center">
                        <i class="fas fa-phone mr-2"></i>Call Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
