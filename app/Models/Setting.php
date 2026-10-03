<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'setting';

    protected $fillable = [
        'namaLokasi',
        'latitude',
        'longitude',
        'radius',
        'limit_absen',
    ];
}
