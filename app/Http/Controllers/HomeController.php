<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use DataTables;

class HomeController extends Controller
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
    public function index()
    {
        $hariIni = date("Y-m-d");
        $userAktif = Auth::user()->id;
        $absenHariIni = DB::table('absensi')
            ->where('id_user', $userAktif)
            ->where('tanggal_absen', $hariIni)->first();
        $bulanIni = date("n");
        $tahunIni = date("Y");
        $set_jam_kerja = Auth::user()->jam_kerja;
        $historyBulanIni = DB::table('absensi')
            ->where('id_user', $userAktif)
            ->whereMonth('tanggal_absen', $bulanIni)
            ->whereYear('tanggal_absen', $tahunIni)
            ->orderBy('tanggal_absen')
            ->get();

    $rekapAbsensi = DB::table('absensi')
        ->selectRaw('
            COUNT(id_user) as jumlahHadir, 
            (DAY(LAST_DAY(?)) - COUNT(id_user)) as jumlahTidakHadir', [$hariIni])
        ->where('id_user', $userAktif)
        ->whereMonth('tanggal_absen', $bulanIni)
        ->whereYear('tanggal_absen', $tahunIni)
        ->first();

        $hitungPulang = DB::table('absensi')
            ->join('users', 'absensi.id_user', '=', 'users.id')
            ->where('tanggal_absen', $hariIni)
            ->whereNotNull('jam_keluar')
            ->count();

        $leaderboard = DB::table('absensi')
            ->join('users', 'absensi.id_user',  '=', 'users.id')
            ->where('tanggal_absen', $hariIni)
            ->orderBy('jam_masuk')
            ->get();

        $leaderboard_mobile = DB::table('absensi')
            ->join('users', 'absensi.id_user',  '=', 'users.id')
            ->where('tanggal_absen', $hariIni)
            ->orderBy('jam_masuk')
            ->take(10)
            ->get();

        $hitungUser = User::count();
        $hitungMasukHariIni = $leaderboard->count();
        $hitungAlfa = $hitungUser - $hitungMasukHariIni;
        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        if (Auth::user()->role === 'admin') {
            return view('home', compact('absenHariIni', 'historyBulanIni', 'bulanIni', 'tahunIni', 'namaBulan', 'leaderboard', 'hitungUser', 'hitungMasukHariIni', 'hitungPulang', 'hitungAlfa'));
        } else {
            return view('home_mobile', compact('absenHariIni', 'historyBulanIni', 'bulanIni', 'tahunIni', 'namaBulan', 'rekapAbsensi', 'leaderboard_mobile', 'set_jam_kerja'));
        }
    }
    // public function getDataLeaderboard()
    // {
    //     $hariIni = now()->toDateString();

    //     $leaderboard = DB::table('absensi')
    //         ->join('users', 'absensi.id_user', '=', 'users.id')
    //         ->where('tanggal_absen', $hariIni)
    //         ->orderBy('jam_masuk')
    //         ->select(['users.nama', 'absensi.foto_masuk', 'absensi.jam_masuk', 'absensi.foto_keluar', 'absensi.jam_keluar'])
    //         ->get();
    //     $index = 1;
    //     foreach ($leaderboard as $row) {
    //         $row->DT_RowIndex = $index++;
    //     }
    //     return response()->json(['data' => $leaderboard]);
    // }
}
