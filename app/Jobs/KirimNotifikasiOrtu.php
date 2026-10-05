<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class KirimNotifikasiOrtu implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $userId,
        public string $jenis,
        public string $jam,
        public bool $rfid = false,
    ) {}

    public function handle(TelegramService $telegram): void
    {
        $user = User::find($this->userId);

        if (! $user) {
            return;
        }

        if ($this->rfid && $user->siswa) {
            $telegram->kirimRfid($user, $user->siswa);

            return;
        }

        $telegram->kabariOrangTua($user, $this->jenis, $this->jam);
    }
}
