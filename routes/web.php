<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataPasienController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/import/data/pasien', [DataPasienController::class, 'data_pasien'])->name('data_pasien');

Route::get('/pasien/data', [DataPasienController::class, 'getData']);
Route::post('/pasien/import', [DataPasienController::class, 'import']);
