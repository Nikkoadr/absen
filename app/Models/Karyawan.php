<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';

    protected $fillable = [
        'user_id',
        'nik',
        'nuptk',
        'nbm',
        'nomor_hp',
        'jabatan',
        'jam_kerja',
        'jam_pulang',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
