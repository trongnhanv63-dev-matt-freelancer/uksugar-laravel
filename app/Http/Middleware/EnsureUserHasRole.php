<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     * This version makes the $role parameter optional to be more robust.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $role The role name to check for.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        // If a role is specified in the route's middleware, and the user
        // is either not logged in or does not have that role, then abort.
        if ($role && (!$request->user() || !$request->user()->hasRole($role))) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        // Allow the request to proceed if no role is required or if the user has the role.
        return $next($request);
    }
}
