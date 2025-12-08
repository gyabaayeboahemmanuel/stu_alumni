<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Business;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        // Basic statistics
        $stats = [
            'total_alumni' => Alumni::count(),
            'verified_alumni' => Alumni::verified()->count(),
            'pending_alumni' => Alumni::pending()->count(),
            'total_businesses' => Business::count(),
            'verified_businesses' => Business::verified()->count(),
            'total_events' => Event::count(),
            'upcoming_events' => Event::upcoming()->count(),
            'total_announcements' => Announcement::count(),
            'published_announcements' => Announcement::published()->count(),
        ];

        // Alumni by year
        $alumniByYear = Alumni::verified()
            ->select('year_of_completion', DB::raw('COUNT(*) as count'))
            ->groupBy('year_of_completion')
            ->orderBy('year_of_completion')
            ->get();

        // Alumni by programme
        $alumniByProgramme = Alumni::verified()
            ->select('programme', DB::raw('COUNT(*) as count'))
            ->groupBy('programme')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // Alumni by country
        $alumniByCountry = Alumni::verified()
            ->whereNotNull('country')
            ->select('country', DB::raw('COUNT(*) as count'))
            ->groupBy('country')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // Recent registrations
        $recentRegistrations = Alumni::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Business by industry
        $businessByIndustry = Business::verified()
            ->active()
            ->select('industry', DB::raw('COUNT(*) as count'))
            ->groupBy('industry')
            ->orderBy('count', 'desc')
            ->get();

        return view('admin.reports.index', compact(
            'stats',
            'alumniByYear',
            'alumniByProgramme',
            'alumniByCountry',
            'recentRegistrations',
            'businessByIndustry'
        ));
    }

    public function alumniReport(Request $request)
    {
        $query = Alumni::with('user');

        // Apply filters
        if ($request->has('verification_status') && $request->verification_status) {
            $query->where('verification_status', $request->verification_status);
        }

        if ($request->has('year_of_completion') && $request->year_of_completion) {
            $query->where('year_of_completion', $request->year_of_completion);
        }

        if ($request->has('programme') && $request->programme) {
            $query->where('programme', 'LIKE', "%{$request->programme}%");
        }

        if ($request->has('country') && $request->country) {
            $query->where('country', $request->country);
        }

        $alumni = $query->latest()->paginate(20);

        $years = Alumni::select('year_of_completion')
            ->distinct()
            ->orderBy('year_of_completion', 'desc')
            ->pluck('year_of_completion');

        $programmes = Alumni::select('programme')
            ->distinct()
            ->orderBy('programme')
            ->pluck('programme');

        $countries = Alumni::whereNotNull('country')
            ->select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        return view('admin.reports.alumni', compact('alumni', 'years', 'programmes', 'countries'));
    }

    public function businessReport(Request $request)
    {
        $query = Business::with('alumni.user');

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('industry') && $request->industry) {
            $query->where('industry', $request->industry);
        }

        if ($request->has('is_verified') && $request->is_verified !== '') {
            $query->where('is_verified', $request->is_verified);
        }

        if ($request->has('is_featured') && $request->is_featured !== '') {
            $query->where('is_featured', $request->is_featured);
        }

        $businesses = $query->latest()->paginate(20);

        $industries = Business::select('industry')
            ->distinct()
            ->orderBy('industry')
            ->pluck('industry');

        return view('admin.reports.business', compact('businesses', 'industries'));
    }

    public function eventsReport(Request $request)
    {
        $query = Event::withCount('registrations');

        // Apply filters
        if ($request->has('status') && $request->status) {
            if ($request->status === 'upcoming') {
                $query->upcoming();
            } elseif ($request->status === 'past') {
                $query->past();
            }
        }

        if ($request->has('event_type') && $request->event_type) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->has('is_published') && $request->is_published !== '') {
            $query->where('is_published', $request->is_published);
        }

        $events = $query->latest()->paginate(20);

        return view('admin.reports.events', compact('events'));
    }

    public function exportAlumni(Request $request)
    {
        // This would generate a CSV/Excel export
        // For now, we'll return a JSON response
        $query = Alumni::with('user');

        if ($request->has('verification_status') && $request->verification_status) {
            $query->where('verification_status', $request->verification_status);
        }

        $alumni = $query->get();

        return response()->json([
            'message' => 'Export functionality will be implemented soon',
            'record_count' => $alumni->count(),
        ]);
    }

    public function systemStats()
    {
        // System usage statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'recent_logins' => User::where('last_login_at', '>=', now()->subDays(7))->count(),
            'storage_usage' => $this->getStorageUsage(),
        ];

        // Registration trends (last 30 days)
        $registrationTrends = Alumni::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return response()->json([
            'stats' => $stats,
            'registration_trends' => $registrationTrends,
        ]);
    }

    private function getStorageUsage()
    {
        $directories = [
            'profile-photos',
            'business-logos',
            'proofs',
            'announcements',
            'events',
        ];

        $totalSize = 0;

        foreach ($directories as $directory) {
            $path = storage_path("app/public/{$directory}");
            if (file_exists($path)) {
                $totalSize += $this->getDirectorySize($path);
            }
        }

        return [
            'total_mb' => round($totalSize / 1024 / 1024, 2),
            'total_gb' => round($totalSize / 1024 / 1024 / 1024, 2),
        ];
    }

    private function getDirectorySize($path)
    {
        $size = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        return $size;
    }
}
