<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest; // We only need the service now
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class UserController extends Controller
{
    use AuthorizesRequests;
    // The controller now ONLY depends on the UserService.
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = $this->userService->getUsersForIndex();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $roles = $this->userService->getRolesForForm();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $this->userService->createNewUser($request->validated());
            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'There was an issue creating the user. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $roles = $this->userService->getRolesForForm();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            $this->userService->updateUser($user->id, $request->validated());
            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'There was an issue updating the user. Please try again.');
        }
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        try {
            $this->userService->toggleUserStatus($user->id);
            return redirect()->route('admin.users.index')->with('success', 'User status updated successfully.');
        } catch (Throwable $e) {
            report($e);
            return redirect()->route('admin.users.index')->with('error', $e->getMessage());
        }
    }
}
