<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SingInController;
use App\Http\Controllers\Admin\CrudUserController;
use App\Http\Controllers\Users\ViewUserController;
use App\Http\Controllers\Users\LoansController;
use App\Http\Controllers\Secretary\ViewSecretary;
use App\Http\Controllers\Secretary\LoanSecController;
use App\Http\Controllers\Secretary\LoansApprovedController;
use App\Http\Middleware\NormalUserMiddleware;
use App\Http\Middleware\SecretaryMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\ViewAdminController;
use App\Http\Controllers\Admin\CrudResourcesController;
use App\Http\Controllers\Secretary\ResourcesController;

use App\Http\Controllers\Admin\CrudLoanController;


/* ruta de prueba*/

/*Inicio de sesión */
Route::resource('login', SingInController::class)->only([
    'index', 'store'
]);
Route::get('/login', [SingInController::class, 'index'])->name('login');

// Ruta personalizada para cerrar sesión
Route::post('logout', [SingInController::class, 'destroy'])->name('logout');

/*Rutas usuario normal */
Route::middleware([NormalUserMiddleware::class])->group(function () {
    Route::resource('/', ViewUserController::class)->only([
        'index', 'store', 'destroy'
    ]);
    Route::resource('/Loans', LoansController::class)->only([
        'index', 'store', 'destroy'
    ]);
});

/*Rutas Secretaria*/
Route::middleware([SecretaryMiddleware::class])->group(function () {
    Route::resource('/Secretary', ViewSecretary::class)->only([
        'index', 'store', 'destroy', 'update' // Agrega 'update' aquí
    ])->names([
        'index' => 'Secretary.index',
        'store' => 'Secretary.store',
        'destroy' => 'Secretary.destroy',
        'update' => 'Secretary.update', // Define el nombre para 'update'
    ]);

    Route::patch('/Secretary/{id}', [ViewSecretary::class, 'update'])->name('Secretary.update');

    Route::resource('/LoanSec', LoanSecController::class)->only([
        'index', 'store', 'destroy'
    ]);
    Route::resource('/resources', ResourcesController::class)->only([
        'index', 'store', 'update', 'destroy'
    ])->names([
        'index' => 'CResource.index',
        'store' => 'CResource.store',
        'update' => 'CResource.update',
        'destroy' => 'CResource.destroy',
    ]);
    Route::resource('/LoansApproved', LoansApprovedController::class)->only([
        'index', 'store', 'destroy']);
    Route::post('/approve/{id}', [LoansApprovedController::class, 'approve'])
        ->name('approve.resource');
    Route::post('/reject/{id}', [LoansApprovedController::class, 'reject'])
        ->name('reject.resource');

    // Nueva ruta para generar el PDF con los préstamos del día
    Route::get('/generate-pdf', [LoansApprovedController::class, 'generatePDF'])
        ->name('generate.pdf');
    
});

/*Ruta Admin*/
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::resource('Admin', ViewAdminController::class)->only([
        'index', 'store', 'destroy'
    ]);
    Route::resource('/CrudUser', CrudUserController::class)->only([
        'index', 'edit', 'store', 'destroy'
    ])->names([
        'index' => 'CrudUser.index',
        'edit' => 'CrudUser.edit',
        'store' => 'CrudUser.store',
        'destroy' => 'CrudUser.destroy',
    ]);
    Route::resource('/CrudResource', CrudResourcesController::class)->only([
        'index', 'store', 'update', 'destroy'
    ])->names([
        'index' => 'CrudResource.index',
        'store' => 'CrudResource.store',
        'update' => 'CrudResource.update',
        'destroy' => 'CrudResource.destroy',
    ]);
    
    Route::resource('/CrudLoans', CrudLoanController::class)->only([
        'index', 'store', 'update', 'destroy'
    ])->names([
        'index' => 'CrudLoan.index',
        'store' => 'CrudLoan.store',
        'update' => 'CrudLoan.update',
        'destroy' => 'CrudLoan.destroy',
    ]);
});
