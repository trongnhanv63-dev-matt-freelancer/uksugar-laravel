<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * This controller handles API requests related to Permissions.
 */
class PermissionController extends Controller
{
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Return a paginated list of permissions based on filter criteria.
     * This is the dedicated API endpoint for the permission management table.
     */
    public function index(Request $request): JsonResponse
    {
        // Capture all filter/sort parameters from the request
        $filters = $request->only(['search', 'status', 'sort_by', 'sort_direction']);

        $permissions = $this->permissionService->getPermissionsForIndex($filters);

        // Return data using PermissionResource for consistency
        return PermissionResource::collection($permissions)->response();
    }
}
