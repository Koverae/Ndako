<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureAccess
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

        $team = $user->company->team;

        // Ensure the user is logged in and has access to the feature (For APIs)
        if (!$team || !hasFeature($team, $feature)) {
            $title = inverseSlug($feature)." is not available on your plan.";
            $message = "The feature ".inverseSlug($feature)." is not available on your plan.";
            return response()->view('errors.feature-missing', compact('feature', 'title', 'message'), 403);
        }

        // // Ensure the user is logged in and has access to the feature (For APIs)
        // if (!$team || !hasFeature($team, $feature)) {

        //     return response()->json(['message' => "The feature ".inverseSlug($feature)." is not available on your plan."], 403);
        // }


        return $next($request);
    }
}
