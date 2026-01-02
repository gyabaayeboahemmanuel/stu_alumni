@extends('layouts.admin')

@section('title', $alumni->full_name . ' - Alumni Details')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $alumni->full_name }}</h1>
            <p class="text-gray-600 mt-2">Alumni Details and Management</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.alumni.edit', $alumni) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.alumni.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Sidebar -->
        <div class="lg:col-span-1">
            <div class="card p-6">
                <!-- Profile Photo -->
                <div class="text-center">
                    <img class="h-32 w-32 rounded-full mx-auto" 
                         src="{{ $alumni->profile_photo_path ? asset('storage/' . $alumni->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($alumni->full_name) . '&color=FFFFFF&background=1E40AF&size=128' }}" 
                         alt="{{ $alumni->full_name }}">
                </div>

                <!-- Verification Status -->
                <div class="mt-6 text-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $alumni->verification_status === 'verified' ? 'bg-green-100 text-green-800' : 
                                   ($alumni->verification_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($alumni->verification_status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                   'bg-gray-100 text-gray-800')) }}">
                        <i class="fas {{ $alumni->verification_status === 'verified' ? 'fa-check-circle' : 
                                       ($alumni->verification_status === 'pending' ? 'fa-clock' : 'fa-times-circle') }} mr-1"></i>
                        {{ ucfirst($alumni->verification_status) }}
                    </span>
                    
                    @if($alumni->verification_source)
                    <p class="text-sm text-gray-600 mt-2">
                        Verified via: {{ ucfirst($alumni->verification_source) }}
                    </p>
                    @endif
                    
                    @if($alumni->verified_at)
                    <p class="text-sm text-gray-600 mt-1">
                        On: {{ $alumni->verified_at->format('M j, Y g:i A') }}
                    </p>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 space-y-3">
                    @if($alumni->verification_status === 'pending')
                    <form action="{{ route('admin.alumni.verify', $alumni) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full btn-success">
                            <i class="fas fa-check mr-2"></i>Verify Alumni
                        </button>
                    </form>
                    
                    <button type="button" onclick="showRejectionForm()" class="w-full btn-danger">
                        <i class="fas fa-times mr-2"></i>Reject Verification
                    </button>
                    @endif

                
                </div>

                <!-- Rejection Form (Hidden Initially) -->
                @if($alumni->verification_status === 'pending')
                <div id="rejection-form" class="mt-4 hidden">
                    <form action="{{ route('admin.alumni.reject', $alumni) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason</label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="3" 
                                      class="form-input" required placeholder="Please provide a reason for rejection..."></textarea>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="btn-danger flex-1">Confirm Rejection</button>
                            <button type="button" onclick="hideRejectionForm()" class="btn-secondary flex-1">Cancel</button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Account Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Account Information</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Member Since</span>
                            <span class="font-medium">{{ $alumni->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Last Updated</span>
                            <span class="font-medium">{{ $alumni->updated_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Registration Method</span>
                            <span class="font-medium capitalize">{{ $alumni->registration_method }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Personal Information -->
            <div class="card p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">First Name</label>
                        <p class="text-gray-900">{{ $alumni->first_name }}</p>
                    </div>
                    <div>
                        <label class="form-label">Last Name</label>
                        <p class="text-gray-900">{{ $alumni->last_name }}</p>
                    </div>
                    <div>
                        <label class="form-label">Other Names</label>
                        <p class="text-gray-900">{{ $alumni->other_names ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="form-label">Gender</label>
                        <p class="text-gray-900 capitalize">{{ $alumni->gender ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="form-label">Date of Birth</label>
                        <p class="text-gray-900">{{ $alumni->date_of_birth?->format('M j, Y') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="card p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Academic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Student ID</label>
                        <p class="text-gray-900">{{ $alumni->student_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="form-label">Year of Completion</label>
                        <p class="text-gray-900">{{ $alumni->year_of_completion }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Programme</label>
                        <p class="text-gray-900">{{ $alumni->programme }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Qualification</label>
                        <p class="text-gray-900">{{ $alumni->qualification ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Email Address</label>
                        <p class="text-gray-900">{{ $alumni->email }}</p>
                    </div>
                    <div>
                        <label class="form-label">Phone Number</label>
                        <p class="text-gray-900">{{ $alumni->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="form-label">Country</label>
                        <p class="text-gray-900">{{ $alumni->country ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="form-label">City</label>
                        <p class="text-gray-900">{{ $alumni->city ?? 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Postal Address</label>
                        <p class="text-gray-900">{{ $alumni->postal_address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="card p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Professional Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Current Employer</label>
                        <p class="text-gray-900">{{ $alumni->current_employer ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="form-label">Job Title</label>
                        <p class="text-gray-900">{{ $alumni->job_title ?? 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Industry</label>
                        <p class="text-gray-900">{{ $alumni->industry ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Social Links -->
            <div class="card p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Social Links</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Website</label>
                        <p class="text-gray-900">
                            @if($alumni->website)
                                <a href="{{ $alumni->website }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                    {{ $alumni->website }}
                                </a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="form-label">LinkedIn</label>
                        <p class="text-gray-900">
                            @if($alumni->linkedin)
                                <a href="{{ $alumni->linkedin }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                    LinkedIn Profile
                                </a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="form-label">Twitter</label>
                        <p class="text-gray-900">
                            @if($alumni->twitter)
                                <a href="{{ $alumni->twitter }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                    Twitter Profile
                                </a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="form-label">Facebook</label>
                        <p class="text-gray-900">
                            @if($alumni->facebook)
                                <a href="{{ $alumni->facebook }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                    Facebook Profile
                                </a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showRejectionForm() {
    document.getElementById('rejection-form').classList.remove('hidden');
}

function hideRejectionForm() {
    document.getElementById('rejection-form').classList.add('hidden');
}
</script>
@endsection
