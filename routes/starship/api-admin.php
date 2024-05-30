<?php

use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/healthcheck', function (Request $request) {
    return 'admin-api-healthy';
});

Route::controller(Controllers\AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', 'login')->name('login');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/token', function (Request $request) {
        return request()->user();
    });

    Route::controller(Controllers\AuthController::class)->prefix('abc')->group(function () {
        Route::get('/', 'index');
    });

});
