<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAllowedDomains
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedDomains = explode(',', env('ALLOWED_DOMAINS'));
        $origin = $request->header('Origin');
        
        // Check if the domain is allowed
        if (!in_array($origin, $allowedDomains)) {
            return response()->json(['message' => 'Unauthorized domain'], 403);
        }
        
        return $next($request);
    }
}
