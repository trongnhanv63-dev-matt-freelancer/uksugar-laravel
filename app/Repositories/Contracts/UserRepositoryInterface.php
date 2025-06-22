<?php

namespace App\Repositories\Contracts;

use App\Models\User;

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
}
