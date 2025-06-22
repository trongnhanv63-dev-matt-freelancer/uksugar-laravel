<?php

namespace App\Repositories\Eloquent;

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentPermissionRepository implements PermissionRepositoryInterface
{
    protected Permission $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function getAll(): Collection
    {
        return $this->model->orderBy('slug')->get();
    }

    public function create(array $attributes): Permission
    {
        return $this->model->create($attributes);
    }

    public function findById(int $id): ?Permission
    {
        return $this->model->findOrFail($id);
    }

    public function update(int $id, array $attributes): Permission
    {
        $permission = $this->findById($id);
        $permission->update($attributes);
        return $permission;
    }

    public function delete(int $id): bool
    {
        return $this->findById($id)->delete();
    }
}
