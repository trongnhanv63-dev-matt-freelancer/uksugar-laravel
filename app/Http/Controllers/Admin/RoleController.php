<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:roles,name|max:50',
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            $this->roleService->createNewRole($validatedData);
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

    public function update(Request $request, Role $role): RedirectResponse
    {
        // Validate the request data, the 'name' field is no longer validated on update.
        $validatedData = $request->validate([
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            $this->roleService->updateExistingRole($role->id, $validatedData);
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
