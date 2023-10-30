<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        return view('home');
    }
    public function absen()
    {
        $harini = date("Y-m-d");
        $id = Auth::user()->id;
        $cek = DB::table('absensi')->where('tanggal_absen', $harini)->where('id_user', $id)->count();
        return view('absen', compact('cek'));
    }
    public function absenMasuk(Request $request)
    {
        $id_user = Auth::user()->id;
        $tanggal_absen = date("Y-m-d");
        $jam = date("H:i:s");
        $lokasi = $request->lokasi;

        $foto = $request->foto;
        $folderPath = "public/absen_file/";
        $cek = DB::table('absensi')->where('tanggal_absen', $tanggal_absen)->where('id_user', $id_user)->count();
        if ($cek) {
            $keterangan = "keluar";
        } else {
            $keterangan = "masuk";
        }
        $formatNama = $id_user . "-" . $tanggal_absen . "-" . $keterangan;
        $foto_parts = explode("base64", $foto);
        $foto_base64 = base64_decode($foto_parts[1]);
        $nama_foto = $formatNama . ".png";
        $file = $folderPath . $nama_foto;
        if ($cek) {
            $data = [
                'jam_keluar' => $jam,
                'foto_keluar' => $nama_foto,
                'lokasi_keluar' =>  $lokasi,
            ];
            $simpan = DB::table('absensi')->update($data);
            if ($simpan) {
                echo 'pulang';
                Storage::put($file, $foto_base64);
            } else {
                echo 'error';
            }
        } else {
            $data = [
                'id_user' => $id_user,
                'tanggal_absen' => $tanggal_absen,
                'jam_masuk' => $jam,
                'foto_masuk' => $nama_foto,
                'lokasi_masuk' =>  $lokasi,
            ];
            $simpan = DB::table('absensi')->insert($data);
            if ($simpan) {
                echo 'masuk';
                Storage::put($file, $foto_base64);
            } else {
                echo 'error';
            }
        }
    }
}
