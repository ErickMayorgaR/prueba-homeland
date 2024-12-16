<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    $products = Product::all();
    return view('products', ['products' => $products]);
});



Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);
Route::get('/products/{id}', [ProductController::class, 'show']); // Obtener producto específico
Route::put('/products/{id}', [ProductController::class, 'update']); // Actualizar producto