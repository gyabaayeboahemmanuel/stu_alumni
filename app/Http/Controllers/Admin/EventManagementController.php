<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::withCount('registrations');

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('venue', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            if ($request->status === 'upcoming') {
                $query->upcoming();
            } elseif ($request->status === 'past') {
                $query->past();
            } elseif ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Type filter
        if ($request->has('type') && $request->type) {
            $query->where('event_type', $request->type);
        }

        $events = $query->latest()->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after:now',
            'event_end_date' => 'nullable|date|after:event_date',
            'venue' => 'nullable|string|max:255',
            'online_link' => 'nullable|url|max:255',
            'event_type' => 'required|in:physical,online,hybrid',
            'max_attendees' => 'nullable|integer|min:1',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'registration_deadline' => 'nullable|date|before:event_date',
            'requires_approval' => 'boolean',
            'price' => 'nullable|numeric|min:0',
        ]);

        // Generate slug
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Event::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $eventData = [
            'slug' => $slug,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'event_date' => $validated['event_date'],
            'event_end_date' => $validated['event_end_date'],
            'venue' => $validated['venue'],
            'online_link' => $validated['online_link'],
            'event_type' => $validated['event_type'],
            'max_attendees' => $validated['max_attendees'],
            'is_published' => $validated['is_published'] ?? false,
            'is_featured' => $validated['is_featured'] ?? false,
            'registration_deadline' => $validated['registration_deadline'],
            'requires_approval' => $validated['requires_approval'] ?? false,
            'price' => $validated['price'] ?? 0,
            'currency' => 'GHS',
        ];

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('events', 'public');
            $eventData['featured_image'] = $imagePath;
        }

        Event::create($eventData);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        $event->load(['registrations.alumni.user']);
        $registrations = $event->registrations()->with('alumni.user')->latest()->paginate(20);
        
        return view('admin.events.show', compact('event', 'registrations'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_end_date' => 'nullable|date|after:event_date',
            'venue' => 'nullable|string|max:255',
            'online_link' => 'nullable|url|max:255',
            'event_type' => 'required|in:physical,online,hybrid',
            'max_attendees' => 'nullable|integer|min:1',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'registration_deadline' => 'nullable|date|before:event_date',
            'requires_approval' => 'boolean',
            'price' => 'nullable|numeric|min:0',
        ]);

        $updateData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'event_date' => $validated['event_date'],
            'event_end_date' => $validated['event_end_date'],
            'venue' => $validated['venue'],
            'online_link' => $validated['online_link'],
            'event_type' => $validated['event_type'],
            'max_attendees' => $validated['max_attendees'],
            'is_published' => $validated['is_published'] ?? false,
            'is_featured' => $validated['is_featured'] ?? false,
            'registration_deadline' => $validated['registration_deadline'],
            'requires_approval' => $validated['requires_approval'] ?? false,
            'price' => $validated['price'] ?? 0,
        ];

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($event->featured_image) {
                Storage::disk('public')->delete($event->featured_image);
            }
            
            $imagePath = $request->file('featured_image')->store('events', 'public');
            $updateData['featured_image'] = $imagePath;
        }

        $event->update($updateData);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        // Delete featured image if exists
        if ($event->featured_image) {
            Storage::disk('public')->delete($event->featured_image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }

    public function togglePublish(Event $event)
    {
        $event->update([
            'is_published' => !$event->is_published,
        ]);

        $status = $event->is_published ? 'published' : 'unpublished';
        return redirect()->back()->with('success', "Event {$status} successfully!");
    }

    public function toggleFeature(Event $event)
    {
        $event->update([
            'is_featured' => !$event->is_featured,
        ]);

        $status = $event->is_featured ? 'featured' : 'unfeatured';
        return redirect()->back()->with('success', "Event {$status} successfully!");
    }

    public function updateRegistrationStatus(Request $request, EventRegistration $registration)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,waitlisted',
        ]);

        $registration->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Registration status updated successfully!');
    }

    public function markAttendance(EventRegistration $registration)
    {
        $registration->update([
            'attended' => true,
            'attended_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Attendance marked successfully!');
    }

    public function exportRegistrations(Event $event)
    {
        // This would typically generate a CSV export
        // For now, we'll just return a success message
        return redirect()->back()->with('success', 'Export functionality will be implemented soon!');
    }
}
