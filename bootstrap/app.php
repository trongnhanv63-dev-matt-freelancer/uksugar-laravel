<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException; // Import the exception class
use Illuminate\Http\Request; // Import the Request class

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            // Check if the request is for an admin route
            if ($request->is('admin/*')) {
                // If so, redirect to the admin login page
                return redirect()->guest(route('admin.login'));
            }

            // For all other unauthenticated requests, redirect to the public login page
            return redirect()->guest(route('login'));
        });
    })->create();
