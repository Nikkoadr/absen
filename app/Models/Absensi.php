<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'id_user',
        'gerbang_id',
        'tanggal_absen',
        'jam_masuk',
        'jam_keluar',
        'foto_masuk',
        'foto_keluar',
        'lokasi_masuk',
        'lokasi_keluar',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_absen' => 'date:Y-m-d',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function gerbang()
    {
        return $this->belongsTo(Perangkat::class, 'gerbang_id');
    }

    public function scopePadaTanggal(Builder $query, string $tanggal): Builder
    {
        return $query->where('tanggal_absen', $tanggal);
    }

    public function scopeMilikPengguna(Builder $query, int $userId): Builder
    {
        return $query->where('id_user', $userId);
    }

    public function scopeBulan(Builder $query, int $bulan, int $tahun): Builder
    {
        return $query->whereMonth('tanggal_absen', $bulan)->whereYear('tanggal_absen', $tahun);
    }

    public function getSudahPulangAttribute(): bool
    {
        return $this->jam_keluar !== null;
    }
}
