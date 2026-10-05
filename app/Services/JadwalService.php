<?php

namespace App\Services;

use App\Models\ShiftAssignment;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Menyelesaikan jam kerja yang berlaku untuk seorang pengguna pada tanggal tertentu.
 * Urutan fallback: penugasan shift pada tanggal itu -> jam_kerja bawaan user -> null.
 */
class JadwalService
{
    /** Bawaan jam masuk: karyawan dari profilnya, siswa dari jadwal kelasnya. */
    public function bawaanPengguna(?User $user): ?string
    {
        if (! $user) {
            return null;
        }

        if ($user->role === 'siswa') {
            return $user->siswa?->kelas?->jam_masuk;
        }

        return $user->jam_kerja;
    }

    /** @return array{jam_masuk: ?string, jam_pulang: ?string, nama: ?string} */
    public function untukTanggal(int $userId, string $tanggal): array
    {
        $tugas = ShiftAssignment::with('shift')
            ->where('user_id', $userId)
            ->berlakuPada($tanggal)
            ->orderByDesc('tanggal_mulai')
            ->first();

        if ($tugas?->shift) {
            return [
                'jam_masuk' => substr((string) $tugas->shift->jam_masuk, 0, 5),
                'jam_pulang' => $tugas->shift->jam_pulang ? substr((string) $tugas->shift->jam_pulang, 0, 5) : null,
                'nama' => $tugas->shift->nama,
            ];
        }

        $user = User::find($userId);

        if ($user?->role === 'siswa' && ($kls = $user->siswa?->kelas)) {
            return [
                'jam_masuk' => substr((string) $kls->jam_masuk, 0, 5),
                'jam_pulang' => $kls->jam_pulang ? substr((string) $kls->jam_pulang, 0, 5) : null,
                'nama' => null,
            ];
        }

        return [
            'jam_masuk' => $this->bawaanPengguna($user) ? substr((string) $this->bawaanPengguna($user), 0, 5) : null,
            'jam_pulang' => $user?->jam_pulang ? substr((string) $user->jam_pulang, 0, 5) : null,
            'nama' => null,
        ];
    }

    /**
     * Muat semua penugasan yang bersinggungan dengan rentang sekaligus,
     * dikelompokkan per user agar rekap bulanan tidak N+1.
     *
     * @return Collection<int, Collection<int, ShiftAssignment>>
     */
    public function petaRentang(string $tanggalAwal, string $tanggalAkhir): Collection
    {
        return ShiftAssignment::with('shift')
            ->where('tanggal_mulai', '<=', $tanggalAkhir)
            ->where(function ($q) use ($tanggalAwal) {
                $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai', '>=', $tanggalAwal);
            })
            ->orderByDesc('tanggal_mulai')
            ->get()
            ->groupBy('user_id');
    }

    public function jamMasukPada(Collection $peta, int $userId, string $tanggal, ?string $bawaan = null): ?string
    {
        $tugas = ($peta[$userId] ?? collect())->first(function ($t) use ($tanggal) {
            $mulaiOk = substr((string) $t->tanggal_mulai, 0, 10) <= $tanggal;
            $selesai = $t->tanggal_selesai ? substr((string) $t->tanggal_selesai, 0, 10) : null;

            return $mulaiOk && ($selesai === null || $selesai >= $tanggal);
        });

        if ($tugas?->shift) {
            return substr((string) $tugas->shift->jam_masuk, 0, 5);
        }

        return $bawaan ? substr($bawaan, 0, 5) : null;
    }

    /** Varian untukTanggal memakai peta yang sudah dimuat (tanpa query per baris). */
    public function untukTanggalDariPeta(Collection $peta, int $userId, string $tanggal, ?User $user = null): array
    {
        $tugas = ($peta[$userId] ?? collect())->first(function ($t) use ($tanggal) {
            $mulaiOk = substr((string) $t->tanggal_mulai, 0, 10) <= $tanggal;
            $selesai = $t->tanggal_selesai ? substr((string) $t->tanggal_selesai, 0, 10) : null;

            return $mulaiOk && ($selesai === null || $selesai >= $tanggal);
        });

        if ($tugas?->shift) {
            return [
                'jam_masuk' => substr((string) $tugas->shift->jam_masuk, 0, 5),
                'jam_pulang' => $tugas->shift->jam_pulang ? substr((string) $tugas->shift->jam_pulang, 0, 5) : null,
                'nama' => $tugas->shift->nama,
            ];
        }

        if ($user?->role === 'siswa' && ($kls = $user->siswa?->kelas)) {
            return [
                'jam_masuk' => substr((string) $kls->jam_masuk, 0, 5),
                'jam_pulang' => $kls->jam_pulang ? substr((string) $kls->jam_pulang, 0, 5) : null,
                'nama' => null,
            ];
        }

        return [
            'jam_masuk' => $user?->jam_kerja ? substr((string) $user->jam_kerja, 0, 5) : null,
            'jam_pulang' => $user?->jam_pulang ? substr((string) $user->jam_pulang, 0, 5) : null,
            'nama' => null,
        ];
    }

    /** Isi jam_kerja_hari tiap baris presensi sesuai shift tanggal itu (sekali query peta). */
    public function anotasiJamKerja(Collection $absensi, ?string $bawaanDefault = null): void
    {
        if ($absensi->isEmpty()) {
            return;
        }

        $tanggal = $absensi->map(fn ($a) => substr((string) $a->tanggal_absen, 0, 10));
        $peta = $this->petaRentang($tanggal->min(), $tanggal->max());

        foreach ($absensi as $a) {
            $u = $a->relationLoaded('user') ? $a->user : null;
            $bawaan = $u ? $this->bawaanPengguna($u) : $bawaanDefault;
            $a->jam_kerja_hari = $this->jamMasukPada($peta, (int) $a->id_user, substr((string) $a->tanggal_absen, 0, 10), $bawaan);
        }
    }
}
