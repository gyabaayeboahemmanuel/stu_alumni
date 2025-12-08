<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_alumni' => Alumni::count(),
            'verified_alumni' => Alumni::verified()->count(),
            'pending_alumni' => Alumni::pending()->count(),
            'total_businesses' => Business::count(),
            'verified_businesses' => Business::verified()->count(),
            'total_events' => Event::count(),
            'upcoming_events' => Event::upcoming()->count(),
            'total_announcements' => Announcement::count(),
        ];

        // Recent pending alumni for manual verification
        $pendingAlumni = Alumni::pending()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        // Recent activities (simplified - would use audit logs in real implementation)
        $recentAnnouncements = Announcement::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingAlumni', 'recentAnnouncements'));
    }

    public function alumniStats()
    {
        // Alumni by year of completion
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

        return response()->json([
            'by_year' => $alumniByYear,
            'by_programme' => $alumniByProgramme,
            'by_country' => $alumniByCountry,
        ]);
    }
}
