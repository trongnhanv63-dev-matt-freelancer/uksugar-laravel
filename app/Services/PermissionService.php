<?php

namespace App\Services;

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

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
     */
    public function getPermissionsForIndex(): Collection
    {
        return $this->permissionRepository->getAll();
    }

    /**
     * Create a new permission within a transaction.
     * @throws Throwable
     */
    public function createNewPermission(array $data): Permission
    {
        return DB::transaction(function () use ($data) {
            return $this->permissionRepository->create($data);
        });
    }

    /**
     * Update an existing permission within a transaction.
     * @throws Throwable
     */
    public function updatePermission(int $permissionId, array $data): Permission
    {
        return DB::transaction(function () use ($permissionId, $data) {
            return $this->permissionRepository->update($permissionId, $data);
        });
    }

    /**
     * Toggle the status of a permission within a transaction.
     * @throws Throwable
     */
    public function togglePermissionStatus(int $permissionId): Permission
    {
        return DB::transaction(function () use ($permissionId) {
            $permission = $this->permissionRepository->findById($permissionId);

            $newStatus = $permission->status === 'active' ? 'inactive' : 'active';

            return $this->permissionRepository->update($permissionId, ['status' => $newStatus]);
        });
    }
}
