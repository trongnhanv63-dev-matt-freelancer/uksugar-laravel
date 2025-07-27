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

        // Apply search filter for name or description
        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Apply status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply dynamic sorting
        $sortBy = $filters['sort_by'] ?? 'id';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        // Prevent sorting by permissions relationship to avoid errors
        if ($sortBy !== 'permissions') {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            // Default sort if trying to sort by a relationship
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($perPage);
    }
}
