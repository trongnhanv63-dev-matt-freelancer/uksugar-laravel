<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => \App\Policies\UserPolicy::class,
    ];

    public function boot(): void
    {
        // This gate is checked before all other authorization checks.
        Gate::before(function (User $user, string $ability) {
            // The hasRole() method now comes from the Spatie package.
            return $user->hasRole('super-admin') ? true : null;
        });
    }
}
