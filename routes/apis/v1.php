<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
        });
    });

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('categories', [\App\Http\Controllers\CategoryController::class, 'index'])->middleware('pagination_limit');

        Route::get('trademarks', [\App\Http\Controllers\TrademarkController::class, 'index'])->middleware('pagination_limit');

        Route::apiResource('products', \App\Http\Controllers\ProductControllers::class);
    });

    Route::get('test', function () {
        return response()->json([
            'mode' => config('app.env'),
            'debug' => config('app.debug'),
            'app_version' => app()->version(),
            'api_version' => 'v1'
        ]);
    });
});
