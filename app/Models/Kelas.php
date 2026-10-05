<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'kompetensi_id',
        'nama',
        'tingkat',
        'jam_masuk',
        'jam_pulang',
        'wali_kelas_user_id',
    ];

    public function kompetensi(): BelongsTo
    {
        return $this->belongsTo(KompetensiKeahlian::class, 'kompetensi_id');
    }

    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_kelas_user_id');
    }

    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
}
