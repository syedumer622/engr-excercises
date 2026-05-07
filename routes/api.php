<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/exercise-1-artwork-version', [IndexController::class, 'artworkVersion']); // // exercise = 1
Route::post('/exercise-2-tier-pricing', [IndexController::class, 'tierPricing']); // exercise = 2
Route::post('/exercise-3-cart-validator', [IndexController::class, 'cartValidator']); // exercise = 3
Route::post('/exercise-4-vendor-allocation', [IndexController::class, 'vendorAllocation']); // exercise = 4
Route::post('/exercise-5-discount', [IndexController::class, 'discountSelection']); // exercise = 5
Route::post('/exercise-6-approval-flow', [IndexController::class, 'approvalFlow']); // exercise = 6
Route::post('/exercise-7-inventory', [IndexController::class, 'inventoryReservationEngine']); // exercise = 7
Route::post('/exercise-8-shipment', [IndexController::class, 'partialShipmentTracker']); // exercise = 8
