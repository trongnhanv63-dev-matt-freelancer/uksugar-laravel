<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Service class for handling User-related business logic.
 */
class UserService
{
    protected UserRepositoryInterface $userRepository;
    protected RoleRepositoryInterface $roleRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
        * Get users for the index page with filtering and pagination.
        *
        * @param array<string, mixed> $filters
        * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
        */
    public function getUsersForIndex(array $filters): LengthAwarePaginator
    {
        // We can add more business logic here in the future if needed
        return $this->userRepository->getPaginatedUsers($filters);
    }

    /**
     * Get all active roles for the create/edit user forms.
     * @param array<string> $except An array of role names to exclude.
     */
    public function getRolesForForm(array $except = []): Collection
    {
        $allActiveRoles = $this->roleRepository->getAllActive();
        if (!empty($except)) {
            return $allActiveRoles->reject(fn ($role) => in_array($role->name, $except));
        }

        return $allActiveRoles;
    }

    /**
     * Create a new user and assign roles within a transaction.
     * @throws Throwable
     */
    public function createNewUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = $this->userRepository->create(
                Arr::except($data, ['roles']) // Create user without the roles attribute
            );

            // Sync roles if they are provided
            if (!empty($data['roles'])) {
                $roleIds = array_map('intval', $data['roles']);
                $user->syncRoles($roleIds);
            }

            return $user;
        });
    }

    /**
     * Update an existing user and sync their roles within a transaction.
     * @throws Throwable
     */
    public function updateUser(int $userId, array $data): User
    {
        return DB::transaction(function () use ($userId, $data) {
            $user = $this->userRepository->findById($userId);

            // Business rule: Prevent modifying the super-admin user
            if ($user->hasRole('super-admin')) {
                throw new \Exception('The Super Admin user cannot be modified.');
            }

            // If password field is empty, remove it from the data array
            if (empty($data['password'])) {
                unset($data['password']);
            }

            // Update user attributes
            $updateData = Arr::except($data, ['roles', 'email', 'username']);
            $this->userRepository->update($user, $updateData);

            // Sync roles
            // The `syncRoles` method comes from the Spatie package.
            $roleIds = [];
            if (!empty($data['roles'])) {
                $roleIds = array_map('intval', $data['roles']);
            }
            $user->syncRoles($roleIds);

            return $user->fresh();
        });
    }
}
