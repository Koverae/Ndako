<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IdentifyKover;
use App\Http\Middleware\AppendSubdomain;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {

            Route::domain('{subdomain}.'.env('APP_URL'))->middleware('web')
            // ->prefix('web')
            ->group(base_path('routes/web.php'));

            Route::domain('{subdomain}.'.env('APP_URL'))->middleware(['web'])
            // ->prefix('web')
            ->group(base_path('routes/auth.php'));
     
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->append([
            IdentifyKover::class,
            AppendSubdomain::class
        ]);
        
        $middleware->alias([
            'twofactor' => \App\Http\Middleware\TwoFactorMiddleware::class,
        ]);
        
        // $middleware->redirectGuestsTo('/login');
        // Using a closure...
        $middleware->redirectGuestsTo(fn (Request $request) => Route::subdomainRoute('login', ['subdomain' => current_company()->domain_name]));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
