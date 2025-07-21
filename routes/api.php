<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\Api\CustomerApiAuthController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ProductController;
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

// Product APIs
Route::get('get/products', [ProductController::class, 'get_products']);
Route::get('new/arrivals', [ProductController::class, 'new_arrivals']);
Route::get('all/products', [ProductController::class, 'all_products']);
Route::get('product/details/{id}', [ProductController::class, 'get_product_details']);

// Category APIs
Route::get('get/categories', [CategoryController::class, 'get_categories']);


