<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

/**
 * Notifikasi presensi siswa ke orang tua via bot Telegram.
 * Gagal kirim tidak boleh menggagalkan presensi.
 */
class TelegramService
{
    public function aktif(): bool
    {
        $setting = Setting::first();

        return (bool) $setting && $setting->telegram_aktif && $setting->telegram_bot_token;
    }

    public function kirim(string $chatId, string $pesan): bool
    {
        $setting = Setting::first();

        if (! $setting || ! $setting->telegram_aktif || ! $setting->telegram_bot_token) {
            return false;
        }

        try {
            $res = Http::timeout(10)->post(
                "https://api.telegram.org/bot{$setting->telegram_bot_token}/sendMessage",
                ['chat_id' => $chatId, 'text' => $pesan]
            );

            return $res->successful() && (bool) $res->json('ok', false);
        } catch (\Throwable) {
            return false;
        }
    }

    public function kabariOrangTua(User $user, string $jenis, string $jam): bool
    {
        $siswa = $user->siswa;

        if ($user->role !== 'siswa' || ! $siswa || ! $siswa->telegram_chat_id) {
            return false;
        }

        $tanggal = Carbon::now('Asia/Jakarta')->isoFormat('dddd, D MMMM Y');
        $pesan = "Presensi SMK Muhammadiyah Kandanghaur\n"
            ."Ananda {$user->nama} tercatat presensi {$jenis} pukul {$jam} pada {$tanggal}.";

        return $this->kirim($siswa->telegram_chat_id, $pesan);
    }

    /** Notifikasi tap RFID gerbang: menyebut nama, kelas, tanggal, dan jam. */
    public function kirimRfid(User $user, Siswa $siswa): bool
    {
        if (! $siswa->telegram_chat_id) {
            return false;
        }

        $kelas = $siswa->kelas ? "{$siswa->kelas->tingkat} {$siswa->kelas->nama}" : 'tanpa kelas';
        $sekarang = Carbon::now('Asia/Jakarta');
        $pesan = "Presensi SMK Muhammadiyah Kandanghaur\n"
            ."Ananda {$user->nama} kelas {$kelas} telah masuk sekolah pada {$sekarang->isoFormat('dddd, D MMMM Y')}, di jam {$sekarang->format('H:i')}.";

        return $this->kirim($siswa->telegram_chat_id, $pesan);
    }

    public function ambilPembaruan(?int $offset = null): array
    {
        $setting = Setting::first();

        if (! $setting || ! $setting->telegram_bot_token) {
            return [];
        }

        try {
            $res = Http::timeout(35)->get(
                "https://api.telegram.org/bot{$setting->telegram_bot_token}/getUpdates",
                array_filter(['offset' => $offset, 'timeout' => 30])
            );
        } catch (\Throwable) {
            return [];
        }

        return $res->successful() ? ($res->json('result') ?? []) : [];
    }

    /** Balas satu pesan masuk dari orang tua. */
    public function tanganiPesan(array $pembaruan): void
    {
        $pesan = $pembaruan['message'] ?? $pembaruan['edited_message'] ?? null;
        $chatId = $pesan['chat']['id'] ?? null;
        $teks = trim((string) ($pesan['text'] ?? ''));

        if (! $chatId || $teks === '' || ! str_starts_with($teks, '/')) {
            return;
        }

        $perintah = strtolower(strtok($teks, " \n@"));

        match ($perintah) {
            '/id' => $this->kirim((string) $chatId, "ID chat Telegram Anda: {$chatId}\nSampaikan nomor ini ke admin sekolah agar notifikasi presensi tersambung."),
            '/start' => $this->kirim((string) $chatId, "Bot Presensi SMK Muhammadiyah Kandanghaur.\n\n/id - melihat ID chat Anda\n/riwayat - rekap kehadiran anak bulan ini\n/bantuan - daftar perintah"),
            '/bantuan', '/help' => $this->kirim((string) $chatId, "/id - melihat ID chat Anda\n/riwayat - rekap kehadiran anak bulan ini"),
            '/riwayat' => $this->kirim((string) $chatId, $this->rekapAnak((string) $chatId)),
            default => $this->kirim((string) $chatId, "Perintah tidak dikenal. Ketik /bantuan."),
        };
    }

    protected function rekapAnak(string $chatId): string
    {
        $sekarang = Carbon::now('Asia/Jakarta');
        $anak = Siswa::with('user:id,nama')->where('telegram_chat_id', $chatId)->get();

        if ($anak->isEmpty()) {
            return "Belum ada anak tertaut ke chat ini.\nKetik /id lalu sampaikan nomor itu ke admin sekolah.";
        }

        $baris = [];
        foreach ($anak as $s) {
            $hadir = \App\Models\Absensi::milikPengguna($s->user_id)
                ->bulan($sekarang->month, $sekarang->year)->count();
            $izin = \App\Models\LeaveRequest::milikPengguna($s->user_id)
                ->where('status', 'disetujui')
                ->whereMonth('tanggal_mulai', $sekarang->month)
                ->whereYear('tanggal_mulai', $sekarang->year)->count();
            $kerja = \App\Support\HariKerja::jumlahHariKerja($sekarang->month, $sekarang->year, $sekarang->toDateString());
            $alfa = max(0, $kerja - $hadir - $izin);
            $baris[] = "Ananda {$s->user->nama} ({$sekarang->isoFormat('MMMM Y')}): hadir {$hadir}, izin {$izin}, alfa {$alfa}.";
        }

        return implode("\n", $baris);
    }
}
