<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('client')->group(function () {
    Route::get('/list', [ClientController::class, 'getListClients']);
    Route::post('/create', [ClientController::class, 'createClient']);
    Route::get('detail/{clientId}', [ClientController::class, 'getClient']);
    Route::put('detail/{clientId}', [ClientController::class, 'updateClient']);
    Route::delete('delete/{clientId}', [ClientController::class, 'deleteClient']);
});

Route::prefix('product')->group(function () {
    Route::get('/list', [ProductController::class, 'getListProducts']);
    Route::post('/create', [ProductController::class, 'createProduct']);
    Route::get('detail/{productId}', [ProductController::class, 'getProduct']);
    Route::put('detail/{productId}', [ProductController::class, 'updateProduct']);
    Route::delete('delete/{productId}', [ProductController::class, 'deleteProduct']);
});

Route::prefix('order')->group(function () {
    Route::get('/list', [OrderController::class, 'getListOrders']);
    Route::post('/create', [OrderController::class, 'createOrder']);
    Route::get('detail/{orderId}', [OrderController::class, 'getOrder']);
    Route::put('detail/{orderId}', [OrderController::class, 'updateOrder']);
    Route::delete('delete/{orderId}', [OrderController::class, 'deleteOrder']);
});
