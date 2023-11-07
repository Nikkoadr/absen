<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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
        $bulanIni = date("m");
        $tahunIni = date("Y");

        $historyBulanIni = DB::table('absensi')
            ->where('id_user', $userAktif)
            ->whereRaw('MONTH(tanggal_absen)="' . $bulanIni . '"')
            ->whereRaw('YEAR(tanggal_absen)="' . $tahunIni . '"')
            ->orderBy('tanggal_absen')
            ->get();

        $rekapAbsensi = DB::table('absensi')
            ->selectRaw('COUNT(id_user) as jumlahHadir, SUM(IF(jam_masuk > "07:00",1,0)) as jumlahTerlambat')
            ->where('id_user', $userAktif)
            ->whereRaw('MONTH(tanggal_absen)="' . $bulanIni . '"')
            ->whereRaw('YEAR(tanggal_absen)="' . $tahunIni . '"')
            ->first();

        $leaderboard = DB::table('absensi')
            ->join('users', 'absensi.id_user',  '=', 'users.id')
            ->where('tanggal_absen', $hariIni)
            ->orderBy('jam_masuk')
            ->get();

        $namaBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        if (Auth::user()->role === 'admin') {
            return view('home', compact('absenHariIni', 'historyBulanIni', 'bulanIni', 'tahunIni', 'namaBulan', 'rekapAbsensi', 'leaderboard'));
        } else {
            return view('home_mobile', compact('absenHariIni', 'historyBulanIni', 'bulanIni', 'tahunIni', 'namaBulan', 'rekapAbsensi', 'leaderboard'));
        }
    }
}
