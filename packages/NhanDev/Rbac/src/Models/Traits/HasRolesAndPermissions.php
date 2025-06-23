<?php

namespace NhanDev\Rbac\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use NhanDev\Rbac\Models\Role;

trait HasRolesAndPermissions
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * An accessor that checks if the user has the 'super-admin' role.
     * The result is cached for the duration of the request for performance.
     */
    public function isSuperAdmin(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->hasRole('super-admin'),
        )->shouldCache();
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermissionTo(string $permissionSlug): bool
    {
        // The check now uses the accessor, which in turn calls hasRole().
        if ($this->is_super_admin) {
            return true;
        }

        // The rest of the logic remains, checking active roles and permissions.
        return $this->roles()
            ->where('status', 'active')
            ->whereHas('permissions', function ($query) use ($permissionSlug) {
                $query->where('slug', $permissionSlug)->where('status', 'active');
            })
            ->exists();
    }
}
