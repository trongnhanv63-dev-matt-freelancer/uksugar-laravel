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

        if (empty($except)) {
            return $allActiveRoles;
        }

        return $allActiveRoles->whereNotIn('name', $except);
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
    public function updateUser(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            // If password field is empty or null, remove it from the data array.
            $updateData = empty($data['password']) ? Arr::except($data, 'password') : $data;

            // These fields are not meant to be updated from this form.
            $updateData = Arr::except($updateData, ['roles', 'email', 'username']);

            $this->userRepository->update($user, $updateData);

            if (isset($data['roles'])) {
                $this->syncRolesAndLog($user, $data['roles']);
            }

            return $user->fresh();
        });
    }

    /**
     * Sync user roles and log the activity.
     */
    private function syncRolesAndLog(User $user, array $roles): void
    {
        $roleIds = array_map('intval', $roles);
        $oldRoleNames = $user->roles->pluck('name');

        $user->syncRoles($roleIds);
        $newRoleNames = $user->fresh()->roles->pluck('name');

        if ($oldRoleNames->diff($newRoleNames)->isNotEmpty() || $newRoleNames->diff($oldRoleNames)->isNotEmpty()) {
            activity()
                ->performedOn($user)
                ->causedBy(auth()->user())
                ->withProperty('old', $oldRoleNames->toArray())
                ->withProperty('new', 'new', $newRoleNames->toArray())
                ->log('User roles updated');
        }
    }
}
