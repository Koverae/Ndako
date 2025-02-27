<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to ensure users complete onboarding before accessing the dashboard.
 */
class EnsureOnboardingCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Ensure user is authenticated and onboarding is not complete
        if ($user && !$user->onboarding_completed) {
            // Store onboarding step in session to resume later
            session(['onboarding_step' => $user->onboarding_step]);
        }

        return $next($request);
    }
}
