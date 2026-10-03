<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $libur = Holiday::orderBy('tanggal')->paginate(20);

        return view('libur', compact('libur'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date', 'unique:holidays,tanggal'],
            'nama' => ['required', 'string', 'max:150'],
            'berulang_tiap_tahun' => ['nullable', 'boolean'],
        ]);

        Holiday::create([
            'tanggal' => $data['tanggal'],
            'nama' => $data['nama'],
            'berulang_tiap_tahun' => (bool) ($data['berulang_tiap_tahun'] ?? false),
        ]);

        return to_route('libur.index')->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function destroy(Holiday $libur)
    {
        $libur->delete();

        return to_route('libur.index')->with('success', 'Hari libur berhasil dihapus.');
    }
}
