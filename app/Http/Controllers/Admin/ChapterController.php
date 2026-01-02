<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    /**
     * Display a listing of chapters
     */
    public function index()
    {
        $chapters = Chapter::with('president')
            ->withCount('members')
            ->orderBy('country')
            ->orderBy('region')
            ->orderBy('city')
            ->paginate(15);
        
        return view('admin.chapters.index', compact('chapters'));
    }

    /**
     * Show the form for creating a new chapter
     */
    public function create()
    {
        return view('admin.chapters.create');
    }

    /**
     * Store a newly created chapter
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'meeting_location' => 'nullable|string|max:500',
            'whatsapp_link' => 'nullable|url|max:500',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Chapter::create([
                'name' => $request->name,
                'region' => $request->region,
                'city' => $request->city,
                'country' => $request->country,
                'description' => $request->description,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'meeting_location' => $request->meeting_location,
                'whatsapp_link' => $request->whatsapp_link,
                'is_active' => $request->boolean('is_active', true),
                'is_approved' => true, // Admin-created chapters are auto-approved
            ]);

            return redirect()->route('admin.chapters.index')
                ->with('success', 'Chapter created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing a chapter
     */
    public function edit(Chapter $chapter)
    {
        $alumni = Alumni::whereNotNull('student_id')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'student_id']);
        
        return view('admin.chapters.edit', compact('chapter', 'alumni'));
    }

    /**
     * Update the specified chapter
     */
    public function update(Request $request, Chapter $chapter)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'president_id' => 'nullable|exists:alumnis,id',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'meeting_location' => 'nullable|string|max:500',
            'whatsapp_link' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $chapter->update([
                'name' => $request->name,
                'region' => $request->region,
                'city' => $request->city,
                'country' => $request->country,
                'description' => $request->description,
                'president_id' => $request->president_id,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'meeting_location' => $request->meeting_location,
                'whatsapp_link' => $request->whatsapp_link,
                'is_active' => $request->boolean('is_active', true),
                'is_approved' => $request->boolean('is_approved', true),
            ]);

            return redirect()->route('admin.chapters.index')
                ->with('success', 'Chapter updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Approve a pending chapter
     */
    public function approve(Chapter $chapter)
    {
        try {
            $chapter->update(['is_approved' => true]);
            
            return redirect()->back()
                ->with('success', 'Chapter approved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Chapter $chapter)
    {
        try {
            $chapter->update(['is_active' => !$chapter->is_active]);
            
            $status = $chapter->is_active ? 'activated' : 'deactivated';
            return redirect()->back()
                ->with('success', "Chapter {$status} successfully!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified chapter
     */
    public function destroy(Chapter $chapter)
    {
        try {
            // Check if chapter has members
            if ($chapter->members()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete chapter with members. Please reassign members first.');
            }

            $chapter->delete();
            return redirect()->route('admin.chapters.index')
                ->with('success', 'Chapter deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show pending chapter requests
     */
    public function pending()
    {
        $chapters = Chapter::pending()
            ->with('president')
            ->latest()
            ->paginate(15);
        
        return view('admin.chapters.pending', compact('chapters'));
    }
}

