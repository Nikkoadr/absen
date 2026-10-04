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
        $mulai = Carbon::parse($tanggalAwal, 'Asia/Jakarta')->startOfDay();
        $selesai = Carbon::parse($tanggalAkhir, 'Asia/Jakarta')->startOfDay();

        $select = [];
        $cursor = $mulai->copy();
        while ($cursor->lte($selesai)) {
            $kunci = $cursor->format('Ymd');
            $tgl = $cursor->toDateString();
            $select[] = "MAX(CASE WHEN tanggal_absen = '{$tgl}' THEN CONCAT(jam_masuk, '-', IFNULL(jam_keluar, '00:00:00')) ELSE '' END) as tgl_{$kunci}";
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

        $peta = $this->jadwal->petaRentang($tanggalAwal, $tanggalAkhir);

        foreach ($rekap as $baris) {
            $baris->total_jam_terlambat = $this->totalTerlambatJam($baris, $peta, $tanggalAwal, $tanggalAkhir);
        }

        $this->anotasiShift($rekap, $peta, $tanggalAwal, $tanggalAkhir);
        $this->tandaiIzinDisetujui($rekap, $tanggalAwal, $tanggalAkhir);

        return $rekap;
    }

    /** Anotasi sj_Ymd = jam masuk shift yang berlaku per tanggal (fallback jam_kerja user). */
    public function anotasiShift(Collection $rekap, Collection $peta, string $tanggalAwal, string $tanggalAkhir): void
    {
        $mulai = Carbon::parse($tanggalAwal, 'Asia/Jakarta')->startOfDay();
        $selesai = Carbon::parse($tanggalAkhir, 'Asia/Jakarta')->startOfDay();

        foreach ($rekap as $baris) {
            $cursor = $mulai->copy();
            while ($cursor->lte($selesai)) {
                $tanggal = $cursor->toDateString();
                $baris->{'sj_'.$cursor->format('Ymd')} = $this->jadwal->jamMasukPada(
                    $peta,
                    (int) $baris->id_user,
                    $tanggal,
                    $baris->jam_kerja ?? null
                );
                $cursor->addDay();
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
                    $kunci = 'tgl_'.$tgl->format('Ymd');

                    if (property_exists($baris, $kunci) && $baris->$kunci === '') {
                        $baris->$kunci = 'Izin';
                        $jumlahIzin++;
                    }
                }
            }

            $baris->jumlah_izin = $jumlahIzin;
        }
    }

    public function totalTerlambatJam(object $baris, Collection $peta, string $tanggalAwal, string $tanggalAkhir): float
    {
        $total = 0.0;
        $mulai = Carbon::parse($tanggalAwal, 'Asia/Jakarta')->startOfDay();
        $selesai = Carbon::parse($tanggalAkhir, 'Asia/Jakarta')->startOfDay();
        $cursor = $mulai->copy();

        while ($cursor->lte($selesai)) {
            $kunci = 'tgl_'.$cursor->format('Ymd');

            if (! isset($baris->$kunci) || $baris->$kunci === '' || ! preg_match('/^\d{2}:\d{2}/', (string) $baris->$kunci)) {
                $cursor->addDay();

                continue;
            }

            $jamMasuk = substr((string) $baris->$kunci, 0, 5);
            $bawaan = isset($baris->jam_kerja) && $baris->jam_kerja ? substr((string) $baris->jam_kerja, 0, 5) : null;
            $tanggal = $cursor->toDateString();
            $jamKerja = isset($baris->id_user)
                ? $this->jadwal->jamMasukPada($peta, (int) $baris->id_user, $tanggal, $baris->jam_kerja ?? null)
                : $bawaan;

            if ($jamKerja && $jamMasuk > $jamKerja) {
                $total += abs(Carbon::parse($jamMasuk, 'Asia/Jakarta')
                    ->diffInMinutes(Carbon::parse($jamKerja, 'Asia/Jakarta'))) / 60;
            }

            $cursor->addDay();
        }

        return $total;
    }
}
