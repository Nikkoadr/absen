<?php

namespace App\Http\Controllers;

use App\Services\AnalitikService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AnalitikController extends Controller
{
    public function __construct(protected AnalitikService $analitik) {}

    public function index(Request $request)
    {
        $data = $request->validate([
            'tanggal_awal' => ['nullable', 'date'],
            'tanggal_akhir' => ['nullable', 'date', 'after_or_equal:tanggal_awal'],
        ]);

        $akhir = isset($data['tanggal_akhir']) ? Carbon::parse($data['tanggal_akhir']) : Carbon::today('Asia/Jakarta');
        $awal = isset($data['tanggal_awal']) ? Carbon::parse($data['tanggal_awal']) : $akhir->copy()->subDays(29);

        if ($awal->diffInDays($akhir) > 93) {
            return back()->withErrors(['tanggal_awal' => 'Rentang maksimal 93 hari.'])->withInput();
        }

        $awalStr = $awal->toDateString();
        $akhirStr = $akhir->toDateString();

        return view('analitik', [
            'tanggal_awal' => $awalStr,
            'tanggal_akhir' => $akhirStr,
            'tren' => $this->analitik->tren($awalStr, $akhirStr),
            'perKelas' => $this->analitik->perKelas($awalStr, $akhirStr),
            'topTerlambat' => $this->analitik->topTerlambat($awalStr, $akhirStr),
        ]);
    }
}
