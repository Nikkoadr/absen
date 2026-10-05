<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'aksi',
        'tabel',
        'record_id',
        'data_lama',
        'data_baru',
        'ip',
    ];

    protected function casts(): array
    {
        return [
            'data_lama' => 'array',
            'data_baru' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function pelaku(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
