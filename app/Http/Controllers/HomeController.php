<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
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
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $hariIni = date("Y-m-d");
            $userAktif = Auth::user()->id;
            $absenHariIni = DB::table('absensi')->where('id_user', $userAktif)->where('tanggal_absen', $hariIni);
            return view('home', compact('absenHariIni'));
        } else {
            $hariIni = date("Y-m-d");
            $userAktif = Auth::user()->id;
            $absenHariIni = DB::table('absensi')->where('id_user', $userAktif)->where('tanggal_absen', $hariIni)->first();
            return view('home_mobile', compact('absenHariIni'));
        }
    }
}
