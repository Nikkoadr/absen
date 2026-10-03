<?php

namespace App\Http\Controllers;

use App\Http\Requests\AbsenMasukRequest;
use App\Http\Requests\FilterTanggalRequest;
use App\Models\Absensi;
use App\Services\AbsensiService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AbsensiController extends Controller
{
    public function __construct(protected AbsensiService $absensi) {}

    public function absen()
    {
        $pengguna = request()->user();
        $hariIni = Carbon::today('Asia/Jakarta')->toDateString();
        $cek = Absensi::milikPengguna($pengguna->id)->padaTanggal($hariIni)->exists();
        $setting = $this->absensi->pengaturan();

        $viewData = [
            'cek' => $cek ? 1 : 0,
            'setting' => $setting,
            'hariIni' => $hariIni,
            'jam' => Carbon::now('Asia/Jakarta')->toTimeString(),
            'limit_absen' => $setting?->limit_absen,
        ];

        return $pengguna->role === 'admin'
            ? view('absen', $viewData)
            : view('absen_mobile', $viewData);
    }

    public function absenMasuk(AbsenMasukRequest $request)
    {
        $hasil = $this->absensi->catat(
            $request->user(),
            $request->string('lokasi')->toString(),
            $request->fotoBiner()
        );

        return response()->json([
            'status' => $hasil['status'],
            'message' => $hasil['message'],
        ], $hasil['http']);
    }

    public function attendance(FilterTanggalRequest $request)
    {
        $attendance = Absensi::with('user:id,nama')
            ->whereDay('tanggal_absen', $request->hari())
            ->whereMonth('tanggal_absen', $request->bulan())
            ->whereYear('tanggal_absen', $request->tahun())
            ->orderBy('jam_masuk')
            ->get();

        return view('attendance', [
            'attendance' => $attendance,
            'hari' => $request->hari(),
            'bulan' => $request->bulan(),
            'tahun' => $request->tahun(),
        ]);
    }

    public function edit_absen(Absensi $absensi)
    {
        $absensi->load('user:id,nama');

        return view('layouts.component.edit_absen', ['data' => $absensi]);
    }

    public function update_absen(Request $request, Absensi $absensi)
    {
        $data = $request->validate([
            'tanggal_absen' => ['required', 'date'],
            'jam_masuk' => ['required', 'date_format:H:i,H:i:s'],
            'jam_keluar' => ['nullable', 'date_format:H:i,H:i:s'],
        ]);
        $absensi->update($data);

        return to_route('attendance')->with('success', 'Data berhasil diperbarui.');
    }

    public function hapus_absen(Absensi $absensi)
    {
        $absensi->delete();

        return to_route('attendance')->with('success', 'Data berhasil dihapus.');
    }
}
