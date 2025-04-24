<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Get the authenticated user
        $user = Auth::user();

        $company = $user->company;

        $allowedLimit = $company->team->subscription('main')->features()->where('tag', $feature)->value('value');
        $currentCount = $company->{$feature}()->count(); // Dynamic model name

        if ($currentCount >= $allowedLimit) {
            $title = inverseSlug($feature)." limit has been reached";
            $message = "You have reached your $allowedLimit $feature limit. Upgrade your plan to add more.";
            return response()->view('errors.feature-missing', compact('feature', 'title', 'message'), 403);
        }

        return $next($request);
    }
}
