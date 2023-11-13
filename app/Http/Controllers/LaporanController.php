<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function printLaporanIndividu(Request $request)
    {
        $id = $request->id;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $user = DB::table('users')
            ->where('id', $id)
            ->first();
        $rekap = DB::table('absensi')
            ->where('id_user', $user->id)
            ->whereMonth('tanggal_absen', $bulan)
            ->whereYear('tanggal_absen', $tahun)
            ->orderBy('tanggal_absen')
            ->get();

        return view('layouts.component.printLaporanIndividu', compact('user', 'bulan', 'tahun', 'rekap'));
    }
    public function laporanSemua(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        return view('laporanSemua', compact('bulan', 'tahun'));
    }
    public function printLaporanSemua(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $rekap = DB::table('absensi')
            ->selectRaw('absensi.id_user, nama,
                MAX(IF(DAY(tanggal_absen) = 1, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_1,
                MAX(IF(DAY(tanggal_absen) = 2, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_2,
                MAX(IF(DAY(tanggal_absen) = 3, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_3,
                MAX(IF(DAY(tanggal_absen) = 4, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_4,
                MAX(IF(DAY(tanggal_absen) = 5, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_5,
                MAX(IF(DAY(tanggal_absen) = 6, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_6,
                MAX(IF(DAY(tanggal_absen) = 7, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_7,
                MAX(IF(DAY(tanggal_absen) = 8, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_8,
                MAX(IF(DAY(tanggal_absen) = 9, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_9,
                MAX(IF(DAY(tanggal_absen) = 10, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_10,
                MAX(IF(DAY(tanggal_absen) = 11, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_11,
                MAX(IF(DAY(tanggal_absen) = 12, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_12,
                MAX(IF(DAY(tanggal_absen) = 13, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_13,
                MAX(IF(DAY(tanggal_absen) = 14, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_14,
                MAX(IF(DAY(tanggal_absen) = 15, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_15,
                MAX(IF(DAY(tanggal_absen) = 16, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_16,
                MAX(IF(DAY(tanggal_absen) = 17, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_17,
                MAX(IF(DAY(tanggal_absen) = 18, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_18,
                MAX(IF(DAY(tanggal_absen) = 19, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_19,
                MAX(IF(DAY(tanggal_absen) = 20, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_20,
                MAX(IF(DAY(tanggal_absen) = 21, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_21,
                MAX(IF(DAY(tanggal_absen) = 22, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_22,
                MAX(IF(DAY(tanggal_absen) = 23, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_23,
                MAX(IF(DAY(tanggal_absen) = 24, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_24,
                MAX(IF(DAY(tanggal_absen) = 25, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_25,
                MAX(IF(DAY(tanggal_absen) = 26, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_26,
                MAX(IF(DAY(tanggal_absen) = 27, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_27,
                MAX(IF(DAY(tanggal_absen) = 28, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_28,
                MAX(IF(DAY(tanggal_absen) = 29, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_29,
                MAX(IF(DAY(tanggal_absen) = 30, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_30,
                MAX(IF(DAY(tanggal_absen) = 31, CONCAT(jam_masuk, "-", IFNULL(jam_keluar, "00:00:00")),"")) as tgl_31')
            ->join('users', 'absensi.id_user', '=', 'users.id')
            ->whereMonth('tanggal_absen', $bulan)
            ->whereYear('tanggal_absen', $tahun)
            ->groupByRaw('absensi.id_user,nama')
            ->get();
        return view('layouts.component.printLaporanSemua', compact('bulan', 'tahun', 'rekap'));
    }
}
