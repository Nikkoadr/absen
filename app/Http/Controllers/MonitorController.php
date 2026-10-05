<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Support\Carbon;

class MonitorController extends Controller
{
    public function layar()
    {
        return view('monitor');
    }

    public function terakhir()
    {
        $hariIni = Carbon::today('Asia/Jakarta')->toDateString();
        $absen = Absensi::with(['user:id,nama,pasfoto', 'user.siswa.kelas.kompetensi', 'gerbang:id,nama'])
            ->whereHas('user', fn ($q) => $q->where('role', 'siswa'))
            ->padaTanggal($hariIni)
            ->orderByDesc('updated_at')
            ->first();

        if (! $absen || ! $absen->user) {
            return response()->json(['ada' => false]);
        }

        $kelas = $absen->user->siswa?->kelas;

        $terbaru = Absensi::with('user:id,nama')
            ->whereHas('user', fn ($q) => $q->where('role', 'siswa'))
            ->padaTanggal($hariIni)
            ->orderByDesc('updated_at')
            ->take(5)
            ->get()
            ->map(fn ($a) => [
                'nama' => $a->user->nama ?? '-',
                'jam' => $a->jam_masuk ? substr($a->jam_masuk, 0, 5) : '-',
            ]);

        return response()->json([
            'ada' => true,
            'nama' => $absen->user->nama,
            'kelas' => $kelas ? "{$kelas->tingkat} {$kelas->nama} ({$kelas->kompetensi->singkatan})" : 'Tanpa kelas',
            'gerbang' => $absen->gerbang?->nama,
            'foto' => $absen->user->pasfoto ? asset('storage/absen_file/pasFotoAbsen/'.$absen->user->pasfoto) : null,
            'inisial' => strtoupper(mb_substr(trim($absen->user->nama ?? '?'), 0, 1)),
            'jam_masuk' => $absen->jam_masuk ? substr($absen->jam_masuk, 0, 5) : '-',
            'jam_keluar' => $absen->jam_keluar ? substr($absen->jam_keluar, 0, 5) : null,
            'diperbarui' => $absen->updated_at->toIso8601String(),
            'terbaru' => $terbaru,
            'jumlah' => Absensi::whereHas('user', fn ($q) => $q->where('role', 'siswa'))->padaTanggal($hariIni)->count(),
        ]);
    }
}
