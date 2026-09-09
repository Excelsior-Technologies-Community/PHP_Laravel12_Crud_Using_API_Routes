<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


// 📊 Product Statistics
Route::get('/products/statistics', [ProductController::class, 'statistics']);


// 🔎 GET: Products
// Supports:
// /api/products
// /api/products?search=mobile
// /api/products?min_price=1000
// /api/products?max_price=50000
// /api/products?search=mobile&min_price=10000&max_price=50000
// /api/products?page=2
Route::get('/products', [ProductController::class, 'index']);


// POST: Create Product
Route::post('/products', [ProductController::class, 'store']);

Route::get('/products/statistics', [ProductController::class, 'statistics']);

// GET: Single Product
Route::get('/products/{id}', [ProductController::class, 'show']);


// PUT: Update Product
Route::put('/products/{id}', [ProductController::class, 'update']);


// DELETE: Product
Route::delete('/products/{id}', [ProductController::class, 'destroy']);