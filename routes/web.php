<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingContoller;
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

Route::get('/home',                     [HomeController::class, 'index'])->name('home');
// Route::post('/GetDataLeaderboard',                     [HomeController::class, 'GetDataLeaderboard'])->name('GetDataLeaderboard');
Route::get('/absen',                    [AbsensiController::class, 'absen'])->name('absen');
Route::post('/absenMasuk',              [AbsensiController::class, 'absenMasuk'])->name('absenMasuk');

Route::get('/data_user',                [UserController::class, 'index'])->name('data_user');
Route::post('/importUser',              [UserController::class, 'importUser'])->name('importUser');
Route::get('/exportuser',               [UserController::class, 'exportuser'])->name('exportuser');
Route::put('/tambah_user',              [UserController::class, 'tambah_user'])->name('tambah_user');
Route::put('/editUserId{id}',           [UserController::class, 'edit_user'])->name('edit_user');
Route::get('/hapusDataUserId{id}',      [UserController::class, 'hapus_data_user']);
Route::put('/ubah_password_id{id}',     [UserController::class, 'ubah_password']);

Route::get('/profile',                  [ProfileController::class, 'index'])->name('profile');
Route::put('/edit/profile_id{id}',      [ProfileController::class, 'edit_user'])->name('edit_user');
Route::put('/edit/password_user_id{id}',      [ProfileController::class, 'edit_password_user_id'])->name('edit_password_user_id');
Route::put('upload_pasfoto_id{id}',      [ProfileController::class, 'upload_pasfoto_id'])->name('upload_pasfoto_id');
Route::get('history',                               [ProfileController::class, 'history'])->name('history.cari');

Route::put('/printLaporanIndividu{id}',          [LaporanController::class, 'printLaporanIndividu'])->name('printLaporanIndividu');
Route::get('/laporanSemua',                  [LaporanController::class, 'laporanSemua'])->name('laporanSemua');
Route::put('/printLaporanSemua',                  [LaporanController::class, 'printLaporanSemua'])->name('printLaporanSemua');

Route::get('/setting',                  [SettingContoller::class, 'setting'])->name('setting');
