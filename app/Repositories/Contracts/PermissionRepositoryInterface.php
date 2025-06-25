<?php

namespace App\Repositories\Contracts;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for the Permission repository.
 * Defines the contract for data access operations for Permissions.
 */
interface PermissionRepositoryInterface
{
    /**
     * Get all permissions, typically for display.
     * @return Collection<int, Permission>
     */
    public function getAll(): Collection;

    /**
     * Get all active permissions.
     * @return Collection<int, Permission>
     */
    public function getAllActive(): Collection;

    /**
     * Find a permission by its ID.
     * @param int $id
     * @return Permission|null
     */
    public function findById(int $id): ?Permission;

    /**
     * Create a new permission.
     * @param array<string, mixed> $attributes
     * @return Permission
     */
    public function create(array $attributes): Permission;

    /**
     * Update an existing permission's attributes.
     * @param int $id
     * @param array<string, mixed> $attributes
     * @return Permission
     */
    public function update(int $id, array $attributes): Permission;
}
