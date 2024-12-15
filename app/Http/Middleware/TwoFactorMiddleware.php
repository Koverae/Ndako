<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Notifications\SendTwoFactorCodeNotification;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::find(auth()->user()->id); // Get the authenticated user

        if (auth()->check()) { // Check if any user is authenticated      

            if($user->last_login_ip !== $request->ip()){
                $request->user()->generateTwoFactorCode();
                $request->user()->notify(new SendTwoFactorCodeNotification());
            }

            // Log the login date
            // $request->user()->last_logged_in_at = now();
            // $request->user()->save();

            // Check if two-factor authentication is required
            if ($user->two_factor_code) {
                // Check if the OTP has expired
                if ($user->two_factor_expires_at < now()) {
                    $user->resetTwoFactorCode(); // Reset the two-factor code
                    auth()->logout(); // Log out the user
                    // Redirect to the login page with a status message
                    return redirect()->intended(Route::subdomainRoute('login'))
                        ->withStatus('Your verification code expired. Please re-login.');
                }
                // Check if the current IP matches the last login IP and if the user is not on a verification route
                if ($user->last_login_ip !== $request->ip() && !$request->is('verify*')) {
                    // Redirect to the OTP verification page
                    return redirect()->intended(Route::subdomainRoute('verify.index'));
                }
            }
        }
        return $next($request);
    }
}
