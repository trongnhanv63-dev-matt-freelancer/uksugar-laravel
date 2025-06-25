<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Service class for handling Role-related business logic.
 */
class RoleService
{
    protected RoleRepositoryInterface $roleRepository;
    protected PermissionRepositoryInterface $permissionRepository;
    public function __construct(
        RoleRepositoryInterface $roleRepository,
        PermissionRepositoryInterface $permissionRepository
    ) {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get all roles for the index page view.
     */
    public function getRolesForIndex(): Collection
    {
        return $this->roleRepository->getAllWithPermissions();
    }

    /**
     * Get all active permissions, grouped by their prefix for the create/edit forms.
     * This method contains the grouping logic.
     */
    public function getPermissionsForForm(): Collection
    {
        // 1. Get all active permissions from the repository.
        $allActivePermissions = $this->permissionRepository->getAllActive();

        // 2. Group the collection by the first part of the permission name (before the dot).
        return $allActivePermissions->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });
    }

    /**
     * Create a new role and sync its permissions within a transaction.
     * @throws Throwable
     */
    public function createNewRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            // Create the role using only role attributes
            $role = $this->roleRepository->create(Arr::except($data, ['permissions']));

            // Sync permissions if they are provided
            if (!empty($data['permissions'])) {
                // Use Spatie's method to sync permissions
                $role->syncPermissions($data['permissions']);
            }

            return $role;
        });
    }

    /**
     * Update an existing role and sync its permissions within a transaction.
     * @throws Throwable
     */
    public function updateExistingRole(int $roleId, array $data): Role
    {
        return DB::transaction(function () use ($roleId, $data) {
            $role = $this->roleRepository->findById($roleId);

            // Business rule: Prevent modifying the super-admin role
            if ($role->name === 'super-admin') {
                throw new \Exception('The Super Admin role cannot be modified.');
            }

            // Update the role's main attributes
            $this->roleRepository->update($roleId, Arr::except($data, ['permissions']));

            // Use Spatie's method to sync permissions
            $role->syncPermissions($data['permissions'] ?? []);

            return $role->fresh();
        });
    }

    /**
     * Toggle the status of a role within a transaction.
     * @throws Throwable
     */
    public function toggleRoleStatus(int $roleId): Role
    {
        return DB::transaction(function () use ($roleId) {
            $role = $this->roleRepository->findById($roleId);

            // Business rule: Prevent changing super-admin status
            if ($role->name === 'super-admin') {
                throw new \Exception('The Super Admin role status cannot be changed.');
            }

            $newStatus = $role->status === 'active' ? 'inactive' : 'active';

            return $this->roleRepository->update($roleId, ['status' => $newStatus]);
        });
    }
}
