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

Route::controller(App\Http\Controllers\API\SaleController::class)
    ->group(function () {
        Route::get('/sales', 'index')->name('sales.index');
        Route::post('/sales', 'store')->name('sales.store');
        Route::get('/sale/{sale:id}', 'show')->name('sales.show');
        Route::put('/sale/{sale:id}', 'update')->name('sales.update');
        Route::delete('/sale/{sale:id}', 'destroy')->name('sales.destroy');
    });
