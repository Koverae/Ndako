<?php

namespace App\Http\Middleware;

use App\Models\Company\Company;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IdentifyKover
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){

        // Retrieving the core URL from the application configuration
        $coreUrl = config('app.core_url');

        // Attempt to handle the request and identify the tenant
        try {

            // Extracting the host from the incoming request
            $host = $request->getHost();

            // Assuming the first part of the domain is the subdomain
            $subdomain = explode('.', $host)[0];

            // Validate the subdomain and get the company data
            $company = $this->validateSubdomain($subdomain);

            // If no company is found for the subdomain, redirect to an error page
            if (!$company) {
                return $this->redirectOnError($coreUrl, $host, $subdomain);
            }

            // Store the company information in the session for later use
            session(['current_company' => $company]);

            // Proceed with the request if all checks are passed
            return $next($request);

        } catch (Exception $e) {
            // Log any exceptions that occur during the process
            Log::error('Error in CheckSubdomain middleware: ' . $e->getMessage());
            // Redirect to a generic error page on exception
            return redirect()->to("{$coreUrl}/error?message=" . urlencode('An error occurred'));
        }
    }

    // Helper method to validate subdomains and fetch company details
    protected function validateSubdomain($subdomain)
    {
        // Cache the company details to improve performance on repeated requests
        return Cache::remember("company_details_{$subdomain}", 60, function () use ($subdomain) {
            return Company::where('domain_name', $subdomain)->first();
        });
    }

    // General method for redirecting on validation errors
    protected function redirectOnError($coreUrl, $host, $subdomain){
        // Redirect to the error page with query parameters for diagnostics
        return redirect($coreUrl . '/kokoma?esika=' . urlencode($host) . '&komboyabybd=' . urlencode($subdomain));
    }
}
