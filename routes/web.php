<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('dosen', DosenController::class);
Route::get('/dosens/export/pdf', [DosenController::class, 'exportPDF'])->name('dosens.export.pdf');
Route::get('/dosen/export/excel', [DosenController::class, 'exportEXCEL'])->name('dosen.export.excel');
