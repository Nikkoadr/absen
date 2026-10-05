<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class UjiTelegram extends Command
{
    protected $signature = 'telegram:uji {chat_id : ID chat tujuan}';

    protected $description = 'Kirim pesan uji bot Telegram ke satu ID chat.';

    public function handle(TelegramService $telegram): int
    {
        if (! $telegram->aktif()) {
            $this->error('Bot belum aktif. Isi token di Pengaturan dan centang aktif.');

            return self::FAILURE;
        }

        $ok = $telegram->kirim(
            (string) $this->argument('chat_id'),
            'Pesan uji bot Presensi SMK Muhammadiyah Kandanghaur. Bot terhubung.'
        );

        if (! $ok) {
            $this->error('Gagal terkirim. Periksa token dan pastikan ID chat sudah chat bot minimal sekali.');

            return self::FAILURE;
        }

        $this->info('Terkirim.');

        return self::SUCCESS;
    }
}
