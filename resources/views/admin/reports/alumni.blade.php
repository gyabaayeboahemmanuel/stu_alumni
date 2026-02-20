@extends('layouts.admin')

@section('title', 'Alumni Report')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Alumni Report</h1>
            <p class="text-gray-600 mt-2">Detailed alumni information and statistics</p>
        </div>
        <div>
            <a href="{{ route('admin.reports.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Reports
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6 mb-6">
        <form action="{{ route('admin.reports.alumni') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="verification_status" class="form-label">Verification Status</label>
                <select id="verification_status" name="verification_status" class="form-input">
                    <option value="">All Statuses</option>
                    <option value="verified" {{ request('verification_status') == 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="pending" {{ request('verification_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('verification_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <label for="year_of_completion" class="form-label">Year of Completion</label>
                <select id="year_of_completion" name="year_of_completion" class="form-input">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year_of_completion') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="programme" class="form-label">Programme</label>
                <input type="text" id="programme" name="programme" value="{{ request('programme') }}" 
                       class="form-input" placeholder="Search programme...">
            </div>
            <div>
                <label for="country" class="form-label">Country</label>
                <select id="country" name="country" class="form-input">
                    <option value="">All Countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4 flex justify-end space-x-3">
                <a href="{{ route('admin.reports.alumni') }}" class="btn-secondary">Clear Filters</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Alumni Table -->
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Alumni List ({{ $alumni->total() }})</h2>
            <a href="{{ route('admin.reports.export-alumni', request()->all()) }}" class="btn-secondary">
                <i class="fas fa-download mr-2"></i>Export
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Programme</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Country</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($alumni as $alumnus)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $alumnus->first_name }} {{ $alumnus->last_name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $alumnus->user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $alumnus->programme ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $alumnus->year_of_completion ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $alumnus->country ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $alumnus->verification_status == 'verified' ? 'bg-green-100 text-green-800' : 
                                   ($alumnus->verification_status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($alumnus->verification_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.alumni.show', $alumnus) }}" class="text-blue-600 hover:text-blue-900">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No alumni found matching the filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($alumni->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $alumni->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
