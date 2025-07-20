<?php

namespace App\Repositories\Eloquent;

use App\Models\Permission; // Use your custom Permission model
use App\Repositories\Contracts\PermissionRepositoryInterface;
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
}
