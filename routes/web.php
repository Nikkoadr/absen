<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\LaporanController;
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

Auth::routes([
    'register' => false,
    'reset' => false
]);

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/absen', [HomeController::class, 'absen'])->name('absen');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/rekap', [RekapController::class, 'index'])->name('rekap');
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
Route::post('/absenMasuk', [HomeController::class, 'absenMasuk'])->name('absenMasuk');
