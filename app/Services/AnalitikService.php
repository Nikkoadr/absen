<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Agregat analitik memakai anotasiJamKerja (satu peta shift) agar
 * penilaian terlambat identik dengan rekap bulanan.
 */
class AnalitikService
{
    public function __construct(protected JadwalService $jadwal) {}

    /** Hadir per tanggal pada rentang. */
    public function tren(string $awal, string $akhir): Collection
    {
        return Absensi::whereBetween('tanggal_absen', [$awal, $akhir])
            ->selectRaw('tanggal_absen as tanggal, COUNT(*) as hadir')
            ->groupBy('tanggal_absen')
            ->orderBy('tanggal_absen')
            ->get();
    }

    /** Per kelas: jumlah siswa, presensi, dan menit terlambat. */
    public function perKelas(string $awal, string $akhir): Collection
    {
        $peta = $this->jadwal->petaRentang($awal, $akhir);

        return Kelas::with(['kompetensi:id,singkatan', 'siswa:id,kelas_id,user_id'])
            ->orderBy('tingkat')->orderBy('nama')
            ->get()
            ->map(function ($kelas) use ($awal, $akhir, $peta) {
                $ids = $kelas->siswa->pluck('user_id')->all();
                $baris = $ids
                    ? Absensi::whereIn('id_user', $ids)->whereBetween('tanggal_absen', [$awal, $akhir])->get()
                    : collect();

                $terlambat = 0;
                $jadwalKelas = substr((string) $kelas->jam_masuk, 0, 5);
                foreach ($baris as $b) {
                    $batas = $this->jadwal->jamMasukPada($peta, (int) $b->id_user, substr((string) $b->tanggal_absen, 0, 10), $jadwalKelas);
                    if ($batas && substr((string) $b->jam_masuk, 0, 5) > $batas) {
                        $terlambat += abs(Carbon::parse(substr((string) $b->jam_masuk, 0, 5), 'Asia/Jakarta')
                            ->diffInMinutes(Carbon::parse($batas, 'Asia/Jakarta')));
                    }
                }

                return [
                    'kelas' => "{$kelas->tingkat} {$kelas->nama} ({$kelas->kompetensi->singkatan})",
                    'siswa' => count($ids),
                    'presensi' => $baris->count(),
                    'terlambat_menit' => $terlambat,
                ];
            });
    }

    /** Pengguna dengan menit keterlambatan terbesar pada rentang. */
    public function topTerlambat(string $awal, string $akhir, int $batas = 10): Collection
    {
        $baris = Absensi::with('user:id,nama')->whereBetween('tanggal_absen', [$awal, $akhir])->get();
        $this->jadwal->anotasiJamKerja($baris);

        return $baris
            ->map(function ($b) {
                $menit = 0;
                if (($b->jam_kerja_hari ?? null) && preg_match('/^\d{2}:\d{2}/', (string) $b->jam_masuk)
                    && substr((string) $b->jam_masuk, 0, 5) > $b->jam_kerja_hari) {
                    $menit = abs(Carbon::parse(substr((string) $b->jam_masuk, 0, 5), 'Asia/Jakarta')
                        ->diffInMinutes(Carbon::parse($b->jam_kerja_hari, 'Asia/Jakarta')));
                }

                return ['nama' => $b->user->nama ?? '-', 'menit' => $menit];
            })
            ->groupBy('nama')
            ->map(fn ($g, $nama) => ['nama' => $nama, 'menit' => $g->sum('menit')])
            ->sortByDesc('menit')
            ->take($batas)
            ->values();
    }
}
