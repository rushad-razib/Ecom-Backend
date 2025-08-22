<?php

use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SearchFilterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/customer/info', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Customer Auth APIs
Route::post('customer/register', [CustomerController::class, 'register']);
Route::post('customer/login', [CustomerController::class, 'login']);
Route::post('customer/logout', [CustomerController::class, 'logout']);
Route::post('customer/update/{id}', [CustomerController::class, 'customer_update']);
Route::get('myorders/{id}', [CustomerController::class, 'myorders']);
Route::get('/order/invoice/download/{order_id}', [CustomerController::class, 'order_invoice_download']);


// Product APIs
Route::get('get/products', [ProductController::class, 'get_products']);
Route::get('new/arrivals', [ProductController::class, 'new_arrivals']);
Route::get('all/products', [ProductController::class, 'all_products']);
Route::get('product/details/{id}', [ProductController::class, 'get_product_details']);

// Category APIs
Route::get('get/categories', [SearchFilterController::class, 'get_categories']);
Route::get('get/colors', [SearchFilterController::class, 'get_colors']);
Route::post('product/search', [SearchFilterController::class, 'search']);

// Cart APIs
Route::post('/cart/store', [CartController::class, 'cart_store']);
Route::get('/cart/{id}', [CartController::class, 'cart']);
Route::post('/cart/update', [CartController::class, 'cart_update']);

// Order APIs
Route::post('coupon/apply', [CheckoutController::class, 'coupon_apply']);
Route::post('order', [CheckoutController::class, 'order']);




