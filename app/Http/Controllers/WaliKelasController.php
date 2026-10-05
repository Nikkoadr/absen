<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Support\Carbon;

class WaliKelasController extends Controller
{
    public function index()
    {
        $pengguna = request()->user();
        $hariIni = Carbon::today('Asia/Jakarta')->toDateString();

        $kelas = Kelas::with(['kompetensi:id,singkatan', 'siswa.user:id,nama'])
            ->where('wali_kelas_user_id', $pengguna->id)
            ->orderBy('tingkat')->orderBy('nama')
            ->get();

        $kehadiran = Absensi::padaTanggal($hariIni)
            ->whereIn('id_user', $kelas->flatMap(fn ($k) => $k->siswa->pluck('user_id'))->all())
            ->get()
            ->keyBy('id_user');

        return view('wali', compact('kelas', 'kehadiran', 'hariIni'));
    }
}
