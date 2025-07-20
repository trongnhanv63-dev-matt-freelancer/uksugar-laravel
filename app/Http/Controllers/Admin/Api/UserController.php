<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; // <-- Thêm use statement này

/**
 * This controller handles API requests related to Users.
 */
class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Return a paginated list of users based on filter criteria.
     * This is the dedicated API endpoint for the user management table.
     */
    public function index(Request $request): JsonResponse
    {
        // MODIFIED: Capture all filter/sort parameters from the request
        $filters = $request->only(['search', 'role', 'status', 'sort_by', 'sort_direction']);

        $users = $this->userService->getUsersForIndex($filters);

        // MODIFIED: Return data using UserResource for consistency
        // Laravel's resource collection automatically handles pagination structure.
        return UserResource::collection($users)->response();
    }
}
