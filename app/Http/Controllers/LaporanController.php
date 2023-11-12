<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function printLaporanIndividu(Request $request)
    {
        $id = $request->id;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $user = DB::table('users')
            ->where('id', $id)
            ->first();
        $rekap = DB::table('absensi')
            ->where('tanggal_abasen', $bulan)
            ->where('id_user', $id)
            ->get();
        return view('layouts.component.cetakLaporanIndividu', compact('user', 'bulan', 'tahun'));
    }
    public function laporanSemua()
    {
        return view('laporanSemua');
    }
}
