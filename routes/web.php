<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataPasienController;
use App\Http\Controllers\GabungDataController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/import/data/pasien', [DataPasienController::class, 'data_pasien'])->name('data_pasien');

Route::get('/pasien/data', [DataPasienController::class, 'getData']);
Route::post('/pasien/import', [DataPasienController::class, 'import']);

Route::get('/gabung/data/pasien', [GabungDataController::class, 'gabung_data'])->name('gabung_data');
Route::get('/ambil/pasien', [GabungDataController::class, 'ambilPasien']);
Route::post('/pasien/gabung/import', [GabungDataController::class, 'import_gabung'])->name('import_gabung');
Route::post('/pasien/gabung/import/update', [GabungDataController::class, 'update_import_gabung'])->name('update_import_gabung');