<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';

    protected $description = 'Cadangkan database MySQL ke storage/app/backup (maksimal 7 berkas).';

    public function handle(BackupService $backup): int
    {
        try {
            $nama = $backup->buat();
        } catch (\Throwable $e) {
            $this->error('Gagal: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info("Tersimpan: {$nama}");

        return self::SUCCESS;
    }
}
