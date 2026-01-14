@extends('layouts.admin')

@section('title', 'In-Kind Donations')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">In-Kind Donations</h1>
        <p class="text-gray-600 mt-2">Manage and review in-kind donations from alumni</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-2 border-green-200 rounded-xl p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="card p-4 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <a href="{{ route('admin.donations.index') }}" 
               class="px-4 py-2 rounded-lg {{ request('status') == null ? 'bg-stu-green text-white' : 'bg-gray-100 text-gray-700' }}">
                All ({{ $donations->total() }})
            </a>
            <a href="{{ route('admin.donations.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-lg {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                Pending ({{ \App\Models\Donation::inKind()->pending()->count() }})
            </a>
            <a href="{{ route('admin.donations.index', ['status' => 'approved']) }}" 
               class="px-4 py-2 rounded-lg {{ request('status') == 'approved' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                Approved ({{ \App\Models\Donation::inKind()->approved()->count() }})
            </a>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($donations as $donation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    @if($donation->alumni)
                                        {{ $donation->alumni->full_name }}
                                    @elseif($donation->user)
                                        {{ $donation->user->name }}
                                    @else
                                        Anonymous
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ \Illuminate\Support\Str::limit($donation->items, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $donation->city }}, {{ $donation->country }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $donation->contact }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $donation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($donation->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $donation->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.donations.show', $donation) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No donations found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $donations->links() }}
        </div>
    </div>
</div>
@endsection
