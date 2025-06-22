<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

// Nhóm các route đăng ký và áp dụng middleware 'guest' cho cả nhóm
Route::middleware('guest')->group(function () {
    // Route để hiển thị form đăng ký
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

    // Route để xử lý dữ liệu từ form đăng ký
    Route::post('/register', [RegisterController::class, 'register']);
});


// Tạm thời tạo một route cho dashboard để có nơi chuyển hướng đến sau khi đăng ký thành công
Route::get('/dashboard', function () {
    return 'Welcome to your dashboard, ' . auth()->user()->username . '!';
})->middleware('auth')->name('dashboard');

// Route mặc định
Route::get('/', function () {
    return view('welcome');
});
