<?php

namespace App\Repositories\Eloquent;

use App\Models\Permission; // Use your custom Permission model
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * The Eloquent implementation of the PermissionRepositoryInterface.
 */
class EloquentPermissionRepository implements PermissionRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAll(): Collection
    {
        return Permission::latest()->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getAllActive(): Collection
    {
        return Permission::where('status', 'active')->get();
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Permission
    {
        return Permission::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $attributes): Permission
    {
        return Permission::create($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Permission $permission, array $attributes): Permission
    {
        $permission->update($attributes);
        return $permission;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaginatedPermissions(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Permission::query();

        // Apply search filter for name or description
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });

        // Apply status filter
        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });

        // Apply dynamic sorting
        $sortBy = $filters['sort_by'] ?? 'id';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }
}
