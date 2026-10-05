<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\KompetensiKeahlian;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        return view('kelas', [
            'kelas' => Kelas::with(['kompetensi:id,nama,singkatan', 'waliKelas:id,nama'])
                ->orderBy('tingkat')->orderBy('nama')->get(),
            'kompetensi' => KompetensiKeahlian::orderBy('nama')->get(['id', 'nama', 'singkatan']),
            'guru' => User::whereIn('role', ['guru', 'admin'])->orderBy('nama')->get(['id', 'nama']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kompetensi_id' => ['required', 'integer', 'exists:kompetensi_keahlian,id'],
            'nama' => ['required', 'string', 'max:50'],
            'tingkat' => ['required', 'string', 'in:X,XI,XII,XIII'],
            'jam_masuk' => ['required', 'date_format:H:i,H:i:s'],
            'jam_pulang' => ['nullable', 'date_format:H:i,H:i:s'],
            'wali_kelas_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        Kelas::create($data);

        return to_route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return to_route('kelas.index')->with('success', 'Kelas berhasil dihapus. Siswa di dalamnya menjadi tanpa kelas.');
    }
}
