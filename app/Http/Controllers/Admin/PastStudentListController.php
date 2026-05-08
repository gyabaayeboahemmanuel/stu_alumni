<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YearGroup;
use App\Services\UniversityAlumniService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PastStudentListController extends Controller
{
    private const DEFAULT_ALUMNI_ENDPOINT = 'https://www.stu.edu.gh/identity/getAlumni';

    public function __construct(private readonly UniversityAlumniService $alumniService)
    {
    }

    public function index()
    {
        // Build a unique list of graduation years from active year groups.
        $years = YearGroup::active()
            ->get()
            ->flatMap(function (YearGroup $group) {
                // Example: start_year=2010, end_year=2014 -> 2010..2014
                return range($group->start_year, $group->end_year);
            })
            ->unique()
            ->sortDesc()
            ->values()
            ->all();

        return view('admin.past-students.index', compact('years'));
    }

    public function fetchByAcademicYear(Request $request)
    {
        $validated = $request->validate([
            // Interpreting "academic year" as a single graduation year.
            'academic_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        $requestedYear = (int) $validated['academic_year'];
        $academicYearCandidates = $this->toApiAcademicYearCandidates($requestedYear);

        try {
            $attempts = [];
            foreach ($academicYearCandidates as $candidate) {
                $result = $this->alumniService->fetchAll($candidate);
                $attempts[] = [
                    'acyear' => $candidate,
                    'total' => $result['total'],
                    'state' => $result['state'] ?? null,
                ];

                if ($result['total'] > 0) {
                    return response()->json([
                        'state' => $result['state'] ?? 'success',
                        'requested_year' => $requestedYear,
                        'acyear' => $candidate,
                        'attempts' => $attempts,
                        'total' => $result['total'],
                        'data' => $result['data'],
                    ]);
                }
            }

            // If both candidates produced zero results, return the last attempt (plus diagnostics).
            $last = end($attempts) ?: ['acyear' => $academicYearCandidates[0] ?? null];
            $fallback = $this->alumniService->fetchAll((string) ($last['acyear'] ?? ''));

            return response()->json([
                'state' => $fallback['state'] ?? 'success',
                'requested_year' => $requestedYear,
                'acyear' => $last['acyear'] ?? null,
                'attempts' => $attempts,
                'total' => $fallback['total'],
                'data' => $fallback['data'],
            ]);
        } catch (\Throwable $e) {
            Log::error('PastStudentList fetch failed', [
                'academic_year_candidates' => $academicYearCandidates,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to fetch alumni from university API.',
            ], 500);
        }
    }

    private function toApiAcademicYearCandidates(int $selectedYear): array
    {
        // Historically "academic year" could be interpreted as:
        // - completion year (e.g., 2019 => 2018/2019)
        // - start year (e.g., 2019 => 2019/2020)
        // We try both to avoid empty results due to mapping mismatch.
        $a = ($selectedYear - 1) . '/' . $selectedYear;
        $b = $selectedYear . '/' . ($selectedYear + 1);

        return array_values(array_unique([$a, $b]));
    }

}

