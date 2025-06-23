<?php

namespace NhanDev\Rbac\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use NhanDev\Rbac\Models\Permission;
use NhanDev\Rbac\Repositories\Contracts;

class RbacServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/rbac.php', 'rbac');

        // Bind all repository interfaces to their implementations from the config file.
        $this->app->bind(Contracts\UserRepositoryInterface::class, config('rbac.repositories.user'));
        $this->app->bind(Contracts\RoleRepositoryInterface::class, config('rbac.repositories.role'));
        $this->app->bind(Contracts\PermissionRepositoryInterface::class, config('rbac.repositories.permission'));
    }

    public function boot(): void
    {
        $this->registerGates();

        if ($this->app->runningInConsole()) {
            $this->commands([
                \NhanDev\Rbac\Console\Commands\MakeSeedersCommand::class, // Changed this line
            ]);
            $this->bootForConsole();
        }
    }

    protected function registerGates(): void
    {
        // Super Admin check - this now relies on the database role.
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
            return null;
        });

        // Dynamically define gates for all permissions.
        try {
            foreach (Permission::all() as $permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission->slug);
                });
            }
        } catch (\Exception $e) {
            // This prevents errors during initial migrations.
            report($e);
        }
    }

    protected function bootForConsole(): void
    {
        // Makes the configuration file publishable.
        $this->publishes([
            __DIR__.'/../../config/rbac.php' => config_path('rbac.php'),
        ], 'rbac-config');

        // Makes the migration files publishable.
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'rbac-migrations');

        // ADD THIS BLOCK: Makes the seeder files publishable
        $this->publishes([
            __DIR__.'/../../database/seeders' => database_path('seeders'),
        ], 'rbac-seeders');
    }
}
