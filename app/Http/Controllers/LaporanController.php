<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

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
            ->join('users', 'absensi.id_user', '=', 'users.id')
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

    public function printSemuaLaporan(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $selectStatements = [];
        $tanggalMulai = Carbon::parse($tanggalAwal);
        $tanggalSelesai = Carbon::parse($tanggalAkhir);

        while ($tanggalMulai->lte($tanggalSelesai)) {
            $hari = $tanggalMulai->day;
            $selectStatements[] = "MAX(CASE WHEN DAY(tanggal_absen) = $hari THEN CONCAT(jam_masuk, '-', IFNULL(jam_keluar, '00:00:00')) ELSE '' END) as tgl_$hari";
            $tanggalMulai->addDay();
        }

        $rekap = DB::table('absensi')
            ->selectRaw('absensi.id_user, users.jam_kerja, nama, ' . implode(', ', $selectStatements))
            ->leftJoin('users', 'absensi.id_user', '=', 'users.id')
            ->whereBetween('tanggal_absen', [$tanggalAwal, $tanggalAkhir])
            ->groupByRaw('absensi.id_user, users.jam_kerja, nama')
            ->orderBy('nama')
            ->get();

        return view('layouts.component.printLaporanSemua', compact('tanggalAwal', 'tanggalAkhir', 'rekap', 'tanggalAwal', 'tanggalAkhir'));
    }
}
