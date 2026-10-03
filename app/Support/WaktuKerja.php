<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class WaktuKerja
{
    public static function menitSelisih(string $awal, string $akhir): int
    {
        return Carbon::parse($awal, 'Asia/Jakarta')->diffInMinutes(Carbon::parse($akhir, 'Asia/Jakarta'));
    }

    public static function formatSelisih(string $dari, string $ke): string
    {
        $menit = abs(static::menitSelisih($dari, $ke));

        return intdiv($menit, 60).':'.str_pad((string) ($menit % 60), 2, '0', STR_PAD_LEFT);
    }

    public static function terlambat(string $jamMasuk, ?string $jamKerja): bool
    {
        return $jamKerja !== null && substr($jamMasuk, 0, 5) > substr($jamKerja, 0, 5);
    }
}
