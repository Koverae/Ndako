<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\ChannelManager\Models\Guest\Guest;
use Modules\Settings\Models\System\Setting;
use Modules\Settings\Policies\SettingPolicy;
use Modules\Settings\Policies\UserPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Setting::class, SettingPolicy::class);

        // Relation::enforceMorphMap([
        //     'user'  => User::class,
        //     'guest' => Guest::class,
        // ]);
    }
}
