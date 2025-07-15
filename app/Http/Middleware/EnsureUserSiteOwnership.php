<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserSiteOwnership
{
    /**
     * Handle an incoming request.
     *
     * This middleware ensures that users can only access their own sites, templates, and related data.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if the request contains site_id parameter
        $siteId = $request->route('site') ?? $request->input('site_id') ?? $request->route('site_id');
        
        if ($siteId) {
            // Verify that the site belongs to the current user
            $userSite = $user->sites()->where('id', $siteId)->first();
            
            if (!$userSite) {
                abort(403, 'Access denied. You can only access your own sites.');
            }
        }

        // Check if the request contains template_id parameter
        $templateId = $request->route('template') ?? $request->input('template_id') ?? $request->route('template_id');
        
        if ($templateId) {
            // Verify that the template belongs to the current user
            $userTemplate = $user->templates()->where('id', $templateId)->first();
            
            if (!$userTemplate) {
                abort(403, 'Access denied. You can only access your own templates.');
            }
        }

        return $next($request);
    }
}
