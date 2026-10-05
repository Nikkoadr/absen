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
        'aktif',
        'nama',
        'email',
        'google_id',
        'password',
        'tanggal_lahir',
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
            'tanggal_lahir' => 'date:Y-m-d',
            'password' => 'hashed',
            'face_descriptor' => 'array',
            'aktif' => 'boolean',
        ];
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_user');
    }

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    /* Kompatibilitas baca selama migrasi view ke relasi baru. */
    protected function profil(): ?Karyawan
    {
        return $this->relationLoaded('karyawan') ? $this->karyawan : $this->karyawan()->first();
    }

    public function getNikAttribute(): ?string
    {
        return $this->profil()?->nik;
    }

    public function getNuptkAttribute(): ?string
    {
        return $this->profil()?->nuptk;
    }

    public function getNbmAttribute(): ?string
    {
        return $this->profil()?->nbm;
    }

    public function getNomorHpAttribute(): ?string
    {
        return $this->profil()?->nomor_hp;
    }

    public function getJabatanAttribute(): ?string
    {
        return $this->profil()?->jabatan;
    }

    public function getJamKerjaAttribute(): ?string
    {
        return $this->profil()?->jam_kerja;
    }

    public function getJamPulangAttribute(): ?string
    {
        return $this->profil()?->jam_pulang;
    }
}
