<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Presentation\UserManagement\Controllers\UserAuthController;

// Public Guest Authentication Routes
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [UserAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('register.post');
});

// Authenticated User Routes
Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
});
