<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController as GuestLoginController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController; // Use an alias

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', function () {
    return view('welcome');
})->name('home');

// == GUEST AUTHENTICATION ROUTES ==
Route::middleware('guest')->group(function () {
    // Registration Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Guest Login Routes
    Route::get('/login', [GuestLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [GuestLoginController::class, 'login']);
});

// == ADMIN AUTHENTICATION & AREA ==
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Login Routes (for guests trying to access admin area)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login']);
    });

    // Admin area routes (for authenticated admins)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            return 'Welcome to ADMIN dashboard, ' . auth()->user()->username . '!';
        })->name('dashboard');

        // Admin Logout
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        // We will add routes for managing roles and permissions here later
    });
});

// == AUTHENTICATED USER AREA ==
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return 'Welcome to your dashboard, ' . auth()->user()->username . '!';
    })->name('dashboard');

    // General Logout for normal users
    Route::post('/logout', [GuestLoginController::class, 'logout'])->name('logout');
});
