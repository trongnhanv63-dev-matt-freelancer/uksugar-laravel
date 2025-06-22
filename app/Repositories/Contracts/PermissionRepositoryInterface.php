<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Permission;

/**
 * Interface for the Permission repository.
 */
interface PermissionRepositoryInterface
{
    /**
     * Get all permissions.
     * @return Collection<int, Permission>
     */
    public function getAll(): Collection;
}
