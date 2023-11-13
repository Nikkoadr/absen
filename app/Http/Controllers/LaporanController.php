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

        $selectStatements = [];
        for ($hari = 1; $hari <= 31; $hari++) {
            $selectStatements[] = "MAX(IF(DAY(tanggal_absen) = $hari, CONCAT(jam_masuk, '-', IFNULL(jam_keluar, '00:00:00')), '')) as tgl_$hari";
        }

        $rekap = DB::table('absensi')
            ->selectRaw('absensi.id_user, nama, ' . implode(', ', $selectStatements))
            ->join('users', 'absensi.id_user', '=', 'users.id')
            ->whereMonth('tanggal_absen', $bulan)
            ->whereYear('tanggal_absen', $tahun)
            ->groupByRaw('absensi.id_user, nama')
            ->orderBy('nama')
            ->get();

        return view('layouts.component.printLaporanSemua', compact('bulan', 'tahun', 'rekap'));
    }
}
