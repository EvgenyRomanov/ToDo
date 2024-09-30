<?php

use App\Http\Controllers\Api\JWT\LoginController;
use App\Http\Controllers\Api\JWT\UserController as UserControllerJWT;
use App\Http\Controllers\Api\Passport\OAuthController;
use App\Http\Controllers\Api\Passport\TaskController;
use App\Http\Controllers\Api\Passport\UserController as UserControllerPassport;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function (): void {
    Route::middleware(['auth:jwt'])->group(function () {
        Route::apiResource('users', UserControllerJWT::class);
    });
    Route::post('login', [LoginController::class, 'login']);
});

Route::group(['prefix' => 'v2'], function (): void {
    Route::middleware(['auth:api'])->group(function () {
        Route::apiResource('tasks', TaskController::class);
        Route::apiResource('users', UserControllerPassport::class);
    });
    Route::post('login', [OAuthController::class, 'login']);
});
