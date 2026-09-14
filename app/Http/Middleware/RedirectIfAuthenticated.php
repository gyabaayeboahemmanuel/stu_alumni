<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // AJAX/JSON callers expect JSON, not an HTML redirect page
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Already authenticated.',
                    ], 409);
                }

                $user = Auth::guard($guard)->user();

                // Redirect based on user role
                if ($user->isAdmin()) {
                    return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
                }

                return redirect()->intended(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
