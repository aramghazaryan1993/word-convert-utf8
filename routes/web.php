<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\WordConvertController::class, 'index'])->name('index');
Route::post('/upload', [\App\Http\Controllers\WordConvertController::class, 'upload'])->name('upload');
Route::post('/download', [\App\Http\Controllers\WordConvertController::class, 'download'])->name('download');
Route::post('/delete', [\App\Http\Controllers\WordConvertController::class, 'delete'])->name('delete');
