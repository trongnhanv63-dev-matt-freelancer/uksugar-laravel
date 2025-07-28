<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\StorePermissionRequest;
use App\Http\Requests\Admin\Permission\UpdatePermissionRequest;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class PermissionController extends Controller
{
    /**
     * The service for handling permission business logic.
     */
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the permissions.
     */
    public function index(): View
    {
        $permissions = $this->permissionService->getPermissionsForIndex();
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create(): View
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created permission in storage.
     */
    public function store(StorePermissionRequest $request): RedirectResponse
    {
        try {
            $this->permissionService->createNewPermission($request->validated());
            return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'There was an issue creating the permission.');
        }
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        try {
            $this->permissionService->updatePermission($permission, $request->validated());
            return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'There was an issue updating the permission.');
        }
    }

    /**
     * Toggle the status of the specified permission.
     */
    public function toggleStatus(Permission $permission): RedirectResponse
    {
        try {
            $this->permissionService->togglePermissionStatus($permission);
            return redirect()->route('admin.permissions.index')->with('success', 'Permission status updated successfully.');
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', $e->getMessage());
        }
    }
}
