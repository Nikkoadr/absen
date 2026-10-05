<?php

namespace App\Support;

use App\Models\Holiday;
use Illuminate\Support\Carbon;

class HariKerja
{
    public static function daftarLibur(int $bulan, int $tahun): array
    {
        return Holiday::padaBulan($bulan, $tahun)->pluck('tanggal')
            ->map(fn ($t) => Carbon::parse($t)->toDateString())
            ->all();
    }

    public static function jumlahHariKerja(int $bulan, int $tahun, ?string $sampaiTanggal = null): int
    {
        $batas = $sampaiTanggal ?? Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta')->endOfMonth()->toDateString();
        $mulai = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $akhir = Carbon::parse($batas, 'Asia/Jakarta');
        $libur = array_flip(static::daftarLibur($bulan, $tahun));

        $jumlah = 0;
        $cursor = $mulai->copy();
        while ($cursor->lte($akhir)) {
            if (! $cursor->isWeekend() && ! isset($libur[$cursor->toDateString()])) {
                $jumlah++;
            }
            $cursor->addDay();
        }

        return $jumlah;
    }

    public static function adalahHariKerja(string $tanggal): bool
    {
        $hari = Carbon::parse($tanggal, 'Asia/Jakarta');

        if ($hari->isWeekend()) {
            return false;
        }

        return ! Holiday::tanggal($hari->toDateString())->exists();
    }
}
