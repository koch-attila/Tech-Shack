<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RatingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('ratings', RatingController::class);
Route::apiResource('orders', OrderController::class)->except(['index']);
Route::get('/orders', [OrderController::class, 'index'])->middleware('auth:sanctum');

Route::get('/products/{product}/ratings', [ProductController::class, 'ratings']);
Route::get('/products/{product}/categories', [ProductController::class, 'categories']);
Route::get('/categories/{category}/products', [CategoryController::class, 'products']);
Route::get('/ratings/{rating}/users', [RatingController::class, 'user']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});