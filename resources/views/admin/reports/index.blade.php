@extends('layouts.app')

@section('title', 'System Reports')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">System Reports & Analytics</h1>
        <p class="text-gray-600 mt-2">Comprehensive overview of system usage and statistics</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        @foreach($stats as $key => $value)
        <div class="card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @php
                        $icons = [
                            'total_alumni' => 'users',
                            'verified_alumni' => 'badge-check',
                            'pending_alumni' => 'clock',
                            'total_businesses' => 'briefcase',
                            'verified_businesses' => 'shield-check',
                            'total_events' => 'calendar',
                            'upcoming_events' => 'calendar-plus',
                            'total_announcements' => 'bullhorn',
                            'published_announcements' => 'eye'
                        ];
                        $colors = [
                            'total_alumni' => 'blue',
                            'verified_alumni' => 'green',
                            'pending_alumni' => 'yellow',
                            'total_businesses' => 'purple',
                            'verified_businesses' => 'green',
                            'total_events' => 'indigo',
                            'upcoming_events' => 'pink',
                            'total_announcements' => 'orange',
                            'published_announcements' => 'teal'
                        ];
                    @endphp
                    <div class="w-8 h-8 bg-{{ $colors[$key] }}-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-{{ $icons[$key] }} text-{{ $colors[$key] }}-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 capitalize">
                        {{ str_replace('_', ' ', $key) }}
                    </p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $value }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Alumni by Year -->
        <div class="card p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Alumni by Graduation Year</h2>
            <div class="space-y-3">
                @foreach($alumniByYear as $data)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $data->year_of_completion }}</span>
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-900 mr-2">{{ $data->count }}</span>
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" 
                                 style="width: {{ ($data->count / max($alumniByYear->max('count'), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Programmes -->
        <div class="card p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Programmes</h2>
            <div class="space-y-3">
                @foreach($alumniByProgramme as $data)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 truncate flex-1 mr-2">{{ $data->programme }}</span>
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-900 mr-2">{{ $data->count }}</span>
                        <div class="w-16 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" 
                                 style="width: {{ ($data->count / max($alumniByProgramme->max('count'), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Alumni by Country -->
        <div class="card p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Alumni by Country</h2>
            <div class="space-y-3">
                @foreach($alumniByCountry as $data)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $data->country }}</span>
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-900 mr-2">{{ $data->count }}</span>
                        <div class="w-16 bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" 
                                 style="width: {{ ($data->count / max($alumniByCountry->max('count'), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Business by Industry -->
        <div class="card p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Business by Industry</h2>
            <div class="space-y-3">
                @foreach($businessByIndustry as $data)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $data->industry }}</span>
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-900 mr-2">{{ $data->count }}</span>
                        <div class="w-16 bg-gray-200 rounded-full h-2">
                            <div class="bg-orange-600 h-2 rounded-full" 
                                 style="width: {{ ($data->count / max($businessByIndustry->max('count'), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Registrations -->
    <div class="mt-8 card p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Recent Registrations</h2>
            <a href="{{ route('admin.reports.alumni') }}" class="text-sm text-blue-600 hover:text-blue-500">
                View All
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Programme</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registered</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentRegistrations as $alumni)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $alumni->full_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $alumni->programme }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $alumni->year_of_completion }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $alumni->verification_status === 'verified' ? 'bg-green-100 text-green-800' : 
                                   ($alumni->verification_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($alumni->verification_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $alumni->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Report Links -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.reports.alumni') }}" class="card p-6 hover:shadow-lg transition duration-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Alumni Reports</h3>
                    <p class="text-sm text-gray-600 mt-1">Detailed alumni statistics and exports</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.businesses') }}" class="card p-6 hover:shadow-lg transition duration-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-briefcase text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Business Reports</h3>
                    <p class="text-sm text-gray-600 mt-1">Business directory analytics</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.events') }}" class="card p-6 hover:shadow-lg transition duration-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Event Reports</h3>
                    <p class="text-sm text-gray-600 mt-1">Event participation and analytics</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
