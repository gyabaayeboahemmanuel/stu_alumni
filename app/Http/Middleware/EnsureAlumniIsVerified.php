<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAlumniIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Allow admin users to bypass alumni verification
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user has alumni record and it's verified
        if (!$user->alumni || $user->alumni->verification_status !== 'verified') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your alumni account is not verified. Please wait for verification or contact support.'
                ], 403);
            }

            return redirect()->route('alumni.dashboard')
                ->with('error', 'Your alumni account is pending verification. You will be notified once verified.');
        }

        return $next($request);
    }
}
