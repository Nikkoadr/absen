<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Jakarta');
        $hariIni = $today->toDateString();
        $userAktif = Auth::id();
        $bulanIni = (int) $today->month;
        $tahunIni = (int) $today->year;

        $absenHariIni = DB::table('absensi')
            ->where('id_user', $userAktif)
            ->where('tanggal_absen', $hariIni)
            ->first();

        $historyBulanIni = DB::table('absensi')
            ->where('id_user', $userAktif)
            ->whereMonth('tanggal_absen', $bulanIni)
            ->whereYear('tanggal_absen', $tahunIni)
            ->orderBy('tanggal_absen')
            ->get();

        $set_jam_kerja = Auth::user()->jam_kerja;

        $jumlahHadir = DB::table('absensi')
            ->where('id_user', $userAktif)
            ->whereMonth('tanggal_absen', $bulanIni)
            ->whereYear('tanggal_absen', $tahunIni)
            ->count();

        $rekapAbsensi = (object) [
            'jumlahHadir' => $jumlahHadir,
            'jumlahTidakHadir' => max(0, $today->day - $jumlahHadir),
        ];

        $hitungPulang = DB::table('absensi')
            ->where('tanggal_absen', $hariIni)
            ->whereNotNull('jam_keluar')
            ->count();

        $leaderboard = DB::table('absensi')
            ->join('users', 'absensi.id_user', '=', 'users.id')
            ->where('tanggal_absen', $hariIni)
            ->orderBy('jam_masuk')
            ->select('absensi.*', 'users.nama')
            ->get();

        $leaderboard_mobile = DB::table('absensi')
            ->join('users', 'absensi.id_user', '=', 'users.id')
            ->where('tanggal_absen', $hariIni)
            ->orderBy('jam_masuk')
            ->select('absensi.*', 'users.nama')
            ->take(10)
            ->get();

        $hitungUser = User::count();
        $hitungMasukHariIni = $leaderboard->count();
        $hitungAlfa = max(0, $hitungUser - $hitungMasukHariIni);
        $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        if (Auth::user()->role === 'admin') {
            return view('home', compact('absenHariIni', 'historyBulanIni', 'bulanIni', 'tahunIni', 'namaBulan', 'leaderboard', 'hitungUser', 'hitungMasukHariIni', 'hitungPulang', 'hitungAlfa'));
        }

        return view('home_mobile', compact('absenHariIni', 'historyBulanIni', 'bulanIni', 'tahunIni', 'namaBulan', 'rekapAbsensi', 'leaderboard_mobile', 'set_jam_kerja'));
    }
}
