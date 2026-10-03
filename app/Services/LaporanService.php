<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LaporanService
{
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
            $baris->total_jam_terlambat = $this->totalTerlambatJam($baris);
        }

        return $rekap;
    }

    public function totalTerlambatJam(object $baris): float
    {
        $total = 0.0;

        for ($i = 1; $i <= 31; $i++) {
            $kunci = "tgl_{$i}";

            if (! isset($baris->$kunci) || $baris->$kunci === '') {
                continue;
            }

            $jamMasuk = substr((string) $baris->$kunci, 0, 5);
            $jamKerja = isset($baris->jam_kerja) && $baris->jam_kerja
                ? substr((string) $baris->jam_kerja, 0, 5)
                : null;

            if ($jamKerja && $jamMasuk > $jamKerja) {
                $total += Carbon::parse($jamMasuk, 'Asia/Jakarta')
                    ->diffInMinutes(Carbon::parse($jamKerja, 'Asia/Jakarta')) / 60;
            }
        }

        return $total;
    }

    public function menitTerlambat(string $jamMasuk, string $jamKerja): int
    {
        if ($jamMasuk <= $jamKerja) {
            return 0;
        }

        return Carbon::parse($jamMasuk, 'Asia/Jakarta')
            ->diffInMinutes(Carbon::parse($jamKerja, 'Asia/Jakarta'));
    }
}
