<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    public function absen()
    {
        $hariIni = Carbon::today('Asia/Jakarta')->toDateString();
        $id = Auth::id();
        $cek = DB::table('absensi')
            ->where('tanggal_absen', $hariIni)
            ->where('id_user', $id)
            ->count();
        $setting = Setting::first();
        $limit_absen = $setting?->limit_absen;
        $jam = Carbon::now('Asia/Jakarta')->toTimeString();

        $viewData = compact('cek', 'setting', 'hariIni', 'jam', 'limit_absen');

        if (Auth::user()->role === 'admin') {
            return view('absen', $viewData);
        }

        return view('absen_mobile', $viewData);
    }

    public function absenMasuk(Request $request)
    {
        $request->validate([
            'lokasi' => ['required', 'string', 'regex:/^-?\d+(\.\d+)?,-?\d+(\.\d+)?$/'],
            'foto' => ['required', 'string', 'min:100'],
        ]);

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $tanggal_absen = $now->toDateString();
        $jam = $now->toTimeString();
        $setting = Setting::first();

        if (! $setting) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengaturan lokasi belum dikonfigurasi. Hubungi admin.',
            ], 500);
        }

        [$latitudeUser, $longitudeUser] = array_map('floatval', explode(',', $request->lokasi));

        $radius = (int) round($this->distance(
            (float) $setting->latitude,
            (float) $setting->longitude,
            $latitudeUser,
            $longitudeUser
        )['meters']);

        if ($radius > (int) $setting->radius) {
            return response()->json([
                'status' => 'error',
                'message' => "Maaf, Jarak Anda {$radius} M dari {$setting->namaLokasi}",
            ], 422);
        }

        $parts = explode('base64,', $request->foto);
        if (count($parts) !== 2 || empty($parts[1])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Format foto tidak valid.',
            ], 422);
        }

        $foto_base64 = base64_decode($parts[1], true);
        if ($foto_base64 === false) {
            return response()->json([
                'status' => 'error',
                'message' => 'Foto tidak dapat diproses.',
            ], 422);
        }

        $absensiHariIni = DB::table('absensi')
            ->where('tanggal_absen', $tanggal_absen)
            ->where('id_user', $user->id)
            ->first();

        $disk = config('filesystems.default');

        if ($absensiHariIni) {
            if ($absensiHariIni->jam_keluar) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah presensi pulang hari ini.',
                ], 422);
            }

            $diff = $now->diffInSeconds(Carbon::parse("{$tanggal_absen} {$absensiHariIni->jam_masuk}", 'Asia/Jakarta'), false);
            // $diff negatif jika jam_masuk di masa lalu; ambil absolut
            $selisih = abs($diff);
            if ($selisih < 300) { // 5 menit = 300 detik
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak bisa presensi keluar terlalu cepat setelah presensi masuk! Tunggu 5 menit.',
                ], 422);
            }

            $nama_foto = "{$user->id}-{$tanggal_absen}-keluar.png";
            $updated = DB::table('absensi')->where('id', $absensiHariIni->id)->update([
                'jam_keluar' => $jam,
                'foto_keluar' => $nama_foto,
                'lokasi_keluar' => $request->lokasi,
            ]);

            if ($updated) {
                Storage::disk($disk)->put($nama_foto, $foto_base64);

                return response()->json([
                    'status' => 'sukses',
                    'message' => 'Anda Sudah Absen Pulang. Hati-hati di jalan!',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan absen pulang. Coba lagi.',
            ], 500);
        }

        $nama_foto = "{$user->id}-{$tanggal_absen}-masuk.png";
        $inserted = DB::table('absensi')->insert([
            'id_user' => $user->id,
            'tanggal_absen' => $tanggal_absen,
            'jam_masuk' => $jam,
            'foto_masuk' => $nama_foto,
            'lokasi_masuk' => $request->lokasi,
        ]);

        if ($inserted) {
            Storage::disk($disk)->put($nama_foto, $foto_base64);

            return response()->json([
                'status' => 'sukses',
                'message' => 'Terima kasih, Anda sudah melakukan presensi masuk hari ini.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal menyimpan absen masuk. Coba lagi.',
        ], 500);
    }

    public function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2)))
            + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        // Clamp untuk hindari NaN akibat floating point (acos domain [-1,1])
        $miles = max(-1, min(1, $miles));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;

        return compact('meters');
    }

    public function attendance(Request $request)
    {
        Gate::authorize('is_admin');

        $data = $request->validate([
            'hari' => ['nullable', 'integer', 'min:1', 'max:31'],
            'bulan' => ['nullable', 'integer', 'min:1', 'max:12'],
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $hari = $data['hari'] ?? now('Asia/Jakarta')->day;
        $bulan = $data['bulan'] ?? now('Asia/Jakarta')->month;
        $tahun = $data['tahun'] ?? now('Asia/Jakarta')->year;

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

    public function edit_absen($id)
    {
        Gate::authorize('is_admin');

        $data = Absensi::with('user:id,nama')->findOrFail($id);

        return view('layouts.component.edit_absen', compact('data'));
    }

    public function update_absen($id, Request $request)
    {
        Gate::authorize('is_admin');

        $data_valid = $request->validate([
            'tanggal_absen' => ['required', 'date'],
            'jam_masuk' => ['required'],
            'jam_keluar' => ['nullable'],
        ]);
        $absensi = Absensi::findOrFail($id);
        $absensi->update($data_valid);

        return redirect('attendance')->with('success', 'Data Berhasil di Update');
    }

    public function hapus_absen($id)
    {
        Gate::authorize('is_admin');

        $data = Absensi::findOrFail($id);
        $data->delete();

        return redirect('/attendance')->with('success', 'Data berhasil dihapus');
    }
}
