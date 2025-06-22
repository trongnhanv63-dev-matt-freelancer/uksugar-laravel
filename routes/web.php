<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController as PublicLoginController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Middleware\EnsureUserHasRole; // Import the middleware class

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
    Route::middleware(['auth', EnsureUserHasRole::class . ':super-admin'])->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

        Route::resource('roles', RoleController::class);
    });
});


//==========================================================================
// PUBLIC ROUTES
//==========================================================================
Route::get('/', function () { return view('welcome'); })->name('home');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('login', [PublicLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [PublicLoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return 'Welcome to your public dashboard, ' . auth()->user()->username . '!';
    })->name('dashboard');

    Route::post('logout', [PublicLoginController::class, 'logout'])->name('logout');
});
