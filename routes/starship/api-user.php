<?php

use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/healthcheck', function (Request $request) {
    return 'user-api-healthy';
});

Route::controller(Controllers\AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', 'register');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/token', function (Request $request) {
        return request()->user();
    });

    Route::controller(Controllers\AuthController::class)->prefix('abc')->group(function () {
        Route::get('/', 'index');
    });

    Route::controller(Controllers\CourseController::class)->prefix('course')->group(function () {
        Route::get('/', 'index');
        Route::get('/category', 'category_index');
    });

    Route::middleware(['ability:api-student'])->group(function () {
    });

    Route::middleware(['ability:api-teacher'])->group(function () {
    });
});
