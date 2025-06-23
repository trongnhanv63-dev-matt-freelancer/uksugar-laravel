<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User; // Import User model
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // This implements the "Intercepting Gate Checks" feature from the Laravel docs.
        // It runs before any other authorization check.
        Gate::before(function (User $user, string $ability) {
            // If the user is a super admin, they are granted all abilities.
            // Returning true from here bypasses all other checks.
            return $user->is_super_admin ? true : null;
        });

        // This section dynamically defines a Gate for every permission in the database.
        // This is how we connect our database-driven permissions to Laravel's Gate system.
        try {
            foreach (Permission::all() as $permission) {
                Gate::define($permission->slug, function (User $user) use ($permission) {
                    // The logic for the Gate is simply to call our existing hasPermissionTo method.
                    return $user->hasPermissionTo($permission->slug);
                });
            }
        } catch (\Exception $e) {
            // This try-catch block prevents errors during initial migrations when
            // the 'permissions' table might not exist yet.
            report($e);
        }
    }
}
