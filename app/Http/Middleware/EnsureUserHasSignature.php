<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasSignature
{
    /**
     * Routes that should be excluded from signature check
     */
    protected array $excludedRoutes = [
        'requisitioner.signature',
        'requisitioner.contact-number',
        'profile.show',
        'logout',
        'login',
    ];

    /**
     * URI paths that should be excluded from signature check
     */
    protected array $excludedPaths = [
        'requisitioner/require-signature',
        'requisitioner/require-contact-number',
        'user/profile',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip if not authenticated
        if (!auth()->check()) {
            return $next($request);
        }

        // Skip if on excluded route
        $currentRoute = $request->route()?->getName();
        if (in_array($currentRoute, $this->excludedRoutes)) {
            return $next($request);
        }

        // Skip if on excluded path
        $currentPath = $request->path();
        if (in_array($currentPath, $this->excludedPaths)) {
            return $next($request);
        }

        // Skip if route starts with 'api.' or 'livewire.'
        if ($currentRoute && (str_starts_with($currentRoute, 'api.') || str_starts_with($currentRoute, 'livewire.'))) {
            return $next($request);
        }

        // Check if user has signature
        if (!auth()->user()->signature()->exists()) {
            return redirect()->route('requisitioner.signature');
        }

        return $next($request);
    }
}
