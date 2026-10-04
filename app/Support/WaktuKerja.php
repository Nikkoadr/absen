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
}
