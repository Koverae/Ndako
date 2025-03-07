<?php

namespace App\Http\Middleware;

use App\Models\Company\Company;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IdentifyKover
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Ensure user is authenticated
            if (!Auth::check()) {
                return redirect()->route('login');
            }

            // Get the authenticated user
            $user = Auth::user();

            // Fetch or cache the user's company
            $company = $this->getCompanyForUser($user->id);

            if (!$company) {
                Log::warning("User {$user->id} has no associated company.");
                return redirect()->route('error')->with('message', 'Company not found.');
            }

            // Store company in session
            session(['current_company' => $company]);

            return $next($request);
        } catch (Exception $e) {
            Log::error('Error in IdentifyKover middleware: ' . $e->getMessage());
            return redirect()->route('getting-started')->with('message', 'Get Started.');
        }
    }

    /**
     * Fetch the company for the authenticated user.
     */
    protected function getCompanyForUser($userId)
    {
        return Cache::remember("user_company_{$userId}", 60, function () use ($userId) {
            return Company::whereHas('users', function ($query) use ($userId) {
                $query->where('id', $userId);
            })->first();
        });
    }
}
