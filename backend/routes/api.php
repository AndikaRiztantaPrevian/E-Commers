<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Guest Route
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->currentAccessToken()->delete()->middleware('auth:sanctum');
});

Route::get('/', function () {
    return response()->json([
        'status' => false,
        'message' => 'Akses tidak diperbolehkan.',
    ], 401);
})->name('login');

// Admin Route
Route::controller(ProductController::class)->group(function () {
    Route::get('/getProductData', 'getDataProduct');
    Route::post('/product/store', 'store');
    Route::put('/product/update', 'update');
    Route::delete('/product/delete', 'delete');
})->middleware('auth:sanctum', 'ablity:admin-ecommers');

// User Route
Route::controller(ProductController::class)->group(function () {
    Route::get('/getDataProduct', 'getDataProduct');
})->middleware('auth:sanctum');
