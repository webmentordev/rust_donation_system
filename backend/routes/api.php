<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('servers', [ServerController::class, 'index']);
Route::get('servers/fetch', [ServerController::class, 'fetch']);
Route::post('servers', [ServerController::class, 'store']);

// Authentication Routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);