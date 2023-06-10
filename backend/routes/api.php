<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('servers', [ServerController::class, 'index']);
Route::get('servers/fetch', [ServerController::class, 'fetch']);
Route::get('servers/fetch/single/{slug}', [ServerController::class, 'single']);
Route::patch('server/update/status/{id}', [ServerController::class, 'updateStatus']);
Route::post('servers', [ServerController::class, 'store']);

Route::get('products', [ProductController::class, 'index']);
Route::post('product', [ProductController::class, 'store']);
Route::patch('product/update/status/{id}', [ProductController::class, 'updateStatus']);



// Authentication Routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);