<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'tanggal_absen',
        'jam_masuk',
        'jam_keluar',
        'foto_masuk',
        'foto_keluar',
        'lokasi_masuk',
        'lokasi_keluar',
    ];

    protected $casts = [
        'tanggal_absen' => 'date:Y-m-d',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
