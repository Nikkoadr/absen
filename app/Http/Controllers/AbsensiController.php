<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
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
            $limit_absen = $setting->limit_absen;
            $jam = date("H:i:s");
            return view('absen', compact('cek', 'setting', 'hariIni', 'jam', 'limit_absen'));
        } else {
            $hariIni = date("Y-m-d");
            $id = Auth::user()->id;
            $cek = DB::table('absensi')->where('tanggal_absen', $hariIni)->where('id_user', $id)->count();
            $setting = Setting::first();
            $limit_absen = $setting->limit_absen;
            $jam = date("H:i:s");
            return view('absen_mobile', compact('cek', 'setting', 'hariIni', 'jam', 'limit_absen'));
        }
    }

    public function absenMasuk(Request $request)
    {
        $user = Auth::user();
        $tanggal_absen = date("Y-m-d");
        $jam = date("H:i:s");
        $setting = Setting::first();

        // Lokasi dan Jarak
        [$latitudeUser, $longitudeUser] = explode(",", $request->lokasi);
        $radius = round($this->distance(
            $setting->latitude,
            $setting->longitude,
            $latitudeUser,
            $longitudeUser
        )["meters"]);

        // Cek absen hari ini
        $absensiHariIni = DB::table('absensi')
            ->where('tanggal_absen', $tanggal_absen)
            ->where('id_user', $user->id)
            ->first();

        if ($radius > $setting->radius) {
            echo "error|Maaf, Jarak Anda $radius M dari {$setting->namaLokasi}";
            return;
        }

        if ($absensiHariIni) {
            if ($absensiHariIni->jam_keluar) {
                echo 'error|Anda sudah presensi pulang hari ini.';
                return;
            }

            $diff = strtotime($jam) - strtotime($absensiHariIni->jam_masuk);
            if ($diff < 150) { // 5 menit = 300 detik
                echo 'error|Anda tidak bisa presensi keluar terlalu cepat setelah presensi masuk !. tunggu 5 menit.';
                return;
            }

            // Absen keluar
            $nama_foto = "{$user->id}-$tanggal_absen-keluar.png";
            $foto_base64 = base64_decode(explode("base64", $request->foto)[1]);
            $data = [
                'jam_keluar' => $jam,
                'foto_keluar' => $nama_foto,
                'lokasi_keluar' =>  $request->lokasi,
            ];
            $simpan = DB::table('absensi')->where('id', $absensiHariIni->id)->update($data);

            if ($simpan) {
                echo 'sukses|Anda Sudah Absen Pulang. Hati - hati Dijalan !';
                Storage::disk(env('STORAGE_DISK'))->put($nama_foto, $foto_base64);
            } else {
                echo 'error|Maaf, Masih Dalam Proses Pengembangan Oleh ICT SMK';
            }
        } else {
            // Absen masuk
            $nama_foto = "{$user->id}-$tanggal_absen-masuk.png";
            $foto_base64 = base64_decode(explode("base64", $request->foto)[1]);
            $data = [
                'id_user' => $user->id,
                'tanggal_absen' => $tanggal_absen,
                'jam_masuk' => $jam,
                'foto_masuk' => $nama_foto,
                'lokasi_masuk' =>  $request->lokasi,
            ];
            $simpan = DB::table('absensi')->insert($data);

            if ($simpan) {
                echo 'sukses|Terima kasih anda sudah melakukan presensi masuk hari ini.';
                Storage::disk(env('STORAGE_DISK'))->put($nama_foto, $foto_base64);
            } else {
                echo 'error|Maaf, Masih Dalam Proses Pengembangan Oleh ICT SMK';
            }
        }
    }


    // Fungsi kirim pesan jika diperlukan
    // private function kirimPesan($nomor_hp, $jam, $keterangan, $nama)
    // {
    //     if ($nomor_hp) {
    //         Http::withOptions(['verify' => false])->post(
    //             '10.20.30.9:3000/send-message',
    //             [
    //                 'number' => $nomor_hp,
    //                 'message' => "Terima kasih $nama, Anda Sudah absen $keterangan di jam $jam Wib.",
    //             ]
    //         );
    //     }
    // }


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

    public function attendance(Request $request)
    {
        $hari = $request->input('hari', now()->day);
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $attendance = DB::table('absensi')
            ->join('users', 'absensi.id_user', '=', 'users.id')
            ->whereDay('tanggal_absen', $hari)
            ->whereMonth('tanggal_absen', $bulan)
            ->whereYear('tanggal_absen', $tahun)
            ->select('absensi.*', 'users.nama as nama')
            ->orderBy('jam_masuk')
            ->get();

        return view('attendance', compact('attendance', 'hari', 'bulan', 'tahun'));
    }


    public function edit_absen($id, Request $request)
    {
        $data = Absensi::with('user:id,nama')->find($id);

        return view('layouts.component.edit_absen', compact('data'));
    }

    public function update_absen($id, Request $request)
    {
        $data_valid = $request->validate([
            'tanggal_absen' => ['required', 'date'],
            'jam_masuk'     => ['required',],
            'jam_keluar'    => ['nullable']
        ]);
        $user = Absensi::find($id);
        $user->timestamps = false;
        $user->update($data_valid);
        return redirect('attendance')->with('success', 'Data Berhasil di Update');
    }

    public function hapus_absen($id)
    {
        $data = Absensi::find($id);
        $data->delete();
        return redirect('/attendance')->with('success', 'Data berhasil dihapus');
    }
}
