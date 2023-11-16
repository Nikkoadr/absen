<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function absen()
    {
        if (Auth::user()->role === 'admin') {
            $hariIni = date("Y-m-d");
            $id = Auth::user()->id;
            $cek = DB::table('absensi')->where('tanggal_absen', $hariIni)->where('id_user', $id)->count();
            $setting = Setting::first();
            return view('absen', compact('cek', 'setting', 'hariIni'));
        } else {
            $hariIni = date("Y-m-d");
            $id = Auth::user()->id;
            $cek = DB::table('absensi')->where('tanggal_absen', $hariIni)->where('id_user', $id)->count();
            $setting = Setting::first();
            return view('absen_mobile', compact('cek', 'setting', 'hariIni'));
        }
    }

    public function absenMasuk(Request $request)
    {
        $id_user = Auth::user()->id;
        $tanggal_absen = date("Y-m-d");
        $jam = date("H:i:s");
        $setting = Setting::first();
        $latitudeKantor = $setting->latitude;
        $longitudeKantor = $setting->longitude;
        $lokasi = $request->lokasi;
        $lokasiUser = explode(",", $lokasi);
        $latitudeUser = $lokasiUser[0];
        $longitudeUser = $lokasiUser[1];
        $jarak = $this->distance($latitudeKantor, $longitudeKantor, $latitudeUser, $longitudeUser);
        $radius = round($jarak["meters"]);
        $foto = $request->foto;

        $cek = DB::table('absensi')->where('tanggal_absen', $tanggal_absen)->where('id_user', $id_user)->count();
        if ($radius > $setting->radius) {
            echo "error|Maaf, Jarak Anda " . $radius . " M dari " . $setting->namaLokasi;
        } else {
            if ($cek) {
                $keterangan = "keluar";
            } else {
                $keterangan = "masuk";
            }
            $nama_foto = $id_user . "-" . $tanggal_absen . "-" . $keterangan . ".png";
            $foto_parts = explode("base64", $foto);
            $foto_base64 = base64_decode($foto_parts[1]);
            if ($cek > 0) {
                $data = [
                    'jam_keluar' => $jam,
                    'foto_keluar' => $nama_foto,
                    'lokasi_keluar' =>  $lokasi,
                ];
                $simpan = DB::table('absensi')->where('tanggal_absen', $tanggal_absen)->where('id_user', $id_user)->update($data);
                if ($simpan) {
                    echo 'sukses|Anda Sudah Absen Pulang. Hati - hati Dijalan !|';
                    Storage::disk(env('STORAGE_DISK'))->put($nama_foto, $foto_base64);
                } else {
                    echo 'error|maaf masih dalam proses pengembangan hehehe';
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
                    echo 'sukses|Terimakasih anda sudah melakukan absen masuk';
                    Storage::disk(env('STORAGE_DISK'))->put($nama_foto, $foto_base64);
                } else {
                    echo 'error|maaf masih dalam proses pengembangan hehehe';
                }
            }
        }
    }

    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }
}
