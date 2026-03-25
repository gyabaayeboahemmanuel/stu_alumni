<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YearGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PastStudentListController extends Controller
{
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

        $endpoint = env('UNIVERSITY_ALUMNI_BY_ACADEMIC_YEAR_ENDPOINT');
        $paramName = env('UNIVERSITY_ALUMNI_BY_ACADEMIC_YEAR_PARAM', 'academic_year');

        if (!$endpoint) {
            return response()->json([
                'error' => 'UNIVERSITY_ALUMNI_BY_ACADEMIC_YEAR_ENDPOINT is not configured.',
            ], 500);
        }

        try {
            $response = Http::timeout(30)->acceptJson()->get($endpoint, [
                $paramName => $validated['academic_year'],
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'error' => 'University API request failed.',
                    'status' => $response->status(),
                    'body' => $response->body(),
                ], 502);
            }

            return response()->json($response->json());
        } catch (\Throwable $e) {
            Log::error('PastStudentList fetch failed', [
                'endpoint' => $endpoint,
                'academic_year' => $validated['academic_year'],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to fetch alumni from university API.',
            ], 500);
        }
    }
}

