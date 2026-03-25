<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public const IMPERSONATOR_SESSION_KEY = 'stu_alumni_impersonator_id';

    /**
     * Start impersonation as a non-admin alumni account.
     */
    public function impersonate(Request $request, User $user)
    {
        $currentUser = Auth::user();
        if (!$currentUser || !$currentUser->isAdmin()) {
            abort(403, 'Only admins can impersonate users.');
        }

        if ($user->id === $currentUser->id) {
            return redirect()->back()->with('warning', 'You cannot impersonate yourself.');
        }

        // Do not allow chaining into admin roles.
        if ($user->isAdmin()) {
            return redirect()->back()->with('warning', 'You cannot impersonate an admin account.');
        }

        session([self::IMPERSONATOR_SESSION_KEY => $currentUser->id]);
        Auth::login($user);

        return redirect()->route('alumni.dashboard')
            ->with('info', 'Impersonation started. Use "Leave impersonation" to return.');
    }

    /**
     * Stop impersonation and restore the original admin session.
     */
    public function leave()
    {
        $adminId = session(self::IMPERSONATOR_SESSION_KEY);
        if (!$adminId) {
            return redirect()->back()->with('warning', 'You are not currently impersonating anyone.');
        }

        $admin = User::find($adminId);
        if (!$admin) {
            session()->forget(self::IMPERSONATOR_SESSION_KEY);
            return redirect()->route('login')->with('error', 'Your original admin session expired. Please login again.');
        }

        session()->forget(self::IMPERSONATOR_SESSION_KEY);
        Auth::login($admin);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Impersonation ended successfully.');
    }
}

