<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Services\PermissionService; // Import the service
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => 'required|string|unique:permissions,slug|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        $this->permissionService->createNewPermission($validated);

        return redirect()->route('admin.permissions.index')
                         ->with('success', 'Permission created successfully.');
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
    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:100|unique:permissions,slug,' . $permission->id,
            'description' => 'nullable|string|max:255',
        ]);

        $this->permissionService->updatePermission($permission->id, $validated);

        return redirect()->route('admin.permissions.index')
                         ->with('success', 'Permission updated successfully.');
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Permission $permission): RedirectResponse
    {
        $this->permissionService->togglePermissionStatus($permission->id);
        return redirect()->route('admin.permissions.index')->with('success', 'Permission status updated successfully.');
    }
}
