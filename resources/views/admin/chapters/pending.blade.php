@extends('layouts.admin')

@section('title', 'Pending Chapter Approvals')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pending Chapter Approvals</h1>
                <p class="mt-1 text-gray-600">Review and approve chapter requests</p>
            </div>
            <div>
                <a href="{{ route('admin.chapters.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Chapters
                </a>
            </div>
        </div>

        <!-- Pending Chapters List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($chapters->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Chapter
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Location
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    President
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Requested
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($chapters as $chapter)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-clock text-yellow-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $chapter->name }}</div>
                                                @if($chapter->description)
                                                    <div class="text-xs text-gray-500">{{ Str::limit($chapter->description, 40) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $chapter->city ?? 'N/A' }}{{ $chapter->city && $chapter->region ? ', ' : '' }}{{ $chapter->region ?? '' }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $chapter->country }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($chapter->president)
                                            <div class="text-sm text-gray-900">
                                                {{ $chapter->president->first_name }} {{ $chapter->president->last_name }}
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">No president</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $chapter->created_at->format('M j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <form action="{{ route('admin.chapters.approve', $chapter) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="text-green-600 hover:text-green-900" 
                                                        title="Approve">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.chapters.edit', $chapter) }}" 
                                               class="text-blue-600 hover:text-blue-900" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($chapters->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $chapters->links() }}
                    </div>
                @endif
            @else
                <div class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-check-circle text-4xl mb-4 text-green-300"></i>
                    <p class="text-lg font-medium">No pending approvals</p>
                    <p class="text-sm mt-2">All chapter requests have been processed.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
