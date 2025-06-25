<?php

namespace App\Repositories\Eloquent;

use App\Models\Role; // Use your custom Role model
use App\Repositories\Contracts\RoleRepositoryInterface;
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
        return Role::with('permissions')->latest('id')->get();
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
    public function update(int $id, array $attributes): Role
    {
        $role = $this->findById($id);
        if ($role) {
            $role->update($attributes);
        }
        return $role;
    }
}
