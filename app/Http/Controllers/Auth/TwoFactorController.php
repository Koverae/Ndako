<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendTwoFactorCodeNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class TwoFactorController extends Controller
{
    public function index(): View
    {
        return view('auth.verify-otp');
    }

    public function store(Request $request): ValidationException|RedirectResponse
    {
        $code = implode('', $request->input('two_factor_code')); // If array of digits is expected
        $request->validate([
            'two_factor_code' => ['required'],
        ]);
        $user = User::find(auth()->user()->id);
        // First check if the OTP has expired
        if (now()->greaterThan($user->two_factor_expires_at)) {
            throw ValidationException::withMessages([
                'two_factor_code' => __("Your OTP has expired. Please request a new one."),
            ]);
        }
        // Then check if the OTP matches
        if ($user->two_factor_code !== $code) {
            throw ValidationException::withMessages([
                'two_factor_code' => __("The OTP code you entered is incorrect. Please try again."),
            ]);
        }
        $user->resetTwoFactorCode();

        // Update the ip address
        // $user->last_login_ip = $request->ipinfo->ip;
        $user->last_login_ip = $request->ip();
        $user->update([
            'last_login_at' => now()
        ]);
        
        $user->save();

        // Retrieve intended URL or fallback to dashboard
        // $redirectUrl = session()->pull('intended_url', route('dashboard'));

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function resend(): RedirectResponse
    {
        $user = User::find(auth()->user()->id);
        $user->generateTwoFactorCode();
        $user->notify(new SendTwoFactorCodeNotification());
        return redirect()->back()->withStatus(__('Code has been sent again'));
    }
}
