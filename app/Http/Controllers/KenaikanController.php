<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class KenaikanController extends Controller
{
    public function index()
    {
        return view('kenaikan', [
            'kelas' => Kelas::withCount('siswa')->with('kompetensi:id,singkatan')
                ->orderBy('tingkat')->orderBy('nama')->get(),
            'tujuan' => Kelas::with('kompetensi:id,singkatan')->orderBy('tingkat')->orderBy('nama')->get(),
        ]);
    }

    public function proses(Request $request)
    {
        $data = $request->validate([
            'peta' => ['required', 'array'],
            'peta.*' => ['nullable', 'string'],
        ]);

        $pindah = 0;
        $lulus = 0;

        foreach ($data['peta'] as $asalId => $tujuan) {
            if (! $tujuan) {
                continue;
            }

            if ($tujuan === 'lulus') {
                $ids = Siswa::where('kelas_id', $asalId)->pluck('user_id')->all();
                Siswa::where('kelas_id', $asalId)->update(['kelas_id' => null]);
                \App\Models\User::whereIn('id', $ids)->update(['aktif' => false]);
                $lulus += count($ids);

                continue;
            }

            if ((int) $tujuan === (int) $asalId || ! Kelas::whereKey($tujuan)->exists()) {
                continue;
            }

            $pindah += Siswa::where('kelas_id', $asalId)->update(['kelas_id' => $tujuan]);
        }

        return to_route('kenaikan.index')->with('success', "Kenaikan selesai: {$pindah} siswa pindah kelas, {$lulus} siswa lulus.");
    }
}
