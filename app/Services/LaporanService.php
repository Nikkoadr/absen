<?php

namespace App\Services;

use App\Models\LeaveRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LaporanService
{
    public function __construct(protected JadwalService $jadwal) {}

    public function rekap(string $tanggalAwal, string $tanggalAkhir): Collection
    {
        $mulai = Carbon::parse($tanggalAwal, 'Asia/Jakarta');
        $selesai = Carbon::parse($tanggalAkhir, 'Asia/Jakarta');

        $select = [];
        $cursor = $mulai->copy();
        while ($cursor->lte($selesai)) {
            $hari = (int) $cursor->day;
            $select[] = "MAX(CASE WHEN DAY(tanggal_absen) = {$hari} THEN CONCAT(jam_masuk, '-', IFNULL(jam_keluar, '00:00:00')) ELSE '' END) as tgl_{$hari}";
            $cursor->addDay();
        }

        $rekap = DB::table('users')
            ->selectRaw('users.id as id_user, users.jam_kerja, users.nama, users.jabatan, '.implode(', ', $select))
            ->leftJoin('absensi', function ($join) use ($tanggalAwal, $tanggalAkhir) {
                $join->on('users.id', '=', 'absensi.id_user')
                    ->whereBetween('tanggal_absen', [$tanggalAwal, $tanggalAkhir]);
            })
            ->groupByRaw('users.id, users.jam_kerja, users.nama, users.jabatan')
            ->orderBy('users.nama')
            ->get();

        foreach ($rekap as $baris) {
            $baris->total_jam_terlambat = $this->totalTerlambatJam($baris, $tanggalAwal, $tanggalAkhir);
        }

        $this->anotasiShift($rekap, $tanggalAwal, $tanggalAkhir);
        $this->tandaiIzinDisetujui($rekap, $tanggalAwal, $tanggalAkhir);

        return $rekap;
    }

    /** Anotasi sj_N = jam masuk shift yang berlaku per hari (fallback jam_kerja user). */
    public function anotasiShift(Collection $rekap, string $tanggalAwal, string $tanggalAkhir): void
    {
        $peta = $this->jadwal->petaRentang($tanggalAwal, $tanggalAkhir);

        foreach ($rekap as $baris) {
            for ($i = 1; $i <= 31; $i++) {
                $tanggal = $this->tanggalUntukHari($i, $tanggalAwal, $tanggalAkhir);

                if ($tanggal === null) {
                    continue;
                }

                $baris->{"sj_{$i}"} = $this->jadwal->jamMasukPada(
                    $peta,
                    (int) $baris->id_user,
                    $tanggal,
                    $baris->jam_kerja ?? null
                );
            }
        }
    }

    /**
     * Hari tanpa presensi yang tercakup izin disetujui ditandai "Izin" (bukan alfa).
     * Izin yang ditolak/diabaikan tidak ditandai sehingga tetap alfa (A).
     */
    public function tandaiIzinDisetujui(Collection $rekap, string $tanggalAwal, string $tanggalAkhir): void
    {
        $izin = LeaveRequest::where('status', 'disetujui')
            ->where(function ($q) use ($tanggalAwal, $tanggalAkhir) {
                $q->whereBetween('tanggal_mulai', [$tanggalAwal, $tanggalAkhir])
                    ->orWhereBetween('tanggal_selesai', [$tanggalAwal, $tanggalAkhir])
                    ->orWhere(function ($q2) use ($tanggalAwal, $tanggalAkhir) {
                        $q2->where('tanggal_mulai', '<=', $tanggalAwal)
                            ->where(function ($q3) use ($tanggalAkhir) {
                                $q3->where('tanggal_selesai', '>=', $tanggalAkhir)->orWhereNull('tanggal_selesai');
                            });
                    });
            })
            ->get(['user_id', 'tanggal_mulai', 'tanggal_selesai'])
            ->groupBy('user_id');

        foreach ($rekap as $baris) {
            $jumlahIzin = 0;

            foreach ($izin[$baris->id_user] ?? [] as $pengajuan) {
                $mulai = Carbon::parse($pengajuan->tanggal_mulai)->startOfDay();
                $selesai = $pengajuan->tanggal_selesai
                    ? Carbon::parse($pengajuan->tanggal_selesai)->startOfDay()
                    : $mulai->copy();

                for ($tgl = $mulai->copy(); $tgl->lte($selesai); $tgl->addDay()) {
                    $kunci = 'tgl_'.$tgl->day;

                    if (property_exists($baris, $kunci) && $baris->$kunci === '') {
                        $baris->$kunci = 'Izin';
                        $jumlahIzin++;
                    }
                }
            }

            $baris->jumlah_izin = $jumlahIzin;
        }
    }

    public function totalTerlambatJam(object $baris, ?string $tanggalAwal = null, ?string $tanggalAkhir = null): float
    {
        $total = 0.0;
        $peta = ($tanggalAwal && $tanggalAkhir) ? $this->jadwal->petaRentang($tanggalAwal, $tanggalAkhir) : collect();

        for ($i = 1; $i <= 31; $i++) {
            $kunci = "tgl_{$i}";

            if (! isset($baris->$kunci) || $baris->$kunci === '' || ! preg_match('/^\d{2}:\d{2}/', (string) $baris->$kunci)) {
                continue;
            }

            $jamMasuk = substr((string) $baris->$kunci, 0, 5);
            $bawaan = isset($baris->jam_kerja) && $baris->jam_kerja ? substr((string) $baris->jam_kerja, 0, 5) : null;
            $jamKerja = $bawaan;

            if ($tanggalAwal && $tanggalAkhir && isset($baris->id_user)) {
                $tanggal = $this->tanggalUntukHari($i, $tanggalAwal, $tanggalAkhir);
                $jamKerja = $tanggal
                    ? $this->jadwal->jamMasukPada($peta, (int) $baris->id_user, $tanggal, $baris->jam_kerja ?? null)
                    : $bawaan;
            }

            if ($jamKerja && $jamMasuk > $jamKerja) {
                $total += abs(Carbon::parse($jamMasuk, 'Asia/Jakarta')
                    ->diffInMinutes(Carbon::parse($jamKerja, 'Asia/Jakarta'))) / 60;
            }
        }

        return $total;
    }

    public function menitTerlambat(string $jamMasuk, string $jamKerja): int
    {
        if ($jamMasuk <= $jamKerja) {
            return 0;
        }

        return abs(Carbon::parse($jamMasuk, 'Asia/Jakarta')
            ->diffInMinutes(Carbon::parse($jamKerja, 'Asia/Jakarta')));
    }

    /**
     * Petakan nomor hari (tgl_N) ke tanggal penuh. Rentang dibatasi 62 hari
     * sehingga hanya mencakup maksimal 2 bulan kalender: hari >= hari awal
     * milik bulan awal, sisanya milik bulan akhir.
     */
    public function tanggalUntukHari(int $hari, string $tanggalAwal, string $tanggalAkhir): ?string
    {
        $awal = Carbon::parse($tanggalAwal);
        $akhir = Carbon::parse($tanggalAkhir);

        if ($awal->month === $akhir->month && $awal->year === $akhir->year) {
            return $awal->copy()->day($hari)->toDateString();
        }

        $target = $hari >= $awal->day
            ? $awal->copy()->day($hari)
            : $akhir->copy()->day($hari);

        if ($target->lt($awal) || $target->gt($akhir)) {
            return null;
        }

        return $target->toDateString();
    }
}
