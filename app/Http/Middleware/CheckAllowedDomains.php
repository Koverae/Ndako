<?php

namespace App\Http\Middleware;

use App\Models\Client\ApiClient;
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
        $origin = $request->header('Origin');
        $publicKey = $request->header('X-API-Key');
        $privateKey = $request->header('X-API-Secret');

        if (! $origin || ! $publicKey) {
            return response()->json(['message' => 'Missing origin or app key'], 400);
        }

        $client = ApiClient::where('public_key ', $publicKey)->first();

        if (! $client) {
            return response()->json(['message' => 'Invalid API key'], 403);
        }

        $allowedDomains = $client->authorized_domains ?? [];

        // Check if the domain is allowed
        if (!in_array($origin, $allowedDomains)) {
            return response()->json(['message' => 'Unauthorized domain'], 403);
        }

        return $next($request);
    }
}
