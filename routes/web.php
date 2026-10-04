<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\KiosPresensiController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        $radius = (int) (App\Models\Setting::first()?->radius ?? 70);
    } catch (Throwable) {
        $radius = 70;
    }

    return view('welcome', ['radius' => $radius]);
});

Auth::routes([
    'register' => false,
    'reset' => false,
]);

// Kios presensi wajah tanpa login (dibatas throttle agar tidak disalahgunakan)
Route::get('/presensi-mandiri', [KiosPresensiController::class, 'kios'])->name('kios');
Route::get('/api/deskriptor-wajah', [KiosPresensiController::class, 'deskriptor'])->middleware('throttle:30,1');
Route::post('/presensi-mandiri', [KiosPresensiController::class, 'simpan'])->middleware('throttle:10,1')->name('kios.simpan');

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/absen', [AbsensiController::class, 'absen'])->name('absen');
    Route::post('/absenMasuk', [AbsensiController::class, 'absenMasuk'])->name('absenMasuk');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/{user}', [ProfileController::class, 'edit_user'])->name('profile.update');
    Route::put('/profile/{user}/password', [ProfileController::class, 'edit_password_user_id'])->name('profile.password');
    Route::put('/profile/{user}/pasfoto', [ProfileController::class, 'upload_pasfoto_id'])->name('profile.pasfoto');
    Route::post('/profile/{user}/wajah', [KiosPresensiController::class, 'simpanDeskriptor'])->name('profile.wajah');
    Route::get('/history', [ProfileController::class, 'history'])->name('history.cari');

    Route::get('/izin', [IzinController::class, 'izin'])->name('izin');
    Route::post('/izin', [IzinController::class, 'request_izin_user'])->name('request_izin_user');

    Route::middleware('can:is_admin')->group(function () {
        Route::get('/perizinan', [IzinController::class, 'daftar'])->name('perizinan');
        Route::put('/perizinan/{perizinan}/setujui', [IzinController::class, 'setujui'])->name('perizinan.setujui');
        Route::put('/perizinan/{perizinan}/tolak', [IzinController::class, 'tolak'])->name('perizinan.tolak');
        Route::get('/attendance', [AbsensiController::class, 'attendance'])->name('attendance');
        Route::get('/attendance/{absensi}/edit', [AbsensiController::class, 'edit_absen'])->name('edit_absen');
        Route::put('/attendance/{absensi}', [AbsensiController::class, 'update_absen'])->name('update_absen');
        Route::delete('/attendance/{absensi}', [AbsensiController::class, 'hapus_absen'])->name('hapus_absen');

        Route::get('/data_user', [UserController::class, 'index'])->name('data_user');
        Route::post('/importUser', [UserController::class, 'importUser'])->name('importUser');
        Route::get('/exportuser', [UserController::class, 'exportuser'])->name('exportuser');
        Route::post('/tambah_user', [UserController::class, 'tambah_user'])->name('tambah_user');
        Route::put('/user/{user}', [UserController::class, 'edit_user'])->name('edit_user');
        Route::delete('/user/{user}', [UserController::class, 'hapus_data_user'])->name('hapus_data_user');
        Route::put('/user/{user}/password', [UserController::class, 'ubah_password'])->name('ubah_password');

        Route::post('/laporan/individu/{user}', [LaporanController::class, 'printLaporanIndividu'])->name('printLaporanIndividu');
        Route::get('/laporanSemua', [LaporanController::class, 'laporanSemua'])->name('laporanSemua');
        Route::post('/printLaporanBulanan', [LaporanController::class, 'printSemuaLaporan'])->name('printSemuaLaporan');
        Route::post('/downloadLaporanBulanan', [LaporanController::class, 'downloadLaporanBulanan'])->name('downloadLaporanBulanan');

        Route::get('/setting', [SettingController::class, 'setting'])->name('setting');
        Route::put('/setting', [SettingController::class, 'editSetting'])->name('editSetting');

        Route::get('/libur', [HolidayController::class, 'index'])->name('libur.index');
        Route::get('/libur/data', [HolidayController::class, 'data'])->name('libur.data');

        Route::get('/shift', [ShiftController::class, 'index'])->name('shift.index');
        Route::post('/shift', [ShiftController::class, 'storeShift'])->name('shift.store');
        Route::get('/shift/contoh', [ShiftController::class, 'contoh'])->name('shift.contoh');
        Route::post('/shift/impor', [ShiftController::class, 'impor'])->name('shift.impor');
        Route::delete('/shift/{shift}', [ShiftController::class, 'destroyShift'])->name('shift.destroy');
        Route::post('/shift/tugas', [ShiftController::class, 'storeTugas'])->name('shift.tugas.store');
        Route::delete('/shift/tugas/{tugas}', [ShiftController::class, 'destroyTugas'])->name('shift.tugas.destroy');
        Route::post('/libur', [HolidayController::class, 'store'])->name('libur.store');
        Route::post('/libur/sinkron', [HolidayController::class, 'sinkron'])->name('libur.sinkron');
        Route::delete('/libur/{libur}', [HolidayController::class, 'destroy'])->name('libur.destroy');
    });
});
