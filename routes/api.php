<?php

use App\Http\Controllers\BuyController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::fallback(function() {
    return response()->json([
        'status' => false,
        'message' => 'API resource not found!',
    ], 404);
});

Route::post('user', [UserController::class, 'store'])->name('register');
Route::post('user/login', [UserController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('user', UserController::class)->except(['store']);
    Route::apiResource('product', ProductController::class);

    Route::put('deposit', DepositController::class);
    Route::put('buy', BuyController::class);
    Route::put('reset', ResetController::class);

    Route::put('logout/all', [UserController::class, 'logoutAll']);
});
