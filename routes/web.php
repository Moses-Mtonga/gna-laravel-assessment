<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\ModuleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::middleware(['auth'])->group(function () {
    Route::get('/', [Controller::class, 'index'])->name('dashboard');

    Route::resource('farmers', FarmerController::class);

    // module management routes
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::post('/modules', [ModuleController::class, 'update'])->name('modules.update');
    Route::get('/modules/install/{module}', [ModuleController::class, 'install'])->name('modules.install');
    Route::get('/modules/delete/{module}', [ModuleController::class, 'delete'])->name('modules.delete');
});

Auth::routes();
