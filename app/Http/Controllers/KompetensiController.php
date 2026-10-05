<?php

namespace App\Http\Controllers;

use App\Models\KompetensiKeahlian;
use Illuminate\Http\Request;

class KompetensiController extends Controller
{
    public function index()
    {
        return view('kompetensi', [
            'kompetensi' => KompetensiKeahlian::withCount('kelas')->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:kompetensi_keahlian,nama'],
            'singkatan' => ['required', 'string', 'max:10', 'unique:kompetensi_keahlian,singkatan'],
        ]);

        KompetensiKeahlian::create($data);

        return to_route('kompetensi.index')->with('success', 'Kompetensi keahlian berhasil ditambahkan.');
    }

    public function destroy(KompetensiKeahlian $kompetensi)
    {
        $kompetensi->delete();

        return to_route('kompetensi.index')->with('success', 'Kompetensi beserta kelasnya berhasil dihapus.');
    }
}
