<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YearGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PastStudentListController extends Controller
{
    private const DEFAULT_ALUMNI_ENDPOINT = 'https://www.stu.edu.gh/identity/getAlumni';

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

        $endpoint = env('UNIVERSITY_ALUMNI_BY_ACADEMIC_YEAR_ENDPOINT', self::DEFAULT_ALUMNI_ENDPOINT);
        $academicYear = $this->toApiAcademicYear((int) $validated['academic_year']);

        if (!$endpoint) {
            return response()->json([
                'error' => 'UNIVERSITY_ALUMNI_BY_ACADEMIC_YEAR_ENDPOINT is not configured.',
            ], 500);
        }

        try {
            $page = 1;
            $allAlumni = [];
            $lastPayload = null;

            do {
                $response = Http::timeout(60)
                    ->acceptJson()
                    ->asJson()
                    ->post($endpoint, [
                        'acyear' => $academicYear,
                        'page' => $page,
                        'limit' => 'all',
                    ]);

                if (!$response->successful()) {
                    return response()->json([
                        'error' => 'University API request failed.',
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ], 502);
                }

                $payload = $response->json();
                $lastPayload = is_array($payload) ? $payload : [];
                $pageData = $lastPayload['data'] ?? [];

                if (is_array($pageData)) {
                    $allAlumni = array_merge($allAlumni, $pageData);
                }

                $totalPages = max((int) ($lastPayload['total_pages'] ?? 1), 1);
                $page++;
            } while ($page <= $totalPages);

            return response()->json([
                'state' => $lastPayload['state'] ?? 'success',
                'acyear' => $academicYear,
                'total' => count($allAlumni),
                'data' => $allAlumni,
            ]);
        } catch (\Throwable $e) {
            Log::error('PastStudentList fetch failed', [
                'endpoint' => $endpoint,
                'academic_year' => $academicYear,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to fetch alumni from university API.',
            ], 500);
        }
    }

    private function toApiAcademicYear(int $graduationYear): string
    {
        $startYear = $graduationYear - 1;

        return $startYear . '/' . $graduationYear;
    }
}

