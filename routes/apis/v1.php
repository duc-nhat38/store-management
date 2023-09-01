<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
        });
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
