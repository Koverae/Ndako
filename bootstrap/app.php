<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
// use App\Http\Middleware\IdentifyKover;
// use App\Http\Middleware\AppendSubdomain;
use App\Http\Middleware\CorsMiddleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware(['web'])
            // ->prefix('web')
            ->group(base_path('routes/auth.php'));

        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->append([
            // AppendSubdomain::class,
            \App\Http\Middleware\EnsureOnboardingCompleted::class,
            // \Illuminate\Http\Middleware\HandleCors::class
            CorsMiddleware::class,
        ]);

        $middleware->alias([
            'twofactor' => \App\Http\Middleware\TwoFactorMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'valid.invitation' => \App\Http\Middleware\ValidInvitationToken::class,
            'checkApiKey' => \App\Http\Middleware\AuthenticateApiKey::class,
            'check-allowed-domains' => \App\Http\Middleware\CheckAllowedDomains::class,
            'identify-kover' => \App\Http\Middleware\IdentifyKover::class,
        ]);

        // $middleware->redirectGuestsTo('/login');
        // Using a closure...
        $middleware->redirectGuestsTo(fn (Request $request) => route('login'));

        // $middleware->validateCsrfTokens(except: [
        //     'api/check-availability', // Add your route here
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
