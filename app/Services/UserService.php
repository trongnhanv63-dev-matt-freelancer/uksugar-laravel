<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Arr;

class UserService
{
    protected UserRepositoryInterface $userRepository;
    protected RoleRepositoryInterface $roleRepository;

    public function __construct(UserRepositoryInterface $userRepository, RoleRepositoryInterface $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function createNewUser(array $data): User
    {
        $user = $this->userRepository->create(
            Arr::except($data, ['roles']) // Create user without the roles attribute
        );

        if (!empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return $user;
    }

    public function updateUser(int $userId, array $data): User
    {
        // If password field is empty, remove it from the data array
        // so we don't overwrite the existing password with an empty one.
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // The 'status' field is now part of the $data array passed from the controller
        $user = $this->userRepository->update($userId, Arr::except($data, ['roles']));

        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        } else {
            // If no roles are provided, remove all existing roles
            $user->roles()->sync([]);
        }

        return $user;
    }
}
