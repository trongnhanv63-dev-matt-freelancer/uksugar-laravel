<?php

use App\Livewire\Auth\LoginForm;
use App\Livewire\Auth\Logout;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', LoginForm::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', Logout::class . '@logout');
});
