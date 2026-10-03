<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'bukti_path',
        'status',
        'approved_by',
        'decided_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date:Y-m-d',
            'tanggal_selesai' => 'date:Y-m-d',
            'decided_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeMilikPengguna(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeTerbaru(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }
}
