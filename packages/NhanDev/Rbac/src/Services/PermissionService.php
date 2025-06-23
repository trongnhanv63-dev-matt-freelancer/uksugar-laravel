<?php

namespace NhanDev\Rbac\Services;

use Illuminate\Database\Eloquent\Collection;
use NhanDev\Rbac\Models\Permission;
use NhanDev\Rbac\Repositories\Contracts\PermissionRepositoryInterface;

/**
 * Service class for handling Permission-related business logic.
 */
class PermissionService
{
    protected PermissionRepositoryInterface $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get all permissions for the index page.
     *
     * @return Collection
     */
    public function getPermissionsForIndex(): Collection
    {
        return $this->permissionRepository->getAll();
    }

    /**
     * Create a new permission.
     *
     * @param array<string, string> $data
     * @return Permission
     */
    public function createNewPermission(array $data): Permission
    {
        return $this->permissionRepository->create($data);
    }

    /**
     * Update an existing permission.
     *
     * @param int $permissionId
     * @param array<string, string> $data
     * @return Permission
     */
    public function updatePermission(int $permissionId, array $data): Permission
    {
        return $this->permissionRepository->update($permissionId, $data);
    }

    /**
     * Toggle the status of a permission.
     *
     * @param int $permissionId
     * @return Permission
     */
    public function togglePermissionStatus(int $permissionId): Permission
    {
        $permission = $this->permissionRepository->findById($permissionId);

        $newStatus = $permission->status === config('rbac.permission_statuses.active') ? config('rbac.permission_statuses.inactive') : config('rbac.permission_statuses.active');

        $this->permissionRepository->update($permissionId, ['status' => $newStatus]);

        // Return a fresh instance from the database to ensure consistency
        return $permission->fresh();
    }
}
