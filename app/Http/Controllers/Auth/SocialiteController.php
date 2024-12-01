<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SocialiteService;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    protected $socialiteService;

    public function __construct(SocialiteService $socialiteService)
    {
        $this->socialiteService = $socialiteService;
    }

    public function redirectToProvider($provider)
    {
        return $this->socialiteService->redirectToProvider($provider);
    }

    public function handleProviderCallback($provider)
    {
        try {
            // Get user data from the social provider dynamically
            $user = $this->socialiteService->handleProviderCallback($provider);

            // Find user in the database where the social id matches the id provided by the provider
            $finduser = User::where('social_id', $user->id)
                            ->where('social_type', $provider)
                            ->first();

            if ($finduser) {
                // If user found, log them in
                Auth::login($finduser);
            } else {
                // If user not found, create a new user using the provider's data
                $randomPwd = Str::random(12);
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => now(),
                    'social_id' => $user->id,
                    'social_type' => $provider,  // Store the provider type (google, facebook, etc.)
                    'password' => $randomPwd,  // Optionally set a random or provider-specific password
                ]);

                // Log in the new user
                Auth::login($newUser);
            }

            // Redirect the user to the dashboard
            return redirect()->route('dashboard');
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors(['error' => $e->getMessage()]);
        }
    }
}
