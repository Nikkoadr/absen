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
        $keterangan = DB::table('absensi')->where('tanggal_absen', $tanggal_absen)->where('id_user', $id_user)->count() ? 'keluar' : 'masuk';
        $foto_parts = explode("base64", $foto);
        $foto_base64 = base64_decode($foto_parts[1]);
        $nama_foto = "$id_user-$tanggal_absen-$keterangan" . '.png';


        $data = [
            'id_user' => $id_user,
            'tanggal_absen' => $tanggal_absen,
            'lokasi_' . $keterangan => $lokasi,
        ];

        if ($keterangan === 'masuk') {
            $data['jam_masuk'] = $jam;
            $data['foto_masuk'] = $nama_foto;
        } else {
            $data['jam_keluar'] = $jam;
            $data['foto_keluar'] = $nama_foto;
        }

        $simpan = DB::table('absensi')->updateOrInsert(
            ['tanggal_absen' => $tanggal_absen, 'id_user' => $id_user],
            $data
        );

        if ($simpan) {
            echo $keterangan === 'masuk' ? 'masuk' : 'pulang';
            Storage::disk('nfs')->put("public/absen_file/$nama_foto", $foto_base64);
        } else {
            echo 'error';
        }
    }
}
