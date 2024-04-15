<?php

use App\Http\Controllers\ConverterController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group([
    'middleware' => 'auth'
], function () {
    Route::get('/upload', [ConverterController::class, 'show']);
    Route::post('/convert', [ConverterController::class, 'convert'])->name('convert');
    Route::get('/download-excel', [ConverterController::class, 'downloadExcel'])->name('download-excel');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
