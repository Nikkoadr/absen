<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use Illuminate\Console\Command;
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

        // Data publik non-sensitif; tanpa verifikasi SSL karena cacert Laragon bermasalah (cURL 77).
        $res = Http::withoutVerifying()->timeout(20)->get($url);

        if (! $res->successful()) {
            $this->error("Gagal mengunduh data tahun {$tahun} (HTTP {$res->status()}).");

            return self::FAILURE;
        }

        $dibuat = 0;
        $diperbarui = 0;

        foreach ($res->json() ?? [] as $baris) {
            foreach ($this->uraiTanggal((string) ($baris['date'] ?? ''), $tahun) as $tanggal) {
                $model = Holiday::updateOrCreate(
                    ['tanggal' => $tanggal],
                    ['nama' => (string) ($baris['holiday_name'] ?? 'Hari libur nasional')]
                );
                $model->wasRecentlyCreated ? $dibuat++ : $diperbarui++;
            }
        }

        $this->info("Sinkron {$tahun} selesai: {$dibuat} baru, {$diperbarui} diperbarui.");

        return self::SUCCESS;
    }

    /** @return string[] tanggal Y-m-d (kembangkan rentang "21 Maret to 22 Maret") */
    public function uraiTanggal(string $mentah, int $tahun): array
    {
        $bagian = preg_split('/\s+to\s+/i', trim($mentah));
        $hasil = [];

        foreach ($bagian as $b) {
            if (preg_match('/^(\d{1,2})\s+([A-Za-z]+)$/', trim($b), $m)) {
                $bulan = self::BULAN[strtolower($m[2])] ?? null;

                if ($bulan) {
                    $hasil[] = sprintf('%04d-%02d-%02d', $tahun, $bulan, (int) $m[1]);
                }
            }
        }

        return $hasil;
    }
}
