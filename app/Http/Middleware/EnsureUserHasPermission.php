<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        // The hasPermissionTo() method in the User model will automatically
        // grant access to super-admins, so we don't need special logic here.
        if ($permission && (!$request->user() || !$request->user()->hasPermissionTo($permission))) {
            abort(403, 'ACTION UNAUTHORIZED.');
        }
        return $next($request);
    }
}
