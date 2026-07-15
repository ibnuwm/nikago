<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function (): void {
            Route::prefix('auth')->group(__DIR__ . '/../routes/auth.php');
            Route::prefix('api')->group(__DIR__ . '/../routes/dashboard.php');
            Route::prefix('api')->group(__DIR__ . '/../routes/cms.php');
            Route::prefix('api')->group(__DIR__ . '/../routes/wedding.php');
            Route::prefix('api')->group(__DIR__ . '/../routes/invitation.php');
            Route::prefix('api')->group(__DIR__ . '/../routes/template.php');
            Route::prefix('api')->group(__DIR__ . '/../routes/rsvp.php');
            Route::prefix('api')->group(__DIR__ . '/../routes/guest.php');
            Route::prefix('api')->group(__DIR__ . '/../routes/planner.php');
            Route::prefix('api')->group(__DIR__ . '/../routes/checklist.php');
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
