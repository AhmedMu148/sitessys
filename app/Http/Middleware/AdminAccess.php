<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     * 
     * This middleware ensures only users with admin privileges can access admin routes
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Check if user has admin access permissions
        if (!$user->hasAnyRole(['super-admin', 'admin', 'team-member'])) {
            abort(403, 'Access denied. You do not have permission to access the admin panel.');
        }

        // Check if user account is active
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Your account has been deactivated.']);
        }

        return $next($request);
    }
}
