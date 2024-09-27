<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckSiteStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Fetch site settings from the database
        $siteStatus = getSetting('site_status');

        // Check if the site is active
        if ($siteStatus != 1) {
            // Return a maintenance message with status code 503
            return response()->json([
                'status' => 'error',
                'message' => 'Site is under maintenance'
            ], 503);
        }

        return $next($request);
    }
}
