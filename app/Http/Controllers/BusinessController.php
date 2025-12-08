<?php
namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::verified()
            ->active()
            ->with('alumni')
            ->latest()
            ->paginate(12);

        $featuredBusinesses = Business::verified()
            ->active()
            ->featured()
            ->with('alumni')
            ->latest()
            ->take(6)
            ->get();

        return view('businesses.index', compact('businesses', 'featuredBusinesses'));
    }

    public function show($slug)
    {
        $business = Business::verified()
            ->active()
            ->with('alumni')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('businesses.show', compact('business'));
    }

    public function myBusinesses()
    {
        $businesses = Auth::user()->alumni->businesses()
            ->latest()
            ->paginate(10);

        return view('businesses.my-businesses', compact('businesses'));
    }

    public function create()
    {
        return view('businesses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'required|string|max:1000',
            'industry' => 'required|string|max:100',
            'website' => 'nullable|url|max:200',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $alumni = Auth::user()->alumni;

        // Enhanced slug generation with better duplicate handling
        $slug = $this->generateUniqueBusinessSlug($validated['name']);

        $businessData = [
            'alumni_id' => $alumni->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'industry' => $validated['industry'],
            'website' => $validated['website'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'status' => 'pending',
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('business-logos', 'public');
            $businessData['logo_path'] = $logoPath;
        }

        Business::create($businessData);

        return redirect()->route('alumni.businesses.my-businesses')
            ->with('success', 'Business listing submitted for approval. You will be notified once verified.');
    }

    public function edit(Business $business)
    {
        // Check ownership
        if ($business->alumni_id !== Auth::user()->alumni->id) {
            abort(403);
        }

        return view('businesses.edit', compact('business'));
    }

    public function update(Request $request, Business $business)
    {
        // Check ownership
        if ($business->alumni_id !== Auth::user()->alumni->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'required|string|max:1000',
            'industry' => 'required|string|max:100',
            'website' => 'nullable|url|max:200',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'industry' => $validated['industry'],
            'website' => $validated['website'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'status' => 'pending', // Reset to pending for admin review
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($business->logo_path) {
                \Storage::disk('public')->delete($business->logo_path);
            }
            
            $logoPath = $request->file('logo')->store('business-logos', 'public');
            $updateData['logo_path'] = $logoPath;
        }

        $business->update($updateData);

        return redirect()->route('alumni.businesses.my-businesses')
            ->with('success', 'Business listing updated and submitted for re-approval.');
    }

    public function destroy(Business $business)
    {
        // Check ownership
        if ($business->alumni_id !== Auth::user()->alumni->id) {
            abort(403);
        }

        // Delete logo if exists
        if ($business->logo_path) {
            \Storage::disk('public')->delete($business->logo_path);
        }

        $business->delete();

        return redirect()->route('alumni.businesses.my-businesses')
            ->with('success', 'Business listing deleted successfully.');
    }

    /**
     * Generate a unique slug for business names
     * Handles duplicates by appending incremental numbers
     */
    private function generateUniqueBusinessSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        // Check for existing slugs and increment counter until unique
        while (Business::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
            
            // Safety limit to prevent infinite loops
            if ($counter > 100) {
                $slug = $baseSlug . '-' . uniqid();
                break;
            }
        }

        return $slug;
    }
}