@extends('layouts.admin')

@section('title', 'Donation Details')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.donations.index') }}" class="text-blue-600 hover:text-blue-900">
            <i class="fas fa-arrow-left mr-2"></i>Back to Donations
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-2 border-green-200 rounded-xl p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="card p-8">
        <!-- Header -->
        <div class="mb-6 pb-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Donation Details</h1>
                    <p class="text-gray-600 mt-1">Submitted on {{ $donation->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $donation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                       ($donation->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                    {{ ucfirst($donation->status) }}
                </span>
            </div>
        </div>

        <!-- Donor Information -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Donor Information</h2>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="text-base font-medium text-gray-900">
                            @if($donation->alumni)
                                {{ $donation->alumni->full_name }}
                            @elseif($donation->user)
                                {{ $donation->user->name }}
                            @else
                                Anonymous
                            @endif
                        </p>
                    </div>
                    @if($donation->alumni)
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-base font-medium text-gray-900">{{ $donation->alumni->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Programme</p>
                            <p class="text-base font-medium text-gray-900">{{ $donation->alumni->programme }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Graduation Year</p>
                            <p class="text-base font-medium text-gray-900">{{ $donation->alumni->year_of_completion }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Donation Details -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Donation Details</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Items</p>
                    <p class="text-base text-gray-900 bg-gray-50 rounded-lg p-4">{{ $donation->items }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Description</p>
                    <p class="text-base text-gray-900 bg-gray-50 rounded-lg p-4 whitespace-pre-wrap">{{ $donation->description }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Country</p>
                        <p class="text-base text-gray-900">{{ $donation->country }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">City</p>
                        <p class="text-base text-gray-900">{{ $donation->city }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Contact</p>
                        <p class="text-base text-gray-900">{{ $donation->contact }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Update Form -->
        <div class="border-t border-gray-200 pt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h2>
            <form action="{{ route('admin.donations.update-status', $donation) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" class="form-input w-full md:w-64">
                        <option value="pending" {{ $donation->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $donation->status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $donation->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                    <textarea id="admin_notes" name="admin_notes" rows="4" 
                              class="form-input w-full"
                              placeholder="Add any notes about this donation...">{{ old('admin_notes', $donation->admin_notes) }}</textarea>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Update Status
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
