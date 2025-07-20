<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

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
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $filters = $request->only(['search', 'role', 'status', 'sort_by', 'sort_direction']);

        $users = $this->userService->getUsersForIndex($filters);

        return UserResource::collection($users);
    }
}
