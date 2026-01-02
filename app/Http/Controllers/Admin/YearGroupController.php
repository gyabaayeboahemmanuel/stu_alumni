<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YearGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YearGroupController extends Controller
{
    /**
     * Display a listing of year groups
     */
    public function index()
    {
        $yearGroups = YearGroup::orderBy('start_year', 'desc')->paginate(15);
        return view('admin.year-groups.index', compact('yearGroups'));
    }

    /**
     * Show the form for creating a new year group
     */
    public function create()
    {
        return view('admin.year-groups.create');
    }

    /**
     * Store a newly created year group
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_year' => 'required|integer|min:1968|max:2030',
            'end_year' => 'required|integer|min:1968|max:2030|gte:start_year',
            'description' => 'nullable|string|max:500',
            'whatsapp_link' => 'nullable|url|max:500',
            'telegram_link' => 'nullable|url|max:500',
            'gekychat_link' => 'nullable|url|max:500',
            'is_active' => 'boolean',
        ], [
            'end_year.gte' => 'End year must be greater than or equal to start year.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please check the form for errors.');
        }

        try {
            // Check for overlapping year groups
            $overlap = YearGroup::where(function ($query) use ($request) {
                $query->whereBetween('start_year', [$request->start_year, $request->end_year])
                    ->orWhereBetween('end_year', [$request->start_year, $request->end_year])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_year', '<=', $request->start_year)
                          ->where('end_year', '>=', $request->end_year);
                    });
            })->exists();

            if ($overlap) {
                return redirect()->back()
                    ->with('warning', 'Note: This year range overlaps with an existing year group. This is allowed but alumni may see multiple groups.')
                    ->withInput();
            }

            YearGroup::create([
                'name' => $request->name,
                'start_year' => $request->start_year,
                'end_year' => $request->end_year,
                'description' => $request->description,
                'whatsapp_link' => $request->whatsapp_link,
                'telegram_link' => $request->telegram_link,
                'gekychat_link' => $request->gekychat_link,
                'is_active' => $request->boolean('is_active', true),
            ]);

            return redirect()->route('admin.year-groups.index')
                ->with('success', 'Year group created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing a year group
     */
    public function edit(YearGroup $yearGroup)
    {
        return view('admin.year-groups.edit', compact('yearGroup'));
    }

    /**
     * Update the specified year group
     */
    public function update(Request $request, YearGroup $yearGroup)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_year' => 'required|integer|min:1968|max:2030',
            'end_year' => 'required|integer|min:1968|max:2030|gte:start_year',
            'description' => 'nullable|string|max:500',
            'whatsapp_link' => 'nullable|url|max:500',
            'telegram_link' => 'nullable|url|max:500',
            'gekychat_link' => 'nullable|url|max:500',
            'is_active' => 'boolean',
        ], [
            'end_year.gte' => 'End year must be greater than or equal to start year.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please check the form for errors.');
        }

        try {
            $yearGroup->update([
                'name' => $request->name,
                'start_year' => $request->start_year,
                'end_year' => $request->end_year,
                'description' => $request->description,
                'whatsapp_link' => $request->whatsapp_link,
                'telegram_link' => $request->telegram_link,
                'gekychat_link' => $request->gekychat_link,
                'is_active' => $request->boolean('is_active', true),
            ]);

            return redirect()->route('admin.year-groups.index')
                ->with('success', 'Year group updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified year group
     */
    public function destroy(YearGroup $yearGroup)
    {
        try {
            $yearGroup->delete();
            return redirect()->route('admin.year-groups.index')
                ->with('success', 'Year group deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(YearGroup $yearGroup)
    {
        try {
            $yearGroup->update(['is_active' => !$yearGroup->is_active]);
            
            $status = $yearGroup->is_active ? 'activated' : 'deactivated';
            return redirect()->back()
                ->with('success', "Year group {$status} successfully!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}

