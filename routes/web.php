<?php

use App\Http\Controllers\Admin\Api\UserController as AdminApiUserController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController; // Import the middleware class
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//==========================================================================
// ADMIN ROUTES
//==========================================================================
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminLoginController::class, 'login']);
    });

    // Protected Admin Area using the full class name for the middleware.
    Route::middleware(['auth', 'can:admin.panel.access'])->group(function () {
        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::controller(RoleController::class)->prefix('roles')->name('roles.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:roles.view');
            Route::get('/create', 'create')->name('create')->middleware('can:roles.create');
            Route::post('/', 'store')->name('store')->middleware('can:roles.create');
            Route::get('/{role}/edit', 'edit')->name('edit')->middleware('can:roles.edit');
            Route::put('/{role}', 'update')->name('update')->middleware('can:roles.edit');
            Route::patch('/{role}/toggle-status', 'toggleStatus')->name('toggleStatus')->middleware('can:roles.edit');
        });
        Route::controller(PermissionController::class)->prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:permissions.view');
            Route::get('/create', 'create')->name('create')->middleware('can:permissions.create');
            Route::post('/', 'store')->name('store')->middleware('can:permissions.create');
            Route::get('/{permission}/edit', 'edit')->name('edit')->middleware('can:permissions.edit');
            Route::put('/{permission}', 'update')->name('update')->middleware('can:permissions.edit');
            Route::patch('/{permission}/toggle-status', 'toggleStatus')->name('toggleStatus')->middleware('can:permissions.edit');
        });
        Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('can:users.view');
            Route::get('/create', 'create')->name('create')->middleware('can:users.create');
            Route::post('/', 'store')->name('store')->middleware('can:users.create');
            Route::get('/{user}/edit', 'edit')->name('edit')->middleware('can:users.edit');
            Route::put('/{user}', 'update')->name('update')->middleware('can:users.edit');
        });
    });
});

// --- THÊM VÀO ĐOẠN MÃ NÀY ---
Route::middleware(['auth', 'verified']) // Đảm bảo người dùng đã đăng nhập
    ->prefix('api/admin')             // Thêm tiền tố /api/admin cho tất cả các route bên trong
    ->name('admin.api.')              // Thêm tiền tố tên route là admin.api.
    ->group(function () {

        // Route để lấy danh sách người dùng
        Route::get('/users', [AdminApiUserController::class, 'index'])->name('users.index');

    });
// --- KẾT THÚC PHẦN THÊM VÀO ---

//==========================================================================
// PUBLIC ROUTES
//==========================================================================
Route::get('/', function () { return view('welcome'); })->name('home');
