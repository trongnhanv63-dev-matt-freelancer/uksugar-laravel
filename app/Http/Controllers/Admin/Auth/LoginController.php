<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest; // Assuming you have a LoginRequest
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Display the admin login view.
     */
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an admin login request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        // 1. Data is validated by LoginRequest.
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // 2. Call the service to attempt authentication.
        $isAuthenticated = $this->authService->attemptLogin($credentials, $remember);

        // 3. If authentication is successful, proceed to authorization.
        if ($isAuthenticated) {
            // 4. Authorize: Check if the now-authenticated user has permission to access the panel.
            // The 'can' helper uses the Gate we defined in AuthServiceProvider.
            if ($request->user()->can('admin.panel.access')) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            // If they are a valid user but not an admin, log them out immediately.
            Auth::logout();
        }

        // If authentication or authorization fails, redirect back with a generic error.
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or you do not have access.',
        ])->onlyInput('email');
    }

    /**
     * Log the admin user out.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
