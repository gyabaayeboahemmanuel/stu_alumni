<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Business;
use App\Models\YearGroup;
use App\Http\Resources\AlumniResource;
use App\Http\Requests\UpdateAlumniProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AlumniDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $alumni = Auth::user()->alumni;
        $recentAnnouncements = Announcement::published()
            ->alumniOnly()
            ->latest()
            ->take(5)
            ->get();
            
        $upcomingEvents = Event::published()
            ->upcoming()
            ->latest('event_date')
            ->take(5)
            ->get();

        $myBusinesses = $alumni->businesses()->count();
        $myEvents = $alumni->eventRegistrations()->count();
        
        // Get year groups for this alumni's graduation year
        $yearGroups = YearGroup::forGraduationYear($alumni->year_of_completion);
        
        // Handle modal dismissal
        if ($request->has('dismiss_profile_reminder')) {
            session(['profile_reminder_dismissed' => true]);
        }
        
        // Check if professional information is incomplete
        $showProfileReminder = $alumni->hasIncompleteProfessionalInfo() && 
                              !session('profile_reminder_dismissed', false);

        return view('alumni.dashboard', compact(
            'alumni',
            'recentAnnouncements',
            'upcomingEvents',
            'yearGroups',
            'myBusinesses',
            'myEvents',
            'showProfileReminder'
        ));
    }

    public function profile()
    {
        $alumni = Auth::user()->alumni;
        return view('alumni.profile', compact('alumni'));
    }

    // API endpoint for getting alumni profile
    public function show(Alumni $alumni)
    {
        // Authorization check - users can only view their own profile via API
        if (Auth::id() !== $alumni->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return new AlumniResource($alumni->load('user', 'businesses'));
    }

    // API endpoint for updating alumni profile
    public function update(UpdateAlumniProfileRequest $request, Alumni $alumni)
    {
        // Authorization check - users can only update their own profile
        if (Auth::id() !== $alumni->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $alumni->update($request->validated());
        
        // If this is an API request, return JSON response
        if ($request->expectsJson()) {
            return new AlumniResource($alumni->load('user', 'businesses'));
        }

        // If this is a web request, redirect back with success message
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updateProfile(Request $request)
    {
        $alumni = Auth::user()->alumni;

        $validated = $request->validate([
            'phone' => 'required|string|max:15',
            'current_employer' => 'nullable|string|max:200',
            'job_title' => 'nullable|string|max:200',
            'industry' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_address' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:200',
            'linkedin' => 'nullable|url|max:200',
            'twitter' => 'nullable|url|max:200',
            'facebook' => 'nullable|url|max:200',
            'is_visible_in_directory' => 'boolean',
        ]);

        $alumni->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $alumni = Auth::user()->alumni;
        
        // Delete old photo if exists
        if ($alumni->profile_photo_path) {
            Storage::disk('public')->delete($alumni->profile_photo_path);
        }

        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $alumni->update(['profile_photo_path' => $path]);

        return redirect()->back()->with('success', 'Profile photo updated successfully!');
    }

    public function announcements()
    {
        $announcements = Announcement::published()
            ->alumniOnly()
            ->latest()
            ->paginate(10);

        return view('alumni.announcements', compact('announcements'));
    }

    public function showAnnouncement($slug)
    {
        $announcement = Announcement::published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('alumni.announcement-show', compact('announcement'));
    }

    public function events()
    {
        $events = Event::published()
            ->upcoming()
            ->latest('event_date')
            ->paginate(10);

        $myRegistrations = Auth::user()->alumni->eventRegistrations()
            ->with('event')
            ->get()
            ->keyBy('event_id');

        return view('alumni.events', compact('events', 'myRegistrations'));
    }

    public function registerForEvent(Request $request, Event $event)
    {
        $alumni = Auth::user()->alumni;

        // Check if already registered
        if ($alumni->eventRegistrations()->where('event_id', $event->id)->exists()) {
            return redirect()->back()->with('error', 'You are already registered for this event.');
        }

        // Check registration deadline
        if (!$event->isRegistrationOpen()) {
            return redirect()->back()->with('error', 'Registration for this event has closed.');
        }

        // Check available spaces
        if ($event->max_attendees && $event->available_spaces <= 0) {
            return redirect()->back()->with('error', 'This event is fully booked.');
        }

        // Create registration
        $registration = $alumni->eventRegistrations()->create([
            'event_id' => $event->id,
            'status' => $event->requires_approval ? 'pending' : 'confirmed',
            'notes' => $request->notes,
        ]);

        $message = $event->requires_approval 
            ? 'Registration submitted for approval. You will be notified once confirmed.'
            : 'Successfully registered for the event!';

        return redirect()->back()->with('success', $message);
    }

    public function myRegistrations()
    {
        $registrations = Auth::user()->alumni->eventRegistrations()
            ->with('event')
            ->latest()
            ->paginate(10);

        return view('alumni.my-registrations', compact('registrations'));
    }

    // API endpoint for getting alumni's event registrations
    public function getMyRegistrations(Request $request)
    {
        $alumni = Auth::user()->alumni;
        $registrations = $alumni->eventRegistrations()
            ->with('event')
            ->latest()
            ->paginate(10);

        return \App\Http\Resources\EventRegistrationResource::collection($registrations);
    }
}