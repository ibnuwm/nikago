<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

// Email verification (requires signed URL)
Route::get('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/resend-verification', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->name('verification.resend');

// Authenticated routes
Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/me', [UserController::class, 'show'])
        ->name('me');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/password', [PasswordController::class, 'update'])
        ->name('password.update');
});

// Google OAuth placeholder
Route::post('/google', function () {
    return response()->json([
        'success' => false,
        'message' => 'Google OAuth is not yet implemented.',
    ], 501);
})->name('google');
