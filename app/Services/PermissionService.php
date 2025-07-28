<?php

namespace App\Services;

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
    public function getPermissionsForIndex(array $filters = []): LengthAwarePaginator
    {
        return $this->permissionRepository->getPaginatedPermissions($filters);
    }

    /**
     * Create a new permission within a transaction.
     * @throws Throwable
     */
    public function createNewPermission(array $data): Permission
    {
        return DB::transaction(function () use ($data) {
            $permission = $this->permissionRepository->create($data);

            activity()
                ->performedOn($permission)
                ->causedBy(auth()->user())
                ->log('Permission created');

            return $permission;
        });
    }

    /**
     * Update an existing permission within a transaction.
     * @throws Throwable
     */
    public function updatePermission(Permission $permission, array $data): Permission
    {
        return DB::transaction(function () use ($permission, $data) {
            $this->permissionRepository->update($permission, $data);

            activity()
                ->performedOn($permission)
                ->causedBy(auth()->user())
                ->withProperty('attributes', $data)
                ->log('Permission updated');

            return $permission->fresh();
        });
    }

    /**
     * Toggle the status of a permission within a transaction.
     * @throws Throwable
     */
    public function togglePermissionStatus(Permission $permission): Permission
    {
        return DB::transaction(function () use ($permission) {
            $newStatus = $permission->status === 'active' ? 'inactive' : 'active';
            $this->permissionRepository->update($permission, ['status' => $newStatus]);

            activity()
                ->performedOn($permission)
                ->causedBy(auth()->user())
                ->withProperty('status', $newStatus)
                ->log('Permission status updated');

            return $permission->fresh();
        });
    }
}
