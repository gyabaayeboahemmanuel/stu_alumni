<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumniManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumni::with('user');

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('student_id', 'LIKE', "%{$search}%");
            });
        }

        // Verification status filter
        if ($request->has('verification_status') && $request->verification_status) {
            $query->where('verification_status', $request->verification_status);
        }

        // Year filter
        if ($request->has('year') && $request->year) {
            $query->where('year_of_completion', $request->year);
        }

        $alumni = $query->latest()->paginate(20);

        return view('admin.alumni.index', compact('alumni'));
    }

    public function show(Alumni $alumni)
    {
        $alumni->load('user', 'businesses', 'eventRegistrations.event');
        return view('admin.alumni.show', compact('alumni'));
    }

    public function edit(Alumni $alumni)
    {
        return view('admin.alumni.edit', compact('alumni'));
    }

    public function update(Request $request, Alumni $alumni)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'other_names' => 'nullable|string|max:100',
            'email' => 'required|email|unique:alumni,email,' . $alumni->id,
            'phone' => 'required|string|max:15',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
            'year_of_completion' => 'required|integer',
            'programme' => 'required|string|max:200',
            'qualification' => 'required|string|max:100',
            'current_employer' => 'nullable|string|max:200',
            'job_title' => 'nullable|string|max:200',
            'industry' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'is_visible_in_directory' => 'boolean',
        ]);

        $alumni->update($validated);

        // Update user email if changed
        if ($alumni->user && $alumni->user->email !== $validated['email']) {
            $alumni->user->update(['email' => $validated['email']]);
        }

        // Log the action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'description' => "Updated alumni profile for {$alumni->full_name}",
            'model_type' => Alumni::class,
            'model_id' => $alumni->id,
        ]);

        return redirect()->route('admin.alumni.show', $alumni)
            ->with('success', 'Alumni profile updated successfully!');
    }

    public function verify(Alumni $alumni)
    {
        $alumni->markAsVerified('manual');

        // Log the action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'verify',
            'description' => "Manually verified alumni: {$alumni->full_name}",
            'model_type' => Alumni::class,
            'model_id' => $alumni->id,
        ]);

        return redirect()->back()->with('success', 'Alumni verified successfully!');
    }

    public function reject(Alumni $alumni, Request $request)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $alumni->update([
            'verification_status' => 'rejected',
            'proof_document_path' => null, // Remove proof document
        ]);

        // Delete proof document
        if ($alumni->proof_document_path) {
            Storage::disk('public')->delete($alumni->proof_document_path);
        }

        // Log the action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'reject',
            'description' => "Rejected alumni verification for {$alumni->full_name}. Reason: {$request->rejection_reason}",
            'model_type' => Alumni::class,
            'model_id' => $alumni->id,
        ]);

        // TODO: Send rejection notification to alumni

        return redirect()->back()->with('success', 'Alumni verification rejected!');
    }

    public function destroy(Alumni $alumni)
    {
        $alumniName = $alumni->full_name;
        
        // Delete associated user
        if ($alumni->user) {
            $alumni->user->delete();
        }

        // Delete proof document
        if ($alumni->proof_document_path) {
            Storage::disk('public')->delete($alumni->proof_document_path);
        }

        // Log the action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'description' => "Deleted alumni: {$alumniName}",
            'model_type' => Alumni::class,
            'model_id' => $alumni->id,
        ]);

        $alumni->delete();

        return redirect()->route('admin.alumni.index')
            ->with('success', 'Alumni deleted successfully!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:verify,delete',
            'alumni_ids' => 'required|array',
            'alumni_ids.*' => 'exists:alumni,id',
        ]);

        $count = 0;

        foreach ($request->alumni_ids as $alumniId) {
            $alumni = Alumni::find($alumniId);

            if ($request->action === 'verify') {
                $alumni->markAsVerified('manual');
                $count++;
            } elseif ($request->action === 'delete') {
                if ($alumni->user) {
                    $alumni->user->delete();
                }
                $alumni->delete();
                $count++;
            }
        }

        $action = $request->action === 'verify' ? 'verified' : 'deleted';
        return redirect()->back()->with('success', "{$count} alumni {$action} successfully!");
    }
}
