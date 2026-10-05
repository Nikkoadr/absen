<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\LeaveRequest;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Demo rotasi Oktober 2026: 5 guru, blok Pagi/Siang bertukar tengah bulan.
 * Boleh dihapus setelah demo (hapus usernya, presensi ikut terhapus).
 */
class DemoRotasiSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(42);

        $pagi = Shift::firstOrCreate(['nama' => 'Pagi'], ['jam_masuk' => '07:00:00', 'jam_pulang' => '14:00:00']);
        $siang = Shift::firstOrCreate(['nama' => 'Siang'], ['jam_masuk' => '13:00:00', 'jam_pulang' => '18:00:00']);

        $guru = [];
        foreach ([
            ['Budi Santoso', 'budi@sekolah.sch.id'],
            ['Siti Rahayu', 'siti@sekolah.sch.id'],
            ['Agus Wijaya', 'agus@sekolah.sch.id'],
            ['Dewi Lestari', 'dewi@sekolah.sch.id'],
            ['Rina Marlina', 'rina@sekolah.sch.id'],
        ] as [$nama, $email]) {
            $u = User::firstOrCreate(['email' => $email], [
                'nama' => $nama,
                'password' => 'password123',
                'role' => 'guru',
            ]);
            $u->karyawan()->updateOrCreate([], ['jam_kerja' => '07:00:00', 'jam_pulang' => '14:00:00']);
            $guru[$email] = $u;
        }

        $blok = [
            'budi@sekolah.sch.id' => [[$pagi, '2026-10-01', '2026-10-12'], [$siang, '2026-10-13', '2026-10-31']],
            'siti@sekolah.sch.id' => [[$pagi, '2026-10-01', '2026-10-12'], [$siang, '2026-10-13', '2026-10-31']],
            'agus@sekolah.sch.id' => [[$siang, '2026-10-01', '2026-10-12'], [$pagi, '2026-10-13', '2026-10-31']],
            'dewi@sekolah.sch.id' => [[$siang, '2026-10-01', '2026-10-12'], [$pagi, '2026-10-13', '2026-10-31']],
        ];

        foreach ($guru as $email => $u) {
            ShiftAssignment::where('user_id', $u->id)->delete();
            Absensi::milikPengguna($u->id)->whereBetween('tanggal_absen', ['2026-10-01', '2026-10-31'])->delete();
            LeaveRequest::milikPengguna($u->id)->where('tanggal_mulai', '>=', '2026-10-01')->delete();

            foreach ($blok[$email] ?? [] as [$shift, $mulai, $selesai]) {
                ShiftAssignment::create([
                    'user_id' => $u->id,
                    'shift_id' => $shift->id,
                    'tanggal_mulai' => $mulai,
                    'tanggal_selesai' => $selesai,
                ]);
            }
        }

        $admin = User::where('role', 'admin')->first();

        LeaveRequest::create([
            'user_id' => $guru['siti@sekolah.sch.id']->id,
            'jenis' => 'izin',
            'tanggal_mulai' => '2026-10-20',
            'tanggal_selesai' => '2026-10-21',
            'keterangan' => 'Acara keluarga (demo)',
            'status' => 'disetujui',
            'approved_by' => $admin?->id,
            'decided_at' => Carbon::parse('2026-10-19 08:00:00', 'Asia/Jakarta'),
        ]);
        LeaveRequest::create([
            'user_id' => $guru['agus@sekolah.sch.id']->id,
            'jenis' => 'sakit',
            'tanggal_mulai' => '2026-10-08',
            'tanggal_selesai' => null,
            'keterangan' => 'Demam (demo)',
            'status' => 'disetujui',
            'approved_by' => $admin?->id,
            'decided_at' => Carbon::parse('2026-10-08 08:00:00', 'Asia/Jakarta'),
        ]);

        $jadwalMasuk = function (User $u, string $tgl): array {
            $tugas = ShiftAssignment::with('shift')->where('user_id', $u->id)
                ->where('tanggal_mulai', '<=', $tgl)
                ->where(fn ($q) => $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai', '>=', $tgl))
                ->orderByDesc('tanggal_mulai')->first();

            return $tugas?->shift
                ? [substr($tugas->shift->jam_masuk, 0, 5), substr($tugas->shift->jam_pulang, 0, 5)]
                : ['07:00', '14:00'];
        };

        $cursor = Carbon::parse('2026-10-01');
        $akhir = Carbon::parse('2026-10-31');
        while ($cursor->lte($akhir)) {
            $tgl = $cursor->toDateString();

            if (! $cursor->isSunday()) {
                foreach ($guru as $u) {
                    if (LeaveRequest::milikPengguna($u->id)->where('status', 'disetujui')
                        ->where('tanggal_mulai', '<=', $tgl)
                        ->where(fn ($q) => $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai', '>=', $tgl))
                        ->exists()) {
                        continue;
                    }

                    if (mt_rand(1, 100) <= 8) {
                        continue; // alfa
                    }

                    [$masuk, $pulang] = $jadwalMasuk($u, $tgl);
                    $terlambat = mt_rand(1, 100) <= 25;
                    $jamMasuk = $terlambat
                        ? Carbon::parse("{$tgl} {$masuk}")->addMinutes(mt_rand(5, 45))->format('H:i:s')
                        : Carbon::parse("{$tgl} {$masuk}")->subMinutes(mt_rand(5, 30))->format('H:i:s');
                    $sudahPulang = mt_rand(1, 100) <= 90;

                    Absensi::create([
                        'id_user' => $u->id,
                        'tanggal_absen' => $tgl,
                        'jam_masuk' => $jamMasuk,
                        'jam_keluar' => $sudahPulang ? Carbon::parse("{$tgl} {$pulang}")->subMinutes(mt_rand(0, 20))->format('H:i:s') : null,
                        'foto_masuk' => "demo-{$u->id}-{$tgl}-masuk.png",
                        'foto_keluar' => $sudahPulang ? "demo-{$u->id}-{$tgl}-keluar.png" : null,
                        'lokasi_masuk' => '-6.363041,108.113627',
                        'lokasi_keluar' => $sudahPulang ? '-6.363041,108.113627' : null,
                    ]);
                }
            }

            $cursor->addDay();
        }
    }
}
