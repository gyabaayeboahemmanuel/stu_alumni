@extends('layouts.admin')

@section('title', 'Alumni Management')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Alumni Management</h1>
            <p class="text-gray-600 mt-2">Manage and verify alumni registrations</p>
        </div>
        <div class="flex space-x-3">
            <a href="#" class="btn-primary">
                <i class="fas fa-download mr-2"></i>Export
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6 mb-6">
        <form action="{{ route('admin.alumni.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="form-label">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       class="form-input" placeholder="Name, email, or student ID...">
            </div>
            <div>
                <label for="verification_status" class="form-label">Verification Status</label>
                <select id="verification_status" name="verification_status" class="form-input">
                    <option value="">All Statuses</option>
                    <option value="unverified" {{ request('verification_status') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                    <option value="pending" {{ request('verification_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ request('verification_status') == 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="rejected" {{ request('verification_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <label for="year" class="form-label">Graduation Year</label>
                <input type="number" id="year" name="year" value="{{ request('year') }}" 
                       class="form-input" placeholder="e.g., 2018" min="1990" max="{{ date('Y') }}">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="mb-4 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <select id="bulk-action" class="form-input w-48">
                <option value="">Bulk Actions</option>
                <option value="verify">Verify Selected</option>
                <option value="delete">Delete Selected</option>
            </select>
            <button type="button" id="apply-bulk-action" class="btn-secondary">
                Apply
            </button>
        </div>
        
        <div class="text-sm text-gray-600">
            Showing {{ $alumni->firstItem() }} to {{ $alumni->lastItem() }} of {{ $alumni->total() }} alumni
        </div>
    </div>

    <!-- Alumni Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Alumni
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Programme & Year
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Registered
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($alumni as $alumnus)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="alumni_ids[]" value="{{ $alumnus->id }}" 
                                   class="alumni-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" 
                                         src="{{ $alumnus->profile_photo_path ? asset('storage/' . $alumnus->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($alumnus->full_name) . '&color=FFFFFF&background=1E40AF' }}" 
                                         alt="{{ $alumnus->full_name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $alumnus->full_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $alumnus->student_id ?? 'No ID' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $alumnus->programme }}</div>
                            <div class="text-sm text-gray-500">{{ $alumnus->year_of_completion }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $alumnus->email }}</div>
                            <div class="text-sm text-gray-500">{{ $alumnus->phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $alumnus->verification_status === 'verified' ? 'bg-green-100 text-green-800' : 
                                   ($alumnus->verification_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($alumnus->verification_status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                   'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($alumnus->verification_status) }}
                            </span>
                            @if($alumnus->verification_source)
                            <div class="text-xs text-gray-500 mt-1">
                                {{ ucfirst($alumnus->verification_source) }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $alumnus->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.alumni.show', $alumnus) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.alumni.edit', $alumnus) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($alumnus->verification_status === 'pending')
                                <form action="{{ route('admin.alumni.verify', $alumnus) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Verify">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                
                                <form action="{{ route('admin.alumni.destroy', $alumnus) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Are you sure you want to delete this alumni?')"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            No alumni found matching your criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($alumni->hasPages())
    <div class="mt-4">
        {{ $alumni->links() }}
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    const selectAll = document.getElementById('select-all');
    const alumniCheckboxes = document.querySelectorAll('.alumni-checkbox');
    
    selectAll.addEventListener('change', function() {
        alumniCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
    });

    // Bulk actions
    const bulkAction = document.getElementById('bulk-action');
    const applyBulkAction = document.getElementById('apply-bulk-action');
    
    applyBulkAction.addEventListener('click', function() {
        const selectedAlumni = Array.from(alumniCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);
        
        if (selectedAlumni.length === 0) {
            alert('Please select at least one alumni.');
            return;
        }
        
        if (!bulkAction.value) {
            alert('Please select a bulk action.');
            return;
        }
        
        if (confirm(`Are you sure you want to ${bulkAction.value} ${selectedAlumni.length} alumni?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.alumni.bulk-action") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = bulkAction.value;
            form.appendChild(actionInput);
            
            selectedAlumni.forEach(alumniId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'alumni_ids[]';
                input.value = alumniId;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>
@endsection
