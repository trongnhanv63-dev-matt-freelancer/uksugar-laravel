<?php

namespace NhanDev\Rbac\Services;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use NhanDev\Rbac\Repositories\Contracts\RoleRepositoryInterface;
use NhanDev\Rbac\Repositories\Contracts\UserRepositoryInterface;
use NhanDev\Rbac\Services\Concerns\LogsRelationshipChanges;

class UserService
{
    use LogsRelationshipChanges;

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
        return DB::transaction(function () use ($data) {
            $user = $this->userRepository->create(
                Arr::except($data, ['roles'])
            );

            if (!empty($data['roles'])) {
                $user->roles()->sync($data['roles']);
            }

            return $user;
        });
    }

    public function updateUser(int $userId, array $data): User
    {
        return DB::transaction(function () use ($userId, $data) {
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $userToUpdate = $this->userRepository->findById($userId);

            if ($userToUpdate->is_super_admin) {
                throw new Exception('The Super Admin user cannot be modified.');
            }

            $user = $this->userRepository->update($userId, Arr::except($data, ['roles']));

            if (isset($data['roles'])) {
                $changes = $user->roles()->sync($data['roles']);
                $this->logSyncActivity($user, "Updated roles for user '{$user->username}'", $changes);
            } else {
                $user->roles()->sync([]);
            }

            return $user;
        });
    }

    public function toggleUserStatus(int $userId): User
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->userRepository->findById($userId);

            if ($user->is_super_admin) {
                throw new Exception('The Super Admin user status cannot be changed.');
            }

            $newStatus = $user->status === config('rbac.user_statuses.active')
                ? config('rbac.user_statuses.inactive')
                : config('rbac.user_statuses.active');

            $this->userRepository->update($userId, ['status' => $newStatus]);

            return $user->fresh();
        });
    }
}
