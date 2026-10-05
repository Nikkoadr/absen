<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Mencatat tambah/ubah/hapus data penting. Nilai sensitif tidak disimpan.
 */
class AuditObserver
{
    protected array $sensitif = [
        'password',
        'remember_token',
        'face_descriptor',
        'google_id',
        'telegram_bot_token',
        'key_hash',
    ];

    public function created(Model $model): void
    {
        $this->catat($model, 'tambah', null, $this->bersih($model->getAttributes()));
    }

    public function updated(Model $model): void
    {
        $berubah = $model->getChanges();
        unset($berubah['updated_at']);

        if ($berubah === []) {
            return;
        }

        $lama = [];
        foreach (array_keys($berubah) as $kunci) {
            $lama[$kunci] = $model->getOriginal($kunci);
        }

        $this->catat($model, 'ubah', $this->bersih($lama), $this->bersih($berubah));
    }

    public function deleted(Model $model): void
    {
        $this->catat($model, 'hapus', $this->bersih($model->getAttributes()), null);
    }

    protected function bersih(array $data): array
    {
        foreach ($this->sensitif as $kunci) {
            unset($data[$kunci]);
        }

        return $data;
    }

    protected function catat(Model $model, string $aksi, ?array $lama, ?array $baru): void
    {
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'aksi' => $aksi,
                'tabel' => $model->getTable(),
                'record_id' => $model->getKey(),
                'data_lama' => $lama,
                'data_baru' => $baru,
                'ip' => request()->ip(),
            ]);
        } catch (\Throwable) {
            // Audit tidak boleh menggagalkan operasi utama.
        }
    }
}
