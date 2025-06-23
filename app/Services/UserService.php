<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
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

    // --- NEW METHOD FOR INDEX PAGE ---
    public function getUsersForIndex(): Collection
    {
        return $this->userRepository->getAllWithRoles();
    }

    // --- NEW METHOD FOR CREATE/EDIT FORMS ---
    public function getRolesForForm(): Collection
    {
        // For now, we get all roles. We can filter for active roles later if needed.
        return $this->roleRepository->getAllWithPermissions();
    }

    // ... (createNewUser, updateUser, and toggleUserStatus methods remain the same) ...
    public function createNewUser(array $data): User
    {
        $user = $this->userRepository->create(
            Arr::except($data, ['roles'])
        );

        if (!empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return $user;
    }

    public function updateUser(int $userId, array $data): User
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $userToUpdate = $this->userRepository->findById($userId);
        // Add this check
        if ($userToUpdate->is_super_admin) {
            throw new Exception('The Super Admin user cannot be modified.');
        }

        $user = $this->userRepository->update($userId, Arr::except($data, ['roles']));

        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        } else {
            $user->roles()->sync([]);
        }

        return $user;
    }
}
