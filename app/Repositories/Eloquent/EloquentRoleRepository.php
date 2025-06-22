<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentRoleRepository implements RoleRepositoryInterface
{
    protected Role $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function getAllWithPermissions(): Collection
    {
        // Eager load the permissions relationship to avoid N+1 problems
        return $this->model->with('permissions')->get();
    }

    public function findById(int $id): ?Role
    {
        // Use findOrFail to automatically handle the case where the role is not found.
        return $this->model->findOrFail($id);
    }

    public function create(array $attributes): Role
    {
        return $this->model->create($attributes);
    }

    public function update(int $id, array $attributes): Role
    {
        $role = $this->findById($id);
        $role->update($attributes);
        return $role;
    }

    public function delete(int $id): bool
    {
        return $this->findById($id)->delete();
    }
}
