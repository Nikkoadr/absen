<?php

namespace App\Http\Controllers;

use App\Models\Perangkat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PerangkatController extends Controller
{
    public function index()
    {
        return view('perangkat', [
            'perangkat' => Perangkat::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $kunci = Str::random(40);
        $alat = Perangkat::create([
            'nama' => $data['nama'],
            'key_hash' => hash('sha256', $kunci),
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
        ]);

        return to_route('perangkat.index')->with('success', "Perangkat {$alat->nama} terdaftar. Kunci (sekali tampil): {$kunci}");
    }

    public function regenerasi(Perangkat $perangkat)
    {
        $kunci = Str::random(40);
        $perangkat->update(['key_hash' => hash('sha256', $kunci)]);

        return to_route('perangkat.index')->with('success', "Kunci baru {$perangkat->nama} (sekali tampil): {$kunci}");
    }

    public function toggle(Perangkat $perangkat)
    {
        $perangkat->update(['aktif' => ! $perangkat->aktif]);

        return to_route('perangkat.index')->with('success', $perangkat->aktif ? 'Perangkat diaktifkan.' : 'Perangkat dinonaktifkan.');
    }

    public function destroy(Perangkat $perangkat)
    {
        $perangkat->delete();

        return to_route('perangkat.index')->with('success', 'Perangkat dihapus. Riwayat presensinya tetap tersimpan.');
    }
}
