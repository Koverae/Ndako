<?php

namespace App\Http\Middleware;

use App\Models\Team\Team;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeamHasSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = User::find(Auth::user()->id);
        $team = Team::find($user->company->team_id);

        if (!$team) {
            abort(403, 'No team found.');
        }

        $trialEndsAt = $team->trial_ends_at;
        $hasActiveSubscription = $team->subscription('main')->isActive();
        $msg = "Your trial has expired. Please subscribe.";


        if ($team->subscription('main')->ends_at && now()->greaterThan($team->subscription('main')->ends_at)) {
            return response()->view('errors.sub-expired', [], 403);
        }

        if ($team->subscription('main')->hasEndedTrial() && !$team->subscription('main')->isActive()) {
            return response()->view('errors.trial-expired', [], 403);
        }

        return $next($request);
    }
}
