<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * This controller handles API requests related to Roles.
 */
class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Return a paginated list of roles based on filter criteria.
     * This is the dedicated API endpoint for the role management table.
     */
    public function index(Request $request): JsonResponse
    {
        // Capture all filter/sort parameters from the request
        $filters = $request->only(['search', 'status', 'sort_by', 'sort_direction']);

        $roles = $this->roleService->getRolesForIndex($filters);

        // Return data using RoleResource for consistency
        // Laravel's resource collection automatically handles pagination structure.
        return RoleResource::collection($roles)->response();
    }
} 