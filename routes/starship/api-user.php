<?php

use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return 'user';
});

Route::controller(Controllers\AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', 'register');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(Controllers\AuthController::class)->prefix('abc')->group(function () {
        Route::get('/', 'index');
    });

    Route::middleware(['ability:api-student'])->group(function () {

    });

    Route::middleware(['ability:api-teacher'])->group(function () {

    });
});
