<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class SinkronHariLibur extends Command
{
    protected $signature = 'holidays:sync {tahun? : Tahun kalender, default tahun berjalan}';

    protected $description = 'Sinkron hari libur nasional Indonesia dari data terbuka GitHub (fajriyan/open-data).';

    const BULAN = [
        'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
        'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
        'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
    ];

    public function handle(): int
    {
        $tahun = (int) ($this->argument('tahun') ?? now('Asia/Jakarta')->year);
        $url = "https://raw.githubusercontent.com/fajriyan/open-data/main/data-nasional/referensi/kalender/libur-nasional/{$tahun}.json";

        // Data publik non-sensitif; verifikasi SSL diutamakan, fallback tanpa
        // verifikasi hanya bila cacert setempat bermasalah (mis. cURL 77 di Laragon).
        try {
            $res = Http::timeout(20)->get($url);
        } catch (ConnectionException) {
            $this->warn('Verifikasi SSL gagal, mencoba tanpa verifikasi (data publik).');

            try {
                $res = Http::withoutVerifying()->timeout(20)->get($url);
            } catch (ConnectionException $e) {
                $this->error("Tidak dapat mengunduh data tahun {$tahun}. Periksa koneksi.");

                return self::FAILURE;
            }
        }

        if (! $res->successful()) {
            $this->error("Gagal mengunduh data tahun {$tahun} (HTTP {$res->status()}).");

            return self::FAILURE;
        }

        $dibuat = 0;
        $diperbarui = 0;

        foreach ($res->json() ?? [] as $baris) {
            foreach ($this->uraiTanggal((string) ($baris['date'] ?? ''), $tahun) as $tanggal) {
                $model = Holiday::firstOrCreate(
                    ['tanggal' => $tanggal],
                    ['nama' => (string) ($baris['holiday_name'] ?? 'Hari libur nasional')]
                );

                if ($model->wasRecentlyCreated) {
                    $dibuat++;
                } else {
                    $diperbarui++;
                }
            }
        }

        $this->info("Sinkron {$tahun} selesai: {$dibuat} baru, {$diperbarui} diperbarui.");

        return self::SUCCESS;
    }

    /** @return string[] tanggal Y-m-d (rentang "21 Maret to 22 Maret" dikembangkan inklusif) */
    public function uraiTanggal(string $mentah, int $tahun): array
    {
        $bagian = preg_split('/\s+to\s+/i', trim($mentah));
        $terurai = [];

        foreach ($bagian as $b) {
            if (preg_match('/^(\d{1,2})\s+([A-Za-z]+)$/', trim($b), $m)) {
                $bulan = self::BULAN[strtolower($m[2])] ?? null;

                if ($bulan && checkdate($bulan, (int) $m[1], $tahun)) {
                    $terurai[] = sprintf('%04d-%02d-%02d', $tahun, $bulan, (int) $m[1]);
                }
            }
        }

        if (count($terurai) === 2 && $terurai[1] >= $terurai[0]) {
            $hasil = [];
            $cursor = $terurai[0];
            while ($cursor <= $terurai[1]) {
                $hasil[] = $cursor;
                $cursor = date('Y-m-d', strtotime($cursor.' +1 day'));
            }

            return $hasil;
        }

        return $terurai;
    }
}
