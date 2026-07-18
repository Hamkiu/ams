<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditTemplateController;
use App\Http\Controllers\AuditTemplateItemsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    Route::middleware('role:Admin|Auditor')->group(function () {

        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        });

    });

    Route::middleware('role:Admin')->group(function () {

        Route::prefix('audittrail')->group(function () {
            Route::get('/', [AuditTrailController::class, 'index'])->name('audittrail');
            Route::any('list', [AuditTrailController::class, 'list'])->name('audittrail.list');
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user');
            Route::any('list', [UserController::class, 'list'])->name('user.list');
            Route::get('create', [UserController::class, 'create'])->name('user.create');
            Route::get('edit/{id}', [UserController::class, 'edit'])->name('user.edit');
            Route::post('store', [UserController::class, 'store'])->name('user.store');
            Route::post('update/{id}', [UserController::class, 'update'])->name('user.update');
            Route::get('destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
            Route::get('actionStatus/{id}/{status}', [UserController::class, 'actionStatus'])->name('user.actionStatus');
        });

        Route::prefix('audittemplate')->group(function () {
            Route::get('/', [AuditTemplateController::class, 'index'])->name('audittemplate');
            Route::any('list', [AuditTemplateController::class, 'list'])->name('audittemplate.list');
            Route::post('store', [AuditTemplateController::class, 'store'])->name('audittemplate.store');
            Route::get('edit/{id}', [AuditTemplateController::class, 'edit'])->name('audittemplate.edit');
            Route::post('update/{id}', [AuditTemplateController::class, 'update'])->name('audittemplate.update');
            Route::get('destroy/{id}', [AuditTemplateController::class, 'destroy'])->name('audittemplate.destroy');
            Route::get('show/{id}', [AuditTemplateController::class, 'show'])->name('audittemplate.show');

            Route::prefix('templateitems')->group(function () {
                Route::get('/{id}', [AuditTemplateItemsController::class, 'index'])->name('audittemplate.items');
                Route::any('list/{id}', [AuditTemplateItemsController::class, 'list'])->name('audittemplate.items.list');
                Route::get('create/{id}', [AuditTemplateItemsController::class, 'create'])->name('audittemplate.items.create');
                Route::post('store/{id}', [AuditTemplateItemsController::class, 'store'])->name('audittemplate.items.store');
                Route::get('edit/{id}', [AuditTemplateItemsController::class, 'edit'])->name('audittemplate.items.edit');
                Route::post('update/{id}', [AuditTemplateItemsController::class, 'update'])->name('audittemplate.items.update');
                Route::get('destroy/{id}', [AuditTemplateItemsController::class, 'destroy'])->name('audittemplate.items.destroy');
                Route::get('actionStatus/{id}/{status}', [AuditTemplateItemsController::class, 'actionStatus'])->name('audittemplate.items.actionStatus');
            });
        });

        

    });

});

require __DIR__.'/auth.php';
