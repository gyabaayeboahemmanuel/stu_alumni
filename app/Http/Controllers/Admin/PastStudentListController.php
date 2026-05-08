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
        $requestedYear = (int) $validated['academic_year'];
        $academicYearCandidates = $this->toApiAcademicYearCandidates($requestedYear);

        if (!$endpoint) {
            return response()->json([
                'error' => 'UNIVERSITY_ALUMNI_BY_ACADEMIC_YEAR_ENDPOINT is not configured.',
            ], 500);
        }

        try {
            $attempts = [];
            foreach ($academicYearCandidates as $candidate) {
                $result = $this->fetchAllAlumniForAcademicYear($endpoint, $candidate);
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
            $fallback = $this->fetchAllAlumniForAcademicYear($endpoint, $last['acyear'] ?? '');

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
                'endpoint' => $endpoint,
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

    private function fetchAllAlumniForAcademicYear(string $endpoint, string $academicYear): array
    {
        $page = 1;
        $allAlumni = [];
        $lastPayload = [];

        do {
            $requestBody = [
                'acyear' => $academicYear,
                'page' => $page,
                'limit' => 'all',
            ];

            Log::info('PastStudentList outbound request', [
                'endpoint' => $endpoint,
                'body' => $requestBody,
            ]);

            $response = Http::timeout(60)
                ->acceptJson()
                ->asJson()
                ->post($endpoint, $requestBody);

            if (!$response->successful()) {
                Log::warning('PastStudentList university API failed', [
                    'endpoint' => $endpoint,
                    'acyear' => $academicYear,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'state' => 'error',
                    'total' => 0,
                    'data' => [],
                ];
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

        return [
            'state' => $lastPayload['state'] ?? 'success',
            'total' => count($allAlumni),
            'data' => $allAlumni,
        ];
    }
}

