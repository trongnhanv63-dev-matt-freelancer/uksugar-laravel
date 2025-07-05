<?php

use App\Http\Controllers\Admin\Api\UserController;
use Illuminate\Support\Facades\Route;

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    // Route to get the filtered/sorted/paginated list of users
    Route::get('/admin/users', [UserController::class, 'index'])->name('api.admin.users.index');
});
