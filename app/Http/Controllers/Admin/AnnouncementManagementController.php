<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with(['author', 'category']);

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            } elseif ($request->status === 'pinned') {
                $query->where('is_pinned', true);
            }
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $announcements = $query->latest()->paginate(15);
        $categories = AnnouncementCategory::active()->get();

        return view('admin.announcements.index', compact('announcements', 'categories'));
    }

    public function create()
    {
        $categories = AnnouncementCategory::active()->get();
        return view('admin.announcements.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:announcement_categories,id',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_published' => 'boolean',
            'is_pinned' => 'boolean',
            'published_at' => 'nullable|date',
            'visibility' => 'required|in:public,alumni',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        // Generate slug
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Announcement::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $announcementData = [
            'slug' => $slug,
            'author_id' => auth()->id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 150),
            'is_published' => $validated['is_published'] ?? false,
            'is_pinned' => $validated['is_pinned'] ?? false,
            'published_at' => $validated['published_at'] ?? ($validated['is_published'] ? now() : null),
            'visibility' => $validated['visibility'],
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? Str::limit(strip_tags($validated['content']), 160),
        ];

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('announcements', 'public');
            $announcementData['featured_image'] = $imagePath;
        }

        Announcement::create($announcementData);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    public function edit(Announcement $announcement)
    {
        $categories = AnnouncementCategory::active()->get();
        return view('admin.announcements.edit', compact('announcement', 'categories'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:announcement_categories,id',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_published' => 'boolean',
            'is_pinned' => 'boolean',
            'published_at' => 'nullable|date',
            'visibility' => 'required|in:public,alumni',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $updateData = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 150),
            'is_published' => $validated['is_published'] ?? false,
            'is_pinned' => $validated['is_pinned'] ?? false,
            'published_at' => $validated['published_at'] ?? ($validated['is_published'] ? now() : null),
            'visibility' => $validated['visibility'],
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? Str::limit(strip_tags($validated['content']), 160),
        ];

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($announcement->featured_image) {
                Storage::disk('public')->delete($announcement->featured_image);
            }
            
            $imagePath = $request->file('featured_image')->store('announcements', 'public');
            $updateData['featured_image'] = $imagePath;
        }

        $announcement->update($updateData);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    public function destroy(Announcement $announcement)
    {
        // Delete featured image if exists
        if ($announcement->featured_image) {
            Storage::disk('public')->delete($announcement->featured_image);
        }

        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }

    public function togglePublish(Announcement $announcement)
    {
        $announcement->update([
            'is_published' => !$announcement->is_published,
            'published_at' => $announcement->is_published ? null : now(),
        ]);

        $status = $announcement->is_published ? 'published' : 'unpublished';
        return redirect()->back()->with('success', "Announcement {$status} successfully!");
    }

    public function togglePin(Announcement $announcement)
    {
        $announcement->update([
            'is_pinned' => !$announcement->is_pinned,
        ]);

        $status = $announcement->is_pinned ? 'pinned' : 'unpinned';
        return redirect()->back()->with('success', "Announcement {$status} successfully!");
    }
}
