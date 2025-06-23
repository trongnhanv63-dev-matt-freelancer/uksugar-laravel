<?php

namespace NhanDev\Rbac\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for the User repository.
 * Defines the contract for data access operations for Users.
 */
interface UserRepositoryInterface
{
    /**
     * Create a new user record in the database.
     *
     * @param array<string, mixed> $attributes
     * @return User
     */
    public function create(array $attributes): User;

    /**
     * Find a user by their email address.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Get all users with their roles.
     *
     * @return Collection<int, User>
     */
    public function getAllWithRoles(): Collection;

    /**
     * Find a user by their ID.
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User;

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array<string, mixed> $attributes
     * @return User
     */
    public function update(int $id, array $attributes): User;
}
