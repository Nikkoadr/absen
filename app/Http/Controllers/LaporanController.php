<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class LaporanController extends Controller
{
    public function printLaporanIndividu(Request $request, $id = null)
    {
        Gate::authorize('is_admin');

        $data = $request->validate([
            'id' => ['nullable', 'integer', 'exists:users,id'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        $userId = $id ?? $data['id'] ?? null;
        abort_if(! $userId, 422, 'ID pengguna wajib diisi.');

        $userId = $id ?? $data['id'] ?? null;
        abort_if(! $userId, 422, 'ID pengguna wajib diisi.');

        $user = DB::table('users')->where('id', $userId)->first();
        abort_if(! $user, 404, 'Pengguna tidak ditemukan.');
        $rekap = DB::table('absensi')
            ->join('users', 'absensi.id_user', '=', 'users.id')
            ->where('id_user', $user->id)
            ->whereMonth('tanggal_absen', $data['bulan'])
            ->whereYear('tanggal_absen', $data['tahun'])
            ->orderBy('tanggal_absen')
            ->select('absensi.*', 'users.nama')
            ->get();

        return view('layouts.component.printLaporanIndividu', [
            'user' => $user,
            'bulan' => $data['bulan'],
            'tahun' => $data['tahun'],
            'rekap' => $rekap,
        ]);
    }

    public function laporanSemua(Request $request)
    {
        Gate::authorize('is_admin');

        $data = $request->validate([
            'bulan' => ['nullable', 'integer', 'min:1', 'max:12'],
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $bulan = $data['bulan'] ?? now('Asia/Jakarta')->month;
        $tahun = $data['tahun'] ?? now('Asia/Jakarta')->year;

        return view('laporanSemua', compact('bulan', 'tahun'));
    }

    protected function buildRekap(string $tanggalAwal, string $tanggalAkhir)
    {
        $selectStatements = [];
        $tanggalMulai = Carbon::parse($tanggalAwal, 'Asia/Jakarta');
        $tanggalSelesai = Carbon::parse($tanggalAkhir, 'Asia/Jakarta');

        $cursor = $tanggalMulai->copy();
        while ($cursor->lte($tanggalSelesai)) {
            $hari = (int) $cursor->day;
            $selectStatements[] = "MAX(CASE WHEN DAY(tanggal_absen) = {$hari} THEN CONCAT(jam_masuk, '-', IFNULL(jam_keluar, '00:00:00')) ELSE '' END) as tgl_{$hari}";
            $cursor->addDay();
        }

        $rekap = DB::table('users')
            ->selectRaw('users.id as id_user, users.jam_kerja, users.nama, users.jabatan, '.implode(', ', $selectStatements))
            ->leftJoin('absensi', function ($join) use ($tanggalAwal, $tanggalAkhir) {
                $join->on('users.id', '=', 'absensi.id_user')
                    ->whereBetween('tanggal_absen', [$tanggalAwal, $tanggalAkhir]);
            })
            ->groupByRaw('users.id, users.jam_kerja, users.nama, users.jabatan')
            ->orderBy('users.nama')
            ->get();

        foreach ($rekap as $row) {
            $totalJamTerlambat = 0;
            for ($i = 1; $i <= 31; $i++) {
                $key = "tgl_{$i}";
                if (isset($row->$key) && $row->$key !== '') {
                    $jamMasuk = substr($row->$key, 0, 5);
                    $jamKerja = $row->jam_kerja ? substr((string) $row->jam_kerja, 0, 5) : null;

                    if ($jamKerja && $jamMasuk > $jamKerja) {
                        $terlambat = Carbon::parse($jamMasuk, 'Asia/Jakarta')
                            ->diffInMinutes(Carbon::parse($jamKerja, 'Asia/Jakarta'));
                        $totalJamTerlambat += $terlambat / 60;
                    }
                }
            }
            $row->total_jam_terlambat = $totalJamTerlambat;
        }

        return $rekap;
    }

    protected function validateRange(Request $request): array
    {
        $data = $request->validate([
            'tanggal_awal' => ['required', 'date'],
            'tanggal_akhir' => ['required', 'date', 'after_or_equal:tanggal_awal'],
        ]);

        // Batasi maksimal 62 hari agar query pivot tidak meledak
        $awal = Carbon::parse($data['tanggal_awal']);
        $akhir = Carbon::parse($data['tanggal_akhir']);
        if ($awal->diffInDays($akhir) > 62) {
            abort(422, 'Rentang tanggal maksimal 62 hari.');
        }

        return $data;
    }

    public function printSemuaLaporan(Request $request)
    {
        Gate::authorize('is_admin');

        $data = $this->validateRange($request);
        $rekap = $this->buildRekap($data['tanggal_awal'], $data['tanggal_akhir']);

        return view('layouts.component.printLaporanSemua', [
            'tanggalAwal' => $data['tanggal_awal'],
            'tanggalAkhir' => $data['tanggal_akhir'],
            'rekap' => $rekap,
        ]);
    }

    public function downloadLaporanBulanan(Request $request)
    {
        Gate::authorize('is_admin');

        $data = $this->validateRange($request);
        $rekap = $this->buildRekap($data['tanggal_awal'], $data['tanggal_akhir']);

        return view('layouts.component.downloadSemuaLaporan', [
            'tanggalAwal' => $data['tanggal_awal'],
            'tanggalAkhir' => $data['tanggal_akhir'],
            'rekap' => $rekap,
        ]);
    }
}
