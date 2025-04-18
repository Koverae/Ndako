<?php

namespace App\Http\Middleware;

use App\Models\Client\ApiClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $origin = $request->header('Origin');
        $publicKey = $request->header('X-API-Key');
        $privateKey = $request->header('X-API-Secret');

        if (!$publicKey || !$privateKey) {
            return response()->json(['message' => 'API keys are required.'], 401);
        }

        $client = ApiClient::where('public_key ', $publicKey)->first();

        if (!$client || $client->private_key !== $privateKey) {
            return response()->json(['message' => 'Invalid API keys.'], 401);
        }

        $allowedDomains = $client->authorized_domains ?? [];

        // Check if the domain is allowed
        if (!in_array($origin, $allowedDomains)) {
            return response()->json(['message' => 'Unauthorized domain'], 403);
        }
        // Log::info('Received API keys:', ['public_key' => $publicKey, 'private_key' => $privateKey]);

        // Pass the client data to the request for further use
        $request->merge(['api_client' => $client]);

        return $next($request);
    }
}
