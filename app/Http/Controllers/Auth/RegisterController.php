<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * The authentication service instance.
     */
    protected AuthService $authService;

    /**
     * Create a new controller instance.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        // 1. Dữ liệu đã được tự động validate bởi RegisterRequest.

        // 2. Gọi Service để thực hiện logic nghiệp vụ tạo người dùng.
        $user = $this->authService->registerUser($request->validated());

        // 3. Tự động đăng nhập cho người dùng vừa được tạo.
        Auth::login($user);

        // 4. Chuyển hướng người dùng đến trang dashboard.
        return redirect()->route('dashboard');
    }
}
