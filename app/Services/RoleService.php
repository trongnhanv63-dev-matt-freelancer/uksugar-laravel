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
     * Get roles for the index page with filtering and pagination.
     *
     * @param array<string, mixed> $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getRolesForIndex(array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // We can add more business logic here in the future if needed
        return $this->roleRepository->getPaginatedRoles($filters);
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
            $role = $this->roleRepository->create(Arr::except($data, 'permissions'));

            if (!empty($data['permissions'])) {
                $permissionIds = array_map('intval', $data['permissions']);
                $role->syncPermissions($permissionIds);
            }

            activity()
                ->performedOn($role)
                ->causedBy(auth()->user())
                ->log('Role created');

            return $role;
        });
    }

    /**
     * Update an existing role and sync its permissions within a transaction.
     * @throws Throwable
     */
    public function updateExistingRole(Role $role, array $data): Role
    {
        if ($role->name === 'super-admin') {
            throw new \Exception('The Super Admin role cannot be modified.');
        }

        return DB::transaction(function () use ($role, $data) {
            $this->roleRepository->update($role, Arr::except($data, 'permissions'));

            $permissionIds = array_map('intval', $data['permissions'] ?? []);
            $this->syncPermissionsAndLog($role, $permissionIds);

            return $role->fresh();
        });
    }

    /**
     * Toggle the status of a role within a transaction.
     * @throws Throwable
     */
    public function toggleRoleStatus(Role $role): Role
    {
        if ($role->name === 'super-admin') {
            throw new \Exception('The Super Admin role status cannot be changed.');
        }

        return DB::transaction(function () use ($role) {
            $newStatus = $role->status === 'active' ? 'inactive' : 'active';
            $this->roleRepository->update($role, ['status' => $newStatus]);

            activity()
                ->performedOn($role)
                ->causedBy(auth()->user())
                ->withProperty('status', $newStatus)
                ->log('Role status updated');

            return $role->fresh();
        });
    }

    /**
     * Sync role permissions and log the activity.
     */
    private function syncPermissionsAndLog(Role $role, array $permissionIds): void
    {
        $oldPermissions = $role->permissions->pluck('name');
        $role->syncPermissions($permissionIds);
        $newPermissions = $role->fresh()->permissions->pluck('name');

        if ($oldPermissions->diff($newPermissions)->isNotEmpty() || $newPermissions->diff($oldPermissions)->isNotEmpty()) {
            activity()
                ->performedOn($role)
                ->causedBy(auth()->user())
                ->withProperty('old', $oldPermissions->toArray())
                ->withProperty('new', $newPermissions->toArray())
                ->log('Role permissions updated');
        }
    }
}
