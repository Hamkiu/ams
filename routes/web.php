<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditTemplateController;
use App\Http\Controllers\AuditTemplateItemsController;
use App\Http\Controllers\AuditItemChecklistController;
use App\Http\Controllers\AuditGroupsController;
use App\Http\Controllers\AuditGroupsMembersController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\UploadImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('image')->group(function () {
        Route::any('upload', [UploadImageController::class, 'uploadimage'])->name('image.upload');
    });

    Route::middleware('role:Auditor')->group(function () {

        Route::prefix('audit')->group(function () {
            Route::get('/', [AuditController::class, 'index'])->name('audit');
            Route::get('show/{id}', [AuditController::class, 'show'])->name('audit.show');
            Route::post('store', [AuditController::class, 'store'])->name('audit.store');
            Route::post('attachment', [AuditController::class, 'attachment'])->name('audit.attachment');
            Route::post('listattachment', [AuditController::class, 'listattachment'])->name('audit.listattachment');
            Route::get('delete/{id}', [AuditController::class, 'delete'])->name('auditfiles.delete');
            Route::post('submit', [AuditController::class, 'submit'])->name('audit.submit');
            Route::get('summary/{id}', [AuditController::class, 'summary'])->name('audit.summary');
            Route::post('store-conclusion', [AuditController::class, 'storeConclusion'])->name('audit.store-conclusion');
        });
    });

    Route::middleware('role:Admin')->group(function () {

        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        });

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
            Route::get('publish/{id}', [AuditTemplateController::class, 'publish'])->name('audittemplate.publish');
            Route::get('archive/{id}', [AuditTemplateController::class, 'archive'])->name('audittemplate.archive');
            Route::get('preview/{id}', [AuditTemplateController::class, 'preview'])->name('audittemplate.preview');

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

            Route::prefix('checklist')->group(function () {
                Route::get('/{id}', [AuditItemChecklistController::class, 'index'])->name('audittemplate.checklist');
                Route::any('list/{id}', [AuditItemChecklistController::class, 'list'])->name('audittemplate.checklist.list');
                Route::post('store/{id}', [AuditItemChecklistController::class, 'store'])->name('audittemplate.checklist.store');
                Route::get('edit/{id}', [AuditItemChecklistController::class, 'edit'])->name('audittemplate.checklist.edit');
                Route::post('update/{id}', [AuditItemChecklistController::class, 'update'])->name('audittemplate.checklist.update');
                Route::get('destroy/{id}', [AuditItemChecklistController::class, 'destroy'])->name('audittemplate.checklist.destroy');
            });
        });

        Route::prefix('auditgroup')->group(function () {
            Route::get('/', [AuditGroupsController::class, 'index'])->name('auditgroup');
            Route::any('list', [AuditGroupsController::class, 'list'])->name('auditgroup.list');
            Route::get('create', [AuditGroupsController::class, 'create'])->name('auditgroup.create');
            Route::post('store', [AuditGroupsController::class, 'store'])->name('auditgroup.store');
            Route::get('edit/{id}', [AuditGroupsController::class, 'edit'])->name('auditgroup.edit');
            Route::post('update/{id}', [AuditGroupsController::class, 'update'])->name('auditgroup.update');
            Route::get('destroy/{id}', [AuditGroupsController::class, 'destroy'])->name('auditgroup.destroy');
            Route::get('answers/{id}', [AuditGroupsController::class, 'answers'])->name('auditgroup.answers');
        });

        Route::prefix('auditgroupmember')->group(function () {
            Route::get('/{id}', [AuditGroupsMembersController::class, 'index'])->name('auditgroupmember');
            Route::any('list/{id}', [AuditGroupsMembersController::class, 'list'])->name('auditgroupmember.list');
            Route::post('store/{id}', [AuditGroupsMembersController::class, 'store'])->name('auditgroupmember.store');
            Route::get('edit/{id}', [AuditGroupsMembersController::class, 'edit'])->name('auditgroupmember.edit');
            Route::post('update/{id}', [AuditGroupsMembersController::class, 'update'])->name('auditgroupmember.update');
            Route::get('destroy/{id}', [AuditGroupsMembersController::class, 'destroy'])->name('auditgroupmember.destroy');
        });
    });

    Route::middleware('role:Admin|Auditor')->group(function () {

        Route::get('audit/download/{id}', [AuditController::class, 'download'])->name('auditfiles.download');
    });
});

require __DIR__ . '/auth.php';
