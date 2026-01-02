@extends('layouts.admin')

@section('title', 'Year Groups Management')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Year Groups Management</h1>
                <p class="mt-2 text-gray-600">Manage alumni year groups and their social group links</p>
            </div>
            <a href="{{ route('admin.year-groups.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Create Year Group
            </a>
        </div>

        <!-- Year Groups List -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-stu-green">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                Year Group
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                Year Range
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                Social Links
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-white uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($yearGroups as $group)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-stu-green rounded-full flex items-center justify-center">
                                            <i class="fas fa-users text-white"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $group->name }}</div>
                                            @if($group->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($group->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">
                                        {{ $group->start_year }} - {{ $group->end_year }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $group->end_year - $group->start_year + 1 }} years span
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        @if($group->whatsapp_link)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                                            </span>
                                        @endif
                                        @if($group->telegram_link)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fab fa-telegram mr-1"></i>Telegram
                                            </span>
                                        @endif
                                        @if($group->gekychat_link)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-comments mr-1"></i>GekyChat
                                            </span>
                                        @endif
                                        @if(!$group->hasSocialLinks())
                                            <span class="text-xs text-gray-400 italic">No links</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($group->is_active)
                                        <span class="badge-success">
                                            <i class="fas fa-check-circle mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge-error">
                                            <i class="fas fa-times-circle mr-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.year-groups.edit', $group) }}" 
                                           class="text-stu-green hover:text-stu-green-dark" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.year-groups.toggle-active', $group) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-yellow-600 hover:text-yellow-900" 
                                                    title="{{ $group->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $group->is_active ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.year-groups.destroy', $group) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this year group?')"
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
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                    <p>No year groups found.</p>
                                    <a href="{{ route('admin.year-groups.create') }}" class="text-stu-green hover:text-stu-green-dark mt-2 inline-block">
                                        Create your first year group
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($yearGroups->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $yearGroups->links() }}
                </div>
            @endif
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 text-xl mt-0.5 mr-4"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-medium mb-2">About Year Groups:</p>
                    <ul class="space-y-1 list-disc list-inside">
                        <li>Year groups help organize alumni by their graduation years</li>
                        <li>Alumni see groups that match their graduation year on their dashboard</li>
                        <li>Add WhatsApp, Telegram, or GekyChat links for alumni to join group chats</li>
                        <li>Year groups can overlap - alumni will see all matching groups</li>
                        <li>Inactive groups won't appear on alumni dashboards</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

