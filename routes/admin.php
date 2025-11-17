<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => [
        'auth',
        AdminMiddleware::class,
    ],
], function () {
    Route::get('companies/data-table', \App\Http\Controllers\CompanyDataTableController::class)
        ->name('companies.data-table');
    Route::resource('companies', CompanyController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::get('employees/data-table', \App\Http\Controllers\EmployeeDataTableController::class)
        ->name('employees.data-table');
    Route::resource('employees', EmployeeController::class)
        ->only(['index', 'store', 'update', 'destroy']);
});
