<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\DashboardController;
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

    Route::middleware('role:Admin|User')->group(function () {

        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        });

    });

    Route::middleware('role:Admin')->group(function () {

        Route::prefix('audittrail')->group(function () {
            Route::get('/', [AuditTrailController::class, 'index'])->name('audittrail');
            Route::any('list', [AuditTrailController::class, 'list'])->name('audittrail.list');
        });

    });

});

require __DIR__.'/auth.php';
