<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection; // Import Paginator

/**
 * Interface for the User repository.
 * Defines the contract for all data access operations related to Users.
 */
interface UserRepositoryInterface
{
    /**
     * Get all users with their assigned roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\User>
     */
    public function getAllWithRoles(): Collection;

    /**
     * Find a user by their ID.
     *
     * @param int $id
     * @return \App\Models\User|null
     */
    public function findById(int $id): ?User;

    /**
     * Find a user by their email address. Essential for login logic.
     *
     * @param string $email
     * @return \App\Models\User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Create a new user record.
     *
     * @param array<string, mixed> $attributes
     * @return \App\Models\User
     */
    public function create(array $attributes): User;

    /**
     * Update an existing user's attributes.
     *
     * @param \App\Models\User $user
     * @param array<string, mixed> $attributes
     * @return \App\Models\User
     */
    public function update(User $user, array $attributes): User;

    /**
     * Get a paginated list of users with dynamic filtering, searching, and sorting.
     *
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedUsers(array $filters = [], int $perPage = 20): LengthAwarePaginator;
}
