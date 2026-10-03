<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $pengguna = request()->user();
        $zona = 'Asia/Jakarta';
        $today = Carbon::today($zona);
        $hariIni = $today->toDateString();

        $dasar = Absensi::with('user:id,nama')->padaTanggal($hariIni)->orderBy('jam_masuk');

        $absenHariIni = Absensi::milikPengguna($pengguna->id)->padaTanggal($hariIni)->first();
        $historyBulanIni = Absensi::milikPengguna($pengguna->id)
            ->bulan((int) $today->month, (int) $today->year)
            ->orderBy('tanggal_absen')
            ->get();

        $jumlahHadir = Absensi::milikPengguna($pengguna->id)
            ->bulan((int) $today->month, (int) $today->year)
            ->count();

        $hitungPulang = Absensi::padaTanggal($hariIni)->whereNotNull('jam_keluar')->count();
        $leaderboard = (clone $dasar)->get();
        $hitungUser = User::count();

        if ($pengguna->role === 'admin') {
            return view('home', [
                'absenHariIni' => $absenHariIni,
                'historyBulanIni' => $historyBulanIni,
                'bulanIni' => (int) $today->month,
                'tahunIni' => (int) $today->year,
                'namaBulan' => $today->isoFormat('MMMM'),
                'leaderboard' => $leaderboard,
                'hitungUser' => $hitungUser,
                'hitungMasukHariIni' => $leaderboard->count(),
                'hitungPulang' => $hitungPulang,
                'hitungAlfa' => max(0, $hitungUser - $leaderboard->count()),
            ]);
        }

        return view('home_mobile', [
            'absenHariIni' => $absenHariIni,
            'historyBulanIni' => $historyBulanIni,
            'bulanIni' => (int) $today->month,
            'tahunIni' => (int) $today->year,
            'namaBulan' => $today->isoFormat('MMMM'),
            'rekapAbsensi' => (object) [
                'jumlahHadir' => $jumlahHadir,
                'jumlahTidakHadir' => max(0, $today->day - $jumlahHadir),
            ],
            'leaderboard_mobile' => (clone $dasar)->take(10)->get(),
            'set_jam_kerja' => $pengguna->jam_kerja,
        ]);
    }
}
