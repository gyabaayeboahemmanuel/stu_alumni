@extends('layouts.admin')

@section('title', 'Business Report')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Business Report</h1>
            <p class="text-gray-600 mt-2">Alumni business directory and statistics</p>
        </div>
        <div>
            <a href="{{ route('admin.reports.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Reports
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6 mb-6">
        <form action="{{ route('admin.reports.businesses') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-input">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <label for="industry" class="form-label">Industry</label>
                <select id="industry" name="industry" class="form-input">
                    <option value="">All Industries</option>
                    @foreach($industries as $industry)
                        <option value="{{ $industry }}" {{ request('industry') == $industry ? 'selected' : '' }}>{{ $industry }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="is_verified" class="form-label">Verification</label>
                <select id="is_verified" name="is_verified" class="form-input">
                    <option value="">All</option>
                    <option value="1" {{ request('is_verified') == '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>Not Verified</option>
                </select>
            </div>
            <div>
                <label for="is_featured" class="form-label">Featured</label>
                <select id="is_featured" name="is_featured" class="form-input">
                    <option value="">All</option>
                    <option value="1" {{ request('is_featured') == '1' ? 'selected' : '' }}>Featured</option>
                    <option value="0" {{ request('is_featured') == '0' ? 'selected' : '' }}>Not Featured</option>
                </select>
            </div>
            <div class="md:col-span-4 flex justify-end space-x-3">
                <a href="{{ route('admin.reports.businesses') }}" class="btn-secondary">Clear Filters</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Businesses Table -->
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Businesses ({{ $businesses->total() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Industry</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($businesses as $business)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $business->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $business->alumni->first_name }} {{ $business->alumni->last_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $business->industry ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $business->city ?? 'N/A' }}{{ $business->city && $business->country ? ', ' : '' }}{{ $business->country ?? '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                @if($business->is_verified)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Verified</span>
                                @endif
                                @if($business->is_featured)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Featured</span>
                                @endif
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $business->status == 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($business->status) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('businesses.public.show', $business->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No businesses found matching the filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($businesses->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $businesses->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
