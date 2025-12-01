<?php

use App\Http\Controllers\API\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Orders
    Route::get('/orders/search', [OrderController::class, 'search']);
    Route::apiResource('orders', OrderController::class);
    
    // You can add more resource routes here as needed
    // Route::apiResource('agents', AgentController::class);
    // Route::apiResource('locations', LocationController::class);
});
