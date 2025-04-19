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
        
        Log::info('Received API keys:', ['origin' => $origin, 'public_key' => $publicKey, 'private_key' => $privateKey]);

        if (!$publicKey || !$privateKey) {
            return response()->json(['message' => 'API keys are required.'], 401);
        }

        $client = ApiClient::where('public_key', $publicKey)->first();

        if (!$client || $client->private_key !== $privateKey) {
            return response()->json(['message' => 'Invalid API keys.'], 401);
        }

        $allowedDomains = $client->authorized_domains ?? [];
        
        Log::info('Authorized Domains:', ['domains' => $allowedDomains]);

        // Check if the domain is allowed
        if ($origin) {
            $normalizedAllowed = array_map(fn($d) => rtrim($d, '/'), $client->authorized_domains);
        
            if (!in_array(rtrim($origin, '/'), $normalizedAllowed)) {
                return response()->json(['message' => 'Unauthorized domain'], 403);
            }
        } else {
            Log::info('No Origin provided — assuming safe (e.g., internal server call)');
        }

        // Pass the client data to the request for further use
        $request->merge(['api_client' => $client]);
        
        // Make client available globally
        // app()->instance('ndako.api_client', $client);

        return $next($request);
    }
}
