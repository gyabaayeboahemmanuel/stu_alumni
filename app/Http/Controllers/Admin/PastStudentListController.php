<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YearGroup;
use App\Services\UniversityAlumniService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PastStudentListController extends Controller
{
    public function __construct(private readonly UniversityAlumniService $alumniService)
    {
    }

    public function index()
    {
        $graduationYears = YearGroup::active()
            ->get()
            ->flatMap(function (YearGroup $group) {
                return range($group->start_year, $group->end_year);
            })
            ->unique()
            ->sortDesc()
            ->values();

        // API expects acyear like "2024/2025" (start/end of academic session).
        $academicYears = $graduationYears
            ->map(fn (int $graduationYear) => ($graduationYear - 1) . '/' . $graduationYear)
            ->unique()
            ->values()
            ->all();

        return view('admin.past-students.index', compact('academicYears'));
    }

    public function fetchByAcademicYear(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
        ]);

        $academicYear = $validated['academic_year'];

        try {
            $result = $this->alumniService->fetchAll($academicYear);

            return response()->json([
                'state' => $result['state'] ?? 'success',
                'acyear' => $academicYear,
                'total' => $result['total'],
                'data' => $result['data'],
            ]);
        } catch (\Throwable $e) {
            Log::error('PastStudentList fetch failed', [
                'academic_year' => $academicYear,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to fetch alumni from university API.',
            ], 500);
        }
    }

}

