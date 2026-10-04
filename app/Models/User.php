<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'role',
        'nik',
        'nuptk',
        'nbm',
        'nama',
        'nomor_hp',
        'email',
        'google_id',
        'password',
        'jabatan',
        'jam_kerja',
        'jam_pulang',
        'pasfoto',
        'face_descriptor',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'face_descriptor',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'face_descriptor' => 'array',
        ];
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_user');
    }
}
