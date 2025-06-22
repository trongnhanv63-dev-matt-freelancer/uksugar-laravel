<?php

namespace App\Repositories\Contracts;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for the Role repository.
 */
interface RoleRepositoryInterface
{
    /**
     * Get all roles with their permissions.
     * @return Collection<int, Role>
     */
    public function getAllWithPermissions(): Collection;

    /**
     * Find a role by its ID.
     * @param int $id
     * @return Role|null
     */
    public function findById(int $id): ?Role;

    /**
     * Create a new role.
     * @param array<string, mixed> $attributes
     * @return Role
     */
    public function create(array $attributes): Role;

    /**
     * Update an existing role.
     * @param int $id
     * @param array<string, mixed> $attributes
     * @return Role
     */
    public function update(int $id, array $attributes): Role;

    /**
     * Delete a role by its ID.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
