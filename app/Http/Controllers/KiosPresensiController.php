<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Services\AbsensiService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class KiosPresensiController extends Controller
{
    public function __construct(protected AbsensiService $absensi) {}

    public function kios()
    {
        $setting = Setting::first();
        $hariIni = Carbon::today('Asia/Jakarta')->toDateString();
        $jam = Carbon::now('Asia/Jakarta')->toTimeString();

        return view('kios', compact('setting', 'hariIni', 'jam'));
    }

    public function deskriptor()
    {
        $data = User::whereNotNull('face_descriptor')
            ->select('id', 'nama', 'pasfoto', 'face_descriptor')
            ->get()
            ->map(fn ($u) => [
                'id' => $u->id,
                'nama' => $u->nama,
                'foto' => $u->pasfoto ? asset('storage/absen_file/pasFotoAbsen/'.$u->pasfoto) : null,
                'descriptor' => $u->face_descriptor,
            ]);

        return response()->json($data);
    }

    public function simpan(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'lokasi' => ['required', 'string', 'regex:/^-?\d+(\.\d+)?,-?\d+(\.\d+)?$/'],
            'foto' => ['required', 'string', 'min:100'],
            'skor' => ['required', 'numeric', 'min:0', 'max:0.6'],
        ]);

        $parts = explode('base64,', $data['foto']);
        if (count($parts) !== 2 || $parts[1] === '') {
            return response()->json(['status' => 'error', 'message' => 'Format foto tidak valid.'], 422);
        }

        $biner = base64_decode($parts[1], true);
        if ($biner === false) {
            return response()->json(['status' => 'error', 'message' => 'Foto tidak dapat diproses.'], 422);
        }

        $pengguna = User::findOrFail($data['user_id']);
        $hasil = $this->absensi->catat($pengguna, $data['lokasi'], $biner);

        return response()->json(['status' => $hasil['status'], 'message' => $hasil['message']], $hasil['http']);
    }

    public function simpanDeskriptor(Request $request, User $user)
    {
        $peminta = $request->user();

        if ($peminta->id !== $user->id && ! $peminta->can('is_admin')) {
            abort(403);
        }

        $data = $request->validate([
            'descriptor' => ['required', 'array', 'size:128'],
            'descriptor.*' => ['required', 'numeric'],
        ]);

        $user->update(['face_descriptor' => array_values($data['descriptor'])]);

        return response()->json(['status' => 'sukses', 'message' => 'Data wajah berhasil disimpan.']);
    }
}
