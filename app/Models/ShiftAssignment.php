<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'shift_id', 'tanggal_mulai', 'tanggal_selesai'];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date:Y-m-d',
            'tanggal_selesai' => 'date:Y-m-d',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function scopeBerlakuPada(Builder $query, string $tanggal): Builder
    {
        return $query->where('tanggal_mulai', '<=', $tanggal)
            ->where(function ($q) use ($tanggal) {
                $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai', '>=', $tanggal);
            });
    }

    /** Rentang baru bertabrakan bila menyentuh penugasan lain user yang sama. */
    public static function bertabrakan(int $userId, string $mulai, ?string $selesai, ?int $kecualiId = null): bool
    {
        return static::where('user_id', $userId)
            ->when($kecualiId, fn ($q) => $q->where('id', '!=', $kecualiId))
            ->where('tanggal_mulai', '<=', $selesai ?? '9999-12-31')
            ->where(function ($q) use ($mulai) {
                $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai', '>=', $mulai);
            })
            ->exists();
    }
}
