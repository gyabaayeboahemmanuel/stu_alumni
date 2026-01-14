<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    /**
     * Show the donation form
     */
    public function create()
    {
        return view('donations.create');
    }

    /**
     * Store a new donation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:cash,in_kind',
            'description' => 'required_if:type,in_kind|nullable|string|max:1000',
            'items' => 'required_if:type,in_kind|nullable|string|max:500',
            'country' => 'required_if:type,in_kind|nullable|string|max:100',
            'city' => 'required_if:type,in_kind|nullable|string|max:100',
            'contact' => 'required_if:type,in_kind|nullable|string|max:100',
        ], [
            'type.required' => 'Please select a donation type.',
            'type.in' => 'Invalid donation type selected.',
            'description.required_if' => 'Please provide a description for your in-kind donation.',
            'items.required_if' => 'Please list the items you are donating.',
            'country.required_if' => 'Please provide your country.',
            'city.required_if' => 'Please provide your city.',
            'contact.required_if' => 'Please provide your contact information.',
        ]);

        try {
            $donation = new Donation();
            $donation->type = $validated['type'];
            
            if (Auth::check()) {
                $donation->user_id = Auth::id();
                if (Auth::user()->alumni) {
                    $donation->alumni_id = Auth::user()->alumni->id;
                }
            }

            if ($validated['type'] === 'in_kind') {
                $donation->description = $validated['description'];
                $donation->items = $validated['items'];
                $donation->country = $validated['country'];
                $donation->city = $validated['city'];
                $donation->contact = $validated['contact'];
            }

            $donation->status = 'pending';
            $donation->save();

            if ($validated['type'] === 'cash') {
                // Redirect to payment gateway
                return redirect('https://stu.edu.gh/stupay/');
            } else {
                return redirect()->route('donations.create')
                    ->with('success', 'Thank you for your in-kind donation! We will review and contact you soon.');
            }
        } catch (\Exception $e) {
            Log::error('Donation submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit donation. Please try again.');
        }
    }

    /**
     * Display in-kind donations for admin
     */
    public function index(Request $request)
    {
        $query = Donation::inKind()->with(['user', 'alumni']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $donations = $query->latest()->paginate(20);

        return view('admin.donations.index', compact('donations'));
    }

    /**
     * Show a specific donation
     */
    public function show(Donation $donation)
    {
        $donation->load(['user', 'alumni']);
        return view('admin.donations.show', compact('donation'));
    }

    /**
     * Update donation status
     */
    public function updateStatus(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $donation->status = $validated['status'];
        $donation->admin_notes = $validated['admin_notes'] ?? null;
        
        if ($validated['status'] === 'approved' || $validated['status'] === 'rejected') {
            $donation->processed_at = now();
        }

        $donation->save();

        return redirect()->back()->with('success', 'Donation status updated successfully!');
    }
}
