<?php

namespace App\Repositories\Contracts;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for the Role repository.
 * Defines the contract for data access operations for Roles.
 */
interface RoleRepositoryInterface
{
    /**
     * Get all roles with their associated permissions.
     * @return Collection<int, Role>
     */
    public function getAllWithPermissions(): Collection;

    /**
     * Get all active roles.
     * @return Collection<int, Role>
     */
    public function getAllActive(): Collection;

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
     * Update an existing role's attributes.
     * @param \App\Models\Role $role
     * @param array<string, mixed> $attributes
     * @return \App\Models\Role
     */
    public function update(Role $role, array $attributes): Role;
}
