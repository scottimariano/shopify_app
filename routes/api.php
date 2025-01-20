<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware(['auth:sanctum','ability:read'])->group(function () {

    
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

    Route::get('/products/{id}/inventory', [InventoryController::class, 'show']);
    
});

Route::middleware(['auth:sanctum','ability:write'])->group(function () {
   
    Route::post('/products', [ProductController::class, 'store']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    
    
});

// admin
Route::middleware(['auth:sanctum','ability:admin'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/admin/{id}/updateMail', [AdminController::class, 'updateUserEmail']);
    Route::post('/admin/{id}/toggleDailyReport', [AdminController::class, 'toogleDailyReportSuscription']);
    
});