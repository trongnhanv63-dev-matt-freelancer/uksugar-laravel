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

    /**
     * Create a new permission.
     * @param array<string, string> $attributes
     * @return Permission
     */
    public function create(array $attributes): Permission;

    /**
     * Find a permission by its ID.
     * @param int $id
     * @return Permission|null
     */
    public function findById(int $id): ?Permission;

    /**
     * Update an existing permission.
     * @param int $id
     * @param array<string, string> $attributes
     * @return Permission
     */
    public function update(int $id, array $attributes): Permission;

    /**
     * Delete a permission by its ID.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
