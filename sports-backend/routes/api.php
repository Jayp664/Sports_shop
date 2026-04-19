<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;

// ─── PUBLIC ROUTES ───────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Categories (public)
Route::get('/categories',      [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Products (public)
Route::get('/products',        [ProductController::class, 'index']);
Route::get('/products/brands', [ProductController::class, 'brands']);
Route::get('/products/{id}',   [ProductController::class, 'show']);

// ─── PROTECTED ROUTES (need token) ───────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // ─── ADMIN ONLY ROUTES ────────────────────
    Route::prefix('admin')->group(function () {

        // Categories
        Route::post('/categories',        [CategoryController::class, 'store']);
        Route::put('/categories/{id}',    [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // Products
        Route::post('/products',        [ProductController::class, 'store']);
        Route::put('/products/{id}',    [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    });
});
