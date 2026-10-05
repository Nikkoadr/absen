<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class PollTelegram extends Command
{
    protected $signature = 'telegram:poll {--sekali : Ambil sekali lalu berhenti}';

    protected $description = 'Baca balasan orang tua dari bot Telegram (/id, /riwayat) via polling.';

    public function handle(TelegramService $telegram): int
    {
        if (! $telegram->aktif()) {
            $this->error('Bot belum aktif. Isi token di Pengaturan dan centang aktif.');

            return self::FAILURE;
        }

        do {
            $offset = Cache::get('telegram_offset');
            $daftar = $telegram->ambilPembaruan($offset ? $offset + 1 : null);

            foreach ($daftar as $pembaruan) {
                Cache::forever('telegram_offset', $pembaruan['update_id']);
                $telegram->tanganiPesan($pembaruan);
            }

            if ($this->option('sekali')) {
                break;
            }

            sleep(2);
        } while (true);

        return self::SUCCESS;
    }
}
