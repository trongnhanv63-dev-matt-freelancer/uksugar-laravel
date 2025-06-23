<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService; // We only need the service now
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

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
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
            'status' => ['required', 'string'],
        ]);

        $this->userService->createNewUser($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
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
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:users,username,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', Password::min(8)->symbols()->mixedCase()->numbers()->uncompromised()],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
            'status' => ['required', 'string'],
        ]);

        $this->userService->updateUser($user->id, $validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
}
