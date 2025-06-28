<?php

use BoukjijTarik\WooSales\Http\Controllers\WooOrdersController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'woo-orders', 'middleware' => 'auth:admin'], function () {
    Route::get('/', [WooOrdersController::class, 'index'])->name('woo-orders.index');
    Route::get('/data', [WooOrdersController::class, 'getData'])->name('woo-orders.data');
    Route::post('/export', [WooOrdersController::class, 'export'])->name('woo-orders.export');
}); 