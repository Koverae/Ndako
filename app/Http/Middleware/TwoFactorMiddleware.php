<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Notifications\SendTwoFactorCodeNotification;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = User::find(Auth::user()->id); // Get the authenticated user

        if ($user) {
            // Check if the user's phone is not verified
            if (!$user->phone_verified_at) {
                // Generate and send OTP only if it hasn’t been sent already
                if (!$user->two_factor_code) {
                    $user->generateTwoFactorCode();
                    $user->notify(new SendTwoFactorCodeNotification());
                }

                // Redirect to verification page if not already there
                if (!$request->is('verify*')) {
                    return redirect()->route('verify.index');
                }
            }

            // Handle OTP expiration
            if ($user->two_factor_code && $user->two_factor_expires_at < now()) {
                $user->resetTwoFactorCode(); // Reset OTP

                return redirect()->route('verify.index')
                    ->withStatus('Your verification code expired. A new code has been sent.');
            }
        }
        return $next($request);
    }
}
