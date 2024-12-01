<?php

namespace App\Services;

use Laravel\Socialite\Facades\Socialite;

class SocialiteService
{
    protected $providers = [
        'google',
        // 'facebook',
        // 'linkedin',
        // 'koverae'
        // Add other providers as needed
    ];

    public function redirectToProvider(string $provider)
    {
        if (!in_array($provider, $this->providers)) {
            throw new \Exception("Unsupported provider: {$provider}");
        }
        
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(string $provider)
    {
        if (!in_array($provider, $this->providers)) {
            throw new \Exception("Unsupported provider: {$provider}");
        }

        try {
            $user = Socialite::driver($provider)->user();
            return $user;
        } catch (\Exception $e) {
            throw new \Exception("Error with provider: {$provider}, " . $e->getMessage());
        }
    }
}