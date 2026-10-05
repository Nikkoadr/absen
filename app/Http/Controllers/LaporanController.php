<?php

namespace App\Http\Controllers;

use App\Exports\LaporanBulananExport;
use App\Http\Requests\FilterTanggalRequest;
use App\Http\Requests\LaporanRentangRequest;
use App\Models\Absensi;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\JadwalService;
use App\Services\LaporanService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function __construct(protected LaporanService $laporan, protected JadwalService $jadwal) {}

    public function printLaporanIndividu(Request $request, ?User $user = null)
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer', 'exists:users,id'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        $pengguna = $user->exists ? $user : User::findOrFail($data['id'] ?? abort(422, 'ID karyawan wajib diisi.'));
        $pengguna->loadMissing(['karyawan', 'siswa.kelas.kompetensi']);
        $awalBulan = Carbon::create($data['tahun'], $data['bulan'], 1, 0, 0, 0, 'Asia/Jakarta')->startOfMonth()->toDateString();
        $akhirBulan = Carbon::create($data['tahun'], $data['bulan'], 1, 0, 0, 0, 'Asia/Jakarta')->endOfMonth()->toDateString();
        $peta = $this->jadwal->petaRentang($awalBulan, $akhirBulan);
        $rekap = Absensi::with(['user:id,nama', 'user.karyawan:user_id,jam_kerja,jam_pulang', 'user.siswa:user_id,kelas_id', 'user.siswa.kelas:id,jam_masuk,jam_pulang'])
            ->milikPengguna($pengguna->id)
            ->bulan((int) $data['bulan'], (int) $data['tahun'])
            ->orderBy('tanggal_absen')
            ->get()
            ->each(function ($absen) use ($peta) {
                $shift = $this->jadwal->untukTanggalDariPeta($peta, $absen->id_user, substr((string) $absen->tanggal_absen, 0, 10), $absen->user);
                $absen->jam_kerja_hari = $shift['jam_masuk'];
                $absen->nama_shift = $shift['nama'];
            });

        return view('layouts.component.printLaporanIndividu', [
            'user' => $pengguna,
            'bulan' => (int) $data['bulan'],
            'tahun' => (int) $data['tahun'],
            'rekap' => $rekap,
            'jumlahIzin' => LeaveRequest::milikPengguna($pengguna->id)
                ->where('status', 'disetujui')
                ->whereMonth('tanggal_mulai', (int) $data['bulan'])
                ->whereYear('tanggal_mulai', (int) $data['tahun'])
                ->count(),
        ]);
    }

    public function laporanSemua(FilterTanggalRequest $request)
    {
        return to_route('laporan.karyawan');
    }

    public function laporanKaryawan()
    {
        return $this->formulir('karyawan');
    }

    public function laporanSiswa()
    {
        return $this->formulir('siswa');
    }

    protected function formulir(string $kelompok)
    {
        return view('laporan', [
            'kelompok' => $kelompok,
            'judul' => $kelompok === 'siswa' ? 'Rekap Presensi Siswa' : 'Rekap Presensi Karyawan',
            'kelas' => $kelompok === 'siswa'
                ? \App\Models\Kelas::with('kompetensi:id,singkatan')->orderBy('tingkat')->orderBy('nama')->get()
                : collect(),
        ]);
    }

    protected function judulHasil(array $data): string
    {
        if (($data['kelompok'] ?? null) !== 'siswa') {
            return 'Karyawan';
        }

        if (empty($data['kelas_id'])) {
            return 'Siswa - Semua Kelas';
        }

        $kelas = \App\Models\Kelas::with('kompetensi:id,singkatan')->find($data['kelas_id']);

        return $kelas ? "Siswa - {$kelas->tingkat} {$kelas->nama} ({$kelas->kompetensi->singkatan})" : 'Siswa';
    }

    public function printSemuaLaporan(LaporanRentangRequest $request)
    {
        $data = $request->validated();
        $rekap = $this->laporan->rekap($data['tanggal_awal'], $data['tanggal_akhir'], $data['kelas_id'] ?? null, $data['kelompok']);

        return view('layouts.component.printLaporanSemua', [
            'tanggalAwal' => $data['tanggal_awal'],
            'tanggalAkhir' => $data['tanggal_akhir'],
            'judulKelompok' => $this->judulHasil($data),
            'rekap' => $rekap,
        ]);
    }

    public function downloadLaporanBulanan(LaporanRentangRequest $request)
    {
        $data = $request->validated();

        return Excel::download(
            new LaporanBulananExport($data['tanggal_awal'], $data['tanggal_akhir'], $data['kelas_id'] ?? null, $data['kelompok']),
            "rekap-presensi-{$data['kelompok']}-{$data['tanggal_awal']}_{$data['tanggal_akhir']}.xlsx"
        );
    }
}
