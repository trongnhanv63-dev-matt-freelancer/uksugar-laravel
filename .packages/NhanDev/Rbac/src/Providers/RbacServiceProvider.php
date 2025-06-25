<?php

namespace NhanDev\Rbac\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use NhanDev\Rbac\Models\ActivityLog;
use NhanDev\Rbac\Models\Permission;
use NhanDev\Rbac\Models\Role;
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
        $this->bootActivityLoggers(); // Call the new method

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

    /**
     * Boot the activity loggers for relationship changes.
     */
    protected function bootActivityLoggers(): void
    {
        $userModelClass = config('rbac.user_model');

        // Listen for when roles are synced to a user
        $userModelClass::resolveRelationUsing('roles', function ($userModel) {
            return $userModel->belongsToMany(Role::class, 'user_roles')
                ->withTimestamps()
                ->using(\Illuminate\Database\Eloquent\Relations\Pivot::class)
                ->on('synced', function ($model, $result) {
                    if (empty($result['attached']) && empty($result['detached'])) {
                        return;
                    }
                    $description = "Updated roles for user '{$model->username}'.";
                    $this->createRelationshipLog($description, Auth::user(), $model, $result);
                });
        });

        // Listen for when permissions are synced to a role
        Role::resolveRelationUsing('permissions', function ($roleModel) {
            dd('??');
            return $roleModel->belongsToMany(Permission::class, 'role_permissions')
                ->withTimestamps()
                ->using(\Illuminate\Database\Eloquent\Relations\Pivot::class)
                ->on('synced', function ($model, $result) {
                    if (empty($result['attached']) && empty($result['detached'])) {
                        return;
                    }
                    $description = "Updated permissions for role '{$model->name}'.";
                    $this->createRelationshipLog($description, Auth::user(), $model, $result);
                });
        });
    }

    /**
     * Helper to create the activity log for relationship changes.
     */
    protected function createRelationshipLog($description, $causer, $subject, $changes): void
    {
        ActivityLog::create([
           'description' => $description,
           'causer_type' => $causer ? get_class($causer) : null,
           'causer_id' => $causer ? $causer->id : null,
           'subject_type' => get_class($subject),
           'subject_id' => $subject->id,
           'properties' => [
               'attached' => $changes['attached'],
               'detached' => $changes['detached'],
           ],
        ]);
    }
}
