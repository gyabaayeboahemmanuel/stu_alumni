@extends('layouts.admin')

@section('title', 'Chapters Management')
@section('page-title', 'Chapters')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Chapters Management</h1>
                <p class="mt-1 text-gray-600">Manage regional alumni chapters</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.chapters.pending') }}" class="btn-secondary">
                    <i class="fas fa-clock mr-2"></i>Pending Approvals
                </a>
                <a href="{{ route('admin.chapters.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Create Chapter
                </a>
            </div>
        </div>

        <!-- Chapters List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
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
                                Members
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($chapters as $chapter)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-stu-green rounded-lg flex items-center justify-center">
                                            <i class="fas fa-map-marker-alt text-white"></i>
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas fa-users text-gray-400 mr-2"></i>
                                        <span class="text-sm font-medium text-gray-900">{{ $chapter->members_count ?? 0 }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        @if($chapter->is_approved)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @endif
                                        @if($chapter->is_active)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-eye mr-1"></i>Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-eye-slash mr-1"></i>Inactive
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        @if(!$chapter->is_approved)
                                            <form action="{{ route('admin.chapters.approve', $chapter) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Approve">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.chapters.edit', $chapter) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.chapters.toggle-active', $chapter) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-yellow-600 hover:text-yellow-900" 
                                                    title="{{ $chapter->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $chapter->is_active ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.chapters.destroy', $chapter) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure? This chapter has {{ $chapter->members_count ?? 0 }} members.')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-map-marked-alt text-4xl mb-4 text-gray-300"></i>
                                    <p>No chapters found.</p>
                                    <a href="{{ route('admin.chapters.create') }}" class="text-stu-green hover:text-stu-green-dark mt-2 inline-block">
                                        Create your first chapter
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($chapters->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $chapters->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

