<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'setting';

    protected $fillable = [
        'nama_lokasi',
        'latitude',
        'longitude',
        'radius',
        'limit_absen',
        'telegram_bot_token',
        'telegram_aktif',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'radius' => 'integer',
            'telegram_aktif' => 'boolean',
        ];
    }
}
