<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\StorePermissionRequest;
use App\Http\Requests\Admin\Permission\UpdatePermissionRequest; // Import the service
use App\Services\PermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use NhanDev\Rbac\Models\Permission;
use Throwable;

class PermissionController extends Controller
{
    // The controller now ONLY depends on the Service layer.
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $permissions = $this->permissionService->getPermissionsForIndex();
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request): RedirectResponse
    {
        try {
            $this->permissionService->createNewPermission($request->validated());
            return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'There was an issue creating the permission. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        try {
            $this->permissionService->updatePermission($permission->id, $request->validated());
            return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'There was an issue updating the permission. Please try again.');
        }
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Permission $permission): RedirectResponse
    {
        try {
            $this->permissionService->togglePermissionStatus($permission->id);
            return redirect()->route('admin.permissions.index')->with('success', 'Permission status updated successfully.');
        } catch (Throwable $e) {
            report($e);
            return redirect()->route('admin.permissions.index')->with('error', $e->getMessage());
        }
    }
}
