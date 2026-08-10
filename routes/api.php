<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/cari-pekerja', [APIController::class, 'cariPekerja']);

Route::post('/senarai-jabatan', [APIController::class, 'senaraiJabatan']);
