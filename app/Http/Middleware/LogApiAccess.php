<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiAccessLog;
use Symfony\Component\HttpFoundation\Response;

class LogApiAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log API routes
        if ($request->is('api/*')) {
            ApiAccessLog::logAccess($request, $response, $request->user());
        }

        return $response;
    }
}
