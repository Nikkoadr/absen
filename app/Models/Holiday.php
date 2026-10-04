<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = ['tanggal', 'nama', 'berulang_tiap_tahun'];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date:Y-m-d',
            'berulang_tiap_tahun' => 'boolean',
        ];
    }

    public function scopePadaBulan(Builder $query, int $bulan, int $tahun): Builder
    {
        return $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
    }

    public function scopeTanggal(Builder $query, string $tanggal): Builder
    {
        $bulanHari = Carbon::parse($tanggal)->format('m-d');

        return $query->where(function ($q) use ($tanggal, $bulanHari) {
            $q->where('tanggal', $tanggal)
                ->orWhere(function ($q2) use ($bulanHari) {
                    $q2->where('berulang_tiap_tahun', true)
                        ->whereRaw("DATE_FORMAT(tanggal, '%m-%d') = ?", [$bulanHari]);
                });
        });
    }
}
