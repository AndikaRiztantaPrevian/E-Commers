<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/getProductData', 'getDataProduct');
    Route::post('/product/store', 'store');
    Route::put('/product/update', 'update');
    Route::delete('/product/delete', 'delete');
})->middleware('auth:sanctum');