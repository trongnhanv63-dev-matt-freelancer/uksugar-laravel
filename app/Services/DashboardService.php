<?php

namespace App\Services;

// 1. Import the repository interfaces
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

/**
 * Service class for fetching data for the admin dashboard.
 */
class DashboardService
{
    // 2. Define properties for the repositories
    protected UserRepositoryInterface $userRepository;
    protected RoleRepositoryInterface $roleRepository;
    protected PermissionRepositoryInterface $permissionRepository;

    /**
     * Inject repositories into the service container.
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository,
        PermissionRepositoryInterface $permissionRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get all the necessary statistics for the dashboard view.
     *
     * @return array<string, mixed>
     */
    public function getDashboardStatistics(): array
    {
        // 3. Fetch data using repositories instead of direct model calls
        $userCount = $this->userRepository->getAllWithRoles()->count();
        $roleCount = $this->roleRepository->getAllWithPermissions()->count();
        $permissionCount = $this->permissionRepository->getAll()->count();

        // This logic can stay as it is a specific query for this service
        $recentUsers = $this->userRepository->getAllWithRoles()->take(5);

        return [
            'userCount' => $userCount,
            'roleCount' => $roleCount,
            'permissionCount' => $permissionCount,
            'recentUsers' => $recentUsers,
        ];
    }
}
