<?php

use Illuminate\Support\Facades\Route;
use Modules\LoanManagement\Http\Controllers\LoanManagementController;

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

Route::group(['prefix' => 'loans'], function () {

    Route::resource('loanmanagement', LoanManagementController::class)->names('loanmanagement');
    Route::post('/loanmanagement/{id}/approve', [LoanManagementController::class, 'approve'])->name('loanmanagement.approve');
    Route::post('/loanmanagement/{id}/reject', [LoanManagementController::class, 'reject'])->name('loanmanagement.reject');
    Route::post('/loanmanagement/{id}/repaid', [LoanManagementController::class, 'repaid'])->name('loanmanagement.repaid');
});
