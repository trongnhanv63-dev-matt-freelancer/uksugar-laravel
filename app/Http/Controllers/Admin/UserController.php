<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class UserController extends Controller
{
    /**
     * The service for handling user business logic.
     */
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the users.
     */
    public function index(): View
    {
        $users = $this->userService->getUsersForIndex([]);
        $roles = $this->userService->getRolesForForm(except: ['super-admin']);
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = $this->userService->getRolesForForm();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $this->userService->createNewUser($request->validated());
            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'There was an issue creating the user.');
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $roles = $this->userService->getRolesForForm(except: ['super-admin']);
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            $this->userService->updateUser($user->id, $request->validated());
            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
