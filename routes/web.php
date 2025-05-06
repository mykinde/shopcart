<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product_code}', [ProductController::class, 'viewProduct'])->name('products.view');
Route::post('/add-to-cart', [ProductController::class, 'addToCart']);
Route::post('/bulk-add-to-cart', [ProductController::class, 'bulkAddToCart']);

Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::get('clear-cart', [CartController::class, 'clearCart']);
Route::post('/remove-from-cart', [CartController::class, 'deleteProduct'])->name('delete.cart.product');

Route::get('/search', [ProductController::class, 'productSearch'])->name('products.search');

