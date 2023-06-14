<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', function(){
        return auth()->user();
    });
});

// Currency Routes
Route::get('currencies', [CurrencyController::class, 'index']);
Route::post('currencies', [CurrencyController::class, 'store']);

// Server Routes
Route::get('servers', [ServerController::class, 'index']);
Route::get('servers/fetch', [ServerController::class, 'fetch']);
Route::get('servers/fetch/single/{slug}', [ServerController::class, 'single']);
Route::patch('server/update/status/{id}', [ServerController::class, 'updateStatus']);
Route::post('servers', [ServerController::class, 'store']);

// Product Routes
Route::get('products', [ProductController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::patch('product/update/status/{id}', [ProductController::class, 'updateStatus']);
Route::get('product/{product}', [ProductController::class, 'product']);

// Authentication Routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);