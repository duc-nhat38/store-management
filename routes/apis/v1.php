<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('test', function () {
        return response()->json([
            'mode' => config('app.env'),
            'debug' => config('app.debug'),
            'app_version' => app()->version(),
            'api_version' => 'v1'
        ]);
    });
});
