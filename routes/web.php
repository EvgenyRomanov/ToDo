<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', [UserController::class, 'home'])->name('user.index');
Route::get('/register', [UserController::class, 'registerDisplay'])->name('user.registerDisplay');
Route::post('/register', [UserController::class, 'register'])->name('user.register');
Route::get('/login', [UserController::class, 'login'])->name('user.loginDisplay');
Route::get('/logout', [UserController::class, 'logout'])->name('user.logout');
Route::post('/login', [UserController::class, 'authenticate'])->name('user.login');

Route::resource('tasks', TaskController::class)->middleware('auth');

Route::get('/test', function () {
   Log::info("test message info");
   Log::error("test message error");
   Log::critical("test message critical");
   echo "Test";
});
