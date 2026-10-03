<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Support\HariKerja;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $pengguna = request()->user();
        $zona = 'Asia/Jakarta';
        $today = Carbon::today($zona);
        $hariIni = $today->toDateString();

        $dasar = Absensi::with('user:id,nama,jam_kerja')
            ->join('users', 'absensi.id_user', '=', 'users.id')
            ->padaTanggal($hariIni)
            ->orderBy('jam_masuk')
            ->select('absensi.*', 'users.nama', 'users.jabatan', 'users.pasfoto');

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
        $hariKerjaBerjalan = HariKerja::jumlahHariKerja((int) $today->month, (int) $today->year, $hariIni);

        $tren7Hari = collect(range(6, 0))->map(function ($mundur) use ($today) {
            $tgl = $today->copy()->subDays($mundur);
            return [
                'label' => $tgl->isoFormat('dd D/M'),
                'hadir' => Absensi::padaTanggal($tgl->toDateString())->count(),
            ];
        });

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
                'hitungAlfa' => HariKerja::adalahHariKerja($hariIni) ? max(0, $hitungUser - $leaderboard->count()) : 0,
                'tren7Hari' => $tren7Hari,
            ]);
        }

        $jumlahIzin = LeaveRequest::milikPengguna($pengguna->id)
            ->where('status', 'disetujui')
            ->whereMonth('tanggal_mulai', (int) $today->month)
            ->whereYear('tanggal_mulai', (int) $today->year)
            ->count();

        $mingguan = collect(range(6, 0))->map(function ($mundur) use ($pengguna) {
            $tgl = Carbon::today('Asia/Jakarta')->subDays($mundur);

            return [
                'label' => $tgl->isoFormat('dd'),
                'ada' => Absensi::milikPengguna($pengguna->id)->padaTanggal($tgl->toDateString())->exists(),
                'hariIni' => $mundur === 0,
            ];
        });

        return view('home_mobile', [
            'absenHariIni' => $absenHariIni,
            'historyBulanIni' => $historyBulanIni,
            'bulanIni' => (int) $today->month,
            'tahunIni' => (int) $today->year,
            'namaBulan' => $today->isoFormat('MMMM'),
            'rekapAbsensi' => (object) [
                'jumlahHadir' => $jumlahHadir,
                'jumlahTidakHadir' => max(0, $hariKerjaBerjalan - $jumlahHadir),
                'jumlahIzin' => $jumlahIzin,
            ],
            'mingguan' => $mingguan,
            'leaderboard_mobile' => (clone $dasar)->take(10)->get(),
            'set_jam_kerja' => $pengguna->jam_kerja,
        ]);
    }
}
