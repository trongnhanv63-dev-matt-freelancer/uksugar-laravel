<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;

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
        }

        return $role;
    }

    public function deleteRole(int $roleId): bool
    {
        $role = $this->roleRepository->findById($roleId);
        if ($role->name === 'super-admin') {
            throw new Exception('The Super Admin role cannot be deleted.');
        }
        return $this->roleRepository->delete($roleId);
    }
}
