<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class DomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $mainDomain = config('app.main_domain', 'localhost');
        
        // List of domains that should bypass tenant detection
        $bypassDomains = [
            'localhost',
            '127.0.0.1',
            'phplaravel-1399496-5687062.cloudwaysapps.com'
        ];
        
        // Skip for admin routes or main domain or bypass domains
        if ($request->is('admin/*') || 
            $request->is('login') || 
            $request->is('register') || 
            $request->is('logout') ||
            in_array($host, $bypassDomains) || 
            $host === $mainDomain) {
            return $next($request);
        }

        // Check if it's a subdomain
        if (str_ends_with($host, '.' . $mainDomain)) {
            $subdomain = str_replace('.' . $mainDomain, '', $host);
            $user = User::where('subdomain', $subdomain)->where('is_active', true)->first();
        } else {
            // Check if it's a custom domain
            $user = User::where('domain', $host)->where('is_active', true)->first();
        }

        if (!$user) {
            // If no tenant found, just continue without setting tenant
            // This allows the main site to work normally
            return $next($request);
        }

        // Set the tenant user in the request
        $request->attributes->set('tenant_user', $user);
        
        // Set view composer data for the tenant
        view()->share('tenant_user', $user);
        view()->share('tenant_site', $user->sites()->first());

        return $next($request);
    }
}
