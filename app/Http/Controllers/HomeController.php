<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\JadwalService;
use App\Support\HariKerja;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function __construct(protected JadwalService $jadwal) {}

    public function index()
    {
        $pengguna = request()->user();
        $pengguna->loadMissing(['karyawan', 'siswa.kelas']);
        $bawaan = $this->jadwal->bawaanPengguna($pengguna);
        $zona = 'Asia/Jakarta';
        $today = Carbon::today($zona);
        $hariIni = $today->toDateString();

        $dasar = Absensi::with(['user:id,nama', 'user.karyawan:user_id,jam_kerja', 'user.siswa:user_id,kelas_id', 'user.siswa.kelas:id,jam_masuk'])
            ->join('users', 'absensi.id_user', '=', 'users.id')
            ->leftJoin('karyawans', 'karyawans.user_id', '=', 'users.id')
            ->padaTanggal($hariIni)
            ->orderBy('jam_masuk')
            ->select('absensi.*', 'users.nama', 'karyawans.jabatan as jabatan', 'users.pasfoto');

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
        $jamKerjaHariIni = $this->jadwal->untukTanggal($pengguna->id, $hariIni)['jam_masuk']
            ?? ($bawaan ? substr($bawaan, 0, 5) : null);

        $tren7Hari = collect(range(6, 0))->map(function ($mundur) use ($today) {
            $tgl = $today->copy()->subDays($mundur);
            return [
                'label' => $tgl->isoFormat('dd D/M'),
                'hadir' => Absensi::padaTanggal($tgl->toDateString())->count(),
            ];
        });

        if ($pengguna->role === 'admin') {
            $staf = User::where('role', '!=', 'admin')->count();
            $izinHariIni = LeaveRequest::where('status', 'disetujui')
                ->where('tanggal_mulai', '<=', $hariIni)
                ->where(function ($q) use ($hariIni) {
                    $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai', '>=', $hariIni);
                })
                ->distinct()
                ->count('user_id');

            $this->jadwal->anotasiJamKerja($leaderboard);

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
                'hitungAlfa' => HariKerja::adalahHariKerja($hariIni) ? max(0, $staf - $leaderboard->count() - $izinHariIni) : 0,
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

        $this->jadwal->anotasiJamKerja($historyBulanIni, $bawaan);

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
            'set_jam_kerja' => $jamKerjaHariIni,
        ]);
    }
}
