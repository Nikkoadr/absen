<?php

namespace App\Http\Controllers;

use App\Exports\LaporanBulananExport;
use App\Http\Requests\FilterTanggalRequest;
use App\Http\Requests\LaporanRentangRequest;
use App\Models\Absensi;
use App\Models\User;
use App\Services\JadwalService;
use App\Services\LaporanService;
use Illuminate\Http\Request;
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
        $rekap = Absensi::with('user:id,nama')
            ->milikPengguna($pengguna->id)
            ->bulan((int) $data['bulan'], (int) $data['tahun'])
            ->orderBy('tanggal_absen')
            ->get()
            ->each(function ($absen) {
                $shift = $this->jadwal->untukTanggal($absen->id_user, substr((string) $absen->tanggal_absen, 0, 10));
                $absen->jam_kerja_hari = $shift['jam_masuk'];
                $absen->nama_shift = $shift['nama'];
            });

        return view('layouts.component.printLaporanIndividu', [
            'user' => $pengguna,
            'bulan' => (int) $data['bulan'],
            'tahun' => (int) $data['tahun'],
            'rekap' => $rekap,
        ]);
    }

    public function laporanSemua(FilterTanggalRequest $request)
    {
        return view('laporanSemua', [
            'bulan' => $request->bulan(),
            'tahun' => $request->tahun(),
        ]);
    }

    public function printSemuaLaporan(LaporanRentangRequest $request)
    {
        $data = $request->validated();
        $rekap = $this->laporan->rekap($data['tanggal_awal'], $data['tanggal_akhir']);

        return view('layouts.component.printLaporanSemua', [
            'tanggalAwal' => $data['tanggal_awal'],
            'tanggalAkhir' => $data['tanggal_akhir'],
            'rekap' => $rekap,
        ]);
    }

    public function downloadLaporanBulanan(LaporanRentangRequest $request)
    {
        $data = $request->validated();

        return Excel::download(
            new LaporanBulananExport($data['tanggal_awal'], $data['tanggal_akhir']),
            "rekap-presensi-{$data['tanggal_awal']}_{$data['tanggal_akhir']}.xlsx"
        );
    }
}
