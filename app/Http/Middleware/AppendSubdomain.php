<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;

class AppendSubdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if current_company() helper exists and has a domain_name
        if (function_exists('current_company') && $domain = current_company()->domain_name) {
            // Add the subdomain parameter to the route URL generator
            Route::macro('route', function ($name, $parameters = [], $absolute = true) use ($domain) {
                $parameters['subdomain'] = $domain;
                return route($name, $parameters, $absolute);
            });
        }

        return $next($request);
    }
}
