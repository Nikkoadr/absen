<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KompetensiKeahlian extends Model
{
    use HasFactory;

    protected $table = 'kompetensi_keahlian';

    protected $fillable = [
        'nama',
        'singkatan',
    ];

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'kompetensi_id');
    }
}
