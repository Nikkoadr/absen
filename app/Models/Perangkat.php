<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perangkat extends Model
{
    use HasFactory;

    protected $table = 'perangkats';

    protected $fillable = [
        'nama',
        'key_hash',
        'latitude',
        'longitude',
        'aktif',
        'terakhir_aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
            'terakhir_aktif' => 'datetime',
        ];
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'gerbang_id');
    }
}
