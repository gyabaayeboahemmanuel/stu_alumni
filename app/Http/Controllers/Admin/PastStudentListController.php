<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YearGroup;
use App\Services\UniversityAlumniService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PastStudentListController extends Controller
{
    private const PER_PAGE = 500;

    private const CACHE_TTL_SECONDS = 3600;

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
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:' . self::PER_PAGE],
        ]);

        $academicYear = $validated['academic_year'];
        $page = (int) ($validated['page'] ?? 1);
        $perPage = (int) ($validated['per_page'] ?? self::PER_PAGE);
        $refresh = $request->boolean('refresh');
        $cacheKey = 'past_students:' . str_replace('/', '-', $academicYear);

        try {
            $cached = $refresh ? null : Cache::get($cacheKey);
            $universityDebug = null;
            $fromCache = is_array($cached);

            if (!$fromCache) {
                $result = $this->alumniService->fetchAll($academicYear);

                if (!$result['success']) {
                    return response()->json([
                        'error' => $result['message'] ?? 'Failed to fetch alumni from university API.',
                        'debug' => $result['debug'] ?? null,
                    ], 502);
                }

                $cached = [
                    'state' => $result['state'] ?? 'success',
                    'message' => $result['message'],
                    'data' => $result['data'],
                    'total' => $result['total'],
                ];
                $universityDebug = $result['debug'] ?? null;

                Cache::put($cacheKey, $cached, self::CACHE_TTL_SECONDS);
            }

            $allRecords = $cached['data'] ?? [];
            $total = (int) ($cached['total'] ?? count($allRecords));
            $lastPage = max(1, (int) ceil($total / $perPage));
            $page = min($page, $lastPage);
            $offset = ($page - 1) * $perPage;
            $pageData = array_slice($allRecords, $offset, $perPage);

            $debug = [
                'from_cache' => $fromCache,
                'app_request' => [
                    'method' => 'GET',
                    'url' => $request->fullUrl(),
                    'query' => $request->query(),
                ],
            ];

            if ($universityDebug) {
                $debug['university'] = $universityDebug;
            }

            return response()->json([
                'state' => $cached['state'] ?? 'success',
                'acyear' => $academicYear,
                'total' => $total,
                'message' => $total === 0 ? ($cached['message'] ?? 'No alumni found for this academic year.') : null,
                'data' => $pageData,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'last_page' => $lastPage,
                    'from' => $total > 0 ? $offset + 1 : 0,
                    'to' => min($offset + $perPage, $total),
                ],
                'debug' => config('app.debug') ? $debug : null,
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

