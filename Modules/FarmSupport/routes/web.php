<?php

use Illuminate\Support\Facades\Route;
use Modules\FarmSupport\Http\Controllers\FarmSupportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'farm-support'], function () {
    Route::post('products/store', [FarmSupportController::class, 'storeProducts'])->name('supported.products.store');
    Route::delete('products/destory/{productId}', [FarmSupportController::class, 'destroyProduct'])->name('supported.products.destroy');
    Route::put('products/update/{productId}', [FarmSupportController::class, 'updateProduct'])->name('supported.products.update');
    Route::resource('farmsupport', FarmSupportController::class);
});
