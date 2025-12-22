<?php

use Illuminate\Support\Facades\Route;

Route::view('/products', 'products.index');
Route::view('/products/create', 'products.create');
Route::view('/products/edit/{id}', 'products.edit');
