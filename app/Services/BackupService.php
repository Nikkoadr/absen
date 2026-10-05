<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

/**
 * Cadangan database MySQL via mysqldump Laragon. Maksimal 7 berkas terbaru.
 */
class BackupService
{
    public int $maksimal = 7;

    public function direktori(): string
    {
        $dir = storage_path('app/backup');

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return $dir;
    }

    /** @return string nama berkas yang dibuat */
    public function buat(): string
    {
        $dump = $this->cariMysqldump();

        if (! $dump) {
            throw new \RuntimeException('mysqldump tidak ditemukan di Laragon.');
        }

        $cfg = config('database.connections.mysql');
        $nama = 'absen-'.Carbon::now('Asia/Jakarta')->format('Ymd-His').'.sql';
        $tujuan = $this->direktori().DIRECTORY_SEPARATOR.$nama;

        $proses = new Process([
            $dump,
            '--host='.$cfg['host'],
            '--port='.$cfg['port'],
            '--user='.$cfg['username'],
            '--single-transaction',
            '--skip-lock-tables',
            $cfg['database'],
            '--result-file='.$tujuan,
        ], null, ['MYSQL_PWD' => (string) ($cfg['password'] ?? '')]);
        $proses->setTimeout(300);
        $proses->mustRun();

        $this->pangkas();

        return $nama;
    }

    /** @return array<int,array{nama:string,ukuran:int,waktu:int}> terbaru dulu */
    public function daftar(): array
    {
        $dir = $this->direktori();
        $daftar = [];

        foreach (glob($dir.DIRECTORY_SEPARATOR.'absen-*.sql') ?: [] as $berkas) {
            $daftar[] = [
                'nama' => basename($berkas),
                'ukuran' => filesize($berkas),
                'waktu' => filemtime($berkas),
            ];
        }

        usort($daftar, fn ($a, $b) => $b['waktu'] <=> $a['waktu']);

        return $daftar;
    }

    public function berkas(string $nama): ?string
    {
        if (! preg_match('/^absen-\d{8}-\d{6}\.sql$/', $nama)) {
            return null;
        }

        $path = $this->direktori().DIRECTORY_SEPARATOR.$nama;

        return is_file($path) ? $path : null;
    }

    protected function pangkas(): void
    {
        $daftar = $this->daftar();

        foreach (array_slice($daftar, $this->maksimal) as $lama) {
            @unlink($this->direktori().DIRECTORY_SEPARATOR.$lama['nama']);
        }
    }

    protected function cariMysqldump(): ?string
    {
        foreach (glob('D:'.DIRECTORY_SEPARATOR.'laragon'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'mysql'.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'mysqldump.exe') ?: [] as $biner) {
            return $biner;
        }

        return null;
    }
}
