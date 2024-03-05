<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'OK!',
    ], 200);
});

Route::controller(App\Http\Controllers\API\ProductController::class)
    ->group(function () {
        Route::get('/products', 'index')->name('products.index');
    });
