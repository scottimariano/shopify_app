<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopifyWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','ability:read,admin'])->group(function () {
 
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/{id}/inventory', [InventoryController::class, 'show']);
    
});

Route::middleware(['auth:sanctum','ability:write,admin'])->group(function () {
   
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    
});

// admin
Route::middleware(['auth:sanctum','ability:admin'])->group(function () {

    Route::put('/admin/{id}/updateMail', [AdminController::class, 'updateUserEmail']);
    Route::post('/admin/{id}/toggleDailyReport', [AdminController::class, 'toogleDailyReportSuscription']);
    
});

Route::post('/webhooks/shopify/createorder', [ShopifyWebhookController::class, 'createOrder']);