<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AbsensiService
{
    public function __construct(protected GeoService $geo) {}

    public function pengaturan(): ?Setting
    {
        return Setting::first();
    }

    /** Foto valid bila biner PNG/JPEG asli, bukan teks acak yang di-base64. */
    public function gambarValid(?string $biner): bool
    {
        return $biner !== null
            && (str_starts_with($biner, "\x89PNG") || str_starts_with($biner, "\xFF\xD8\xFF"));
    }

    /**
     * @return array{status:string,message:string,http:int}
     */
    public function catat(User $user, string $lokasi, ?string $fotoBiner): array
    {
        $now = Carbon::now('Asia/Jakarta');
        $tanggal = $now->toDateString();
        $jam = $now->toTimeString();
        $setting = $this->pengaturan();

        if (! $setting) {
            return ['status' => 'error', 'message' => 'Pengaturan lokasi belum dikonfigurasi. Hubungi admin.', 'http' => 500];
        }

        [$latUser, $lonUser] = array_map('floatval', explode(',', $lokasi));
        $radius = (int) round($this->geo->jarakMeter(
            (float) $setting->latitude,
            (float) $setting->longitude,
            $latUser,
            $lonUser
        ));

        if ($radius > (int) $setting->radius) {
            return ['status' => 'error', 'message' => "Maaf, jarak Anda {$radius} M dari {$setting->nama_lokasi}.", 'http' => 422];
        }

        if (! $this->gambarValid($fotoBiner)) {
            return ['status' => 'error', 'message' => 'Foto tidak valid. Ambil ulang dari kamera.', 'http' => 422];
        }

        $disk = config('filesystems.default');

        try {
            return DB::transaction(function () use ($user, $tanggal, $jam, $lokasi, $fotoBiner, $disk, $setting, $now) {
                $absen = Absensi::where('tanggal_absen', $tanggal)
                    ->where('id_user', $user->id)
                    ->lockForUpdate()
                    ->first();

                if ($absen) {
                    if ($absen->jam_keluar) {
                        return ['status' => 'error', 'message' => 'Anda sudah presensi pulang hari ini.', 'http' => 422];
                    }

                    $masuk = Carbon::parse("{$tanggal} {$absen->jam_masuk}", 'Asia/Jakarta');
                    if (abs($now->diffInSeconds($masuk, false)) < 300) {
                        return ['status' => 'error', 'message' => 'Anda tidak bisa presensi keluar terlalu cepat setelah presensi masuk! Tunggu 5 menit.', 'http' => 422];
                    }

                    $namaFoto = "{$user->id}-{$tanggal}-keluar.png";
                    $absen->update([
                        'jam_keluar' => $jam,
                        'foto_keluar' => $namaFoto,
                        'lokasi_keluar' => $lokasi,
                    ]);
                    Storage::disk($disk)->put($namaFoto, $fotoBiner);

                    return ['status' => 'sukses', 'message' => 'Anda sudah presensi pulang. Hati-hati di jalan!', 'http' => 200];
                }

                if ($setting->limit_absen && $jam > substr((string) $setting->limit_absen, 0, 8)) {
                    return ['status' => 'error', 'message' => 'Presensi masuk sudah ditutup pukul '.substr((string) $setting->limit_absen, 0, 5).'.', 'http' => 422];
                }

                $namaFoto = "{$user->id}-{$tanggal}-masuk.png";
                try {
                    Absensi::create([
                        'id_user' => $user->id,
                        'tanggal_absen' => $tanggal,
                        'jam_masuk' => $jam,
                        'foto_masuk' => $namaFoto,
                        'lokasi_masuk' => $lokasi,
                    ]);
                } catch (QueryException) {
                    return ['status' => 'error', 'message' => 'Presensi Anda sudah tercatat. Muat ulang halaman.', 'http' => 422];
                }
                Storage::disk($disk)->put($namaFoto, $fotoBiner);

                return ['status' => 'sukses', 'message' => 'Terima kasih, Anda sudah melakukan presensi masuk hari ini.', 'http' => 200];
            });
        } catch (\Throwable $e) {
            report($e);

            return ['status' => 'error', 'message' => 'Gagal menyimpan presensi. Coba lagi.', 'http' => 500];
        }
    }
}
