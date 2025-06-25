<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use App\Services\RoleService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use NhanDev\Rbac\Models\Role;
use Throwable;

class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(): View
    {
        $roles = $this->roleService->getRolesForIndex();
        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = $this->roleService->getPermissionsForForm();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        // Validation and Authorization are already done by StoreRoleRequest.
        // We can directly get the validated data.
        try {
            $this->roleService->createNewRole($request->validated());
            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
        } catch (Throwable $e) {
            // Log the detailed error for the developer
            report($e);
            // Redirect back with a user-friendly error message
            return back()->withInput()->with('error', 'There was an issue creating the role. Please try again.');
        }


    }

    public function edit(Role $role): View
    {
        $permissions = $this->roleService->getPermissionsForForm();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        try {
            // Validation and Authorization are already done by UpdateRoleRequest.
            $this->roleService->updateExistingRole($role->id, $request->validated());
            return redirect()->route('admin.roles.index')
                             ->with('success', 'Role updated successfully.');
        } catch (Throwable $e) {
            // Log the detailed error for the developer
            report($e);
            // Redirect back with a user-friendly error message
            return back()->withInput()->with('error', 'There was an issue updating the role. Please try again.');
        }

    }

    public function toggleStatus(Role $role): RedirectResponse
    {
        try {
            $this->roleService->toggleRoleStatus($role->id);
            return redirect()->route('admin.roles.index')->with('success', 'Role status updated successfully.');
        } catch (Exception $e) {
            // Log the detailed error for the developer
            report($e);
            // Redirect back with a user-friendly error message
            return redirect()->route('admin.roles.index')->with('error', $e->getMessage());
        }
    }
}
