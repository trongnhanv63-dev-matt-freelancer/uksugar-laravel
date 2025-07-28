<?php

namespace App\Repositories\Eloquent;

use App\Models\Role; // Use your custom Role model
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * The Eloquent implementation of the RoleRepositoryInterface.
 */
class EloquentRoleRepository implements RoleRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAllWithPermissions(): Collection
    {
        return Role::with('permissions')->latest()->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getAllActive(): Collection
    {
        return Role::where('status', 'active')->get();
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Role
    {
        return Role::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $attributes): Role
    {
        return Role::create($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Role $role, array $attributes): Role
    {
        $role->update($attributes);
        return $role;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaginatedRoles(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Role::with('permissions')->withCount('permissions');

        $this->applyFilters($query, $filters);
        $this->applySorting($query, $filters);

        return $query->paginate($perPage);
    }

    /**
     * Apply search and status filters to the query.
     */
    private function applyFilters($query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%"));
        })
        ->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });
    }

    /**
     * Apply dynamic sorting to the query.
     */
    private function applySorting($query, array $filters): void
    {
        $sortBy = $filters['sort_by'] ?? 'id';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        $sortableColumns = ['id', 'name', 'description', 'status', 'permissions_count'];
        if (! in_array($sortBy, $sortableColumns)) {
            $sortBy = 'id';
        }

        $query->orderBy($sortBy, $sortDirection);
    }
}
