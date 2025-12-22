<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Get all products
Route::get('/products', [ProductController::class, 'index']);

// Create product
Route::post('/products', [ProductController::class, 'store']);

// Get single product
Route::get('/products/{id}', [ProductController::class, 'show']);

// Update product
Route::put('/products/{id}', [ProductController::class, 'update']);

// Delete product
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
