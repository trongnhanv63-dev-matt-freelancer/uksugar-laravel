<?php

namespace NhanDev\Rbac\Services;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use NhanDev\Rbac\Models\Role;
use NhanDev\Rbac\Repositories\Contracts\PermissionRepositoryInterface;
use NhanDev\Rbac\Repositories\Contracts\RoleRepositoryInterface;

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

    public function getRolesForIndex(): Collection
    {
        return $this->roleRepository->getAllWithPermissions();
    }

    public function getPermissionsForForm(): Collection
    {
        return $this->permissionRepository->getAll();
    }

    public function createNewRole(array $data): Role
    {
        $role = $this->roleRepository->create($data);
        if (!empty($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }
        return $role;
    }

    public function updateExistingRole(int $roleId, array $data): Role
    {
        // The repository updates the main role attributes like name, description
        $role = $this->roleRepository->update($roleId, $data);

        // It checks if the role is NOT 'super-admin' before syncing permissions.
        // If it IS 'super-admin', this block is skipped entirely.
        if ($role->name !== 'super-admin') {
            $role->permissions()->sync($data['permissions'] ?? []);
        } else {
            throw new Exception('The Super Admin role cannot be modified.');
        }

        return $role;
    }

    public function toggleRoleStatus(int $roleId): Role
    {
        $role = $this->roleRepository->findById($roleId);

        if ($role->name === 'super-admin') {
            throw new Exception('The Super Admin role status cannot be changed.');
        }

        // Use the enum for comparison and assignment
        $newStatus = $role->status === config('rbac.role_statuses.active') ? config('rbac.role_statuses.inactive') : config('rbac.role_statuses.active');
        $this->roleRepository->update($roleId, ['status' => $newStatus]);

        return $role->fresh(); // Return the updated model
    }
}
