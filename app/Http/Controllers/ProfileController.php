<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $hariIni = date('Y-m-d');
            $userAktif = Auth::id();
            $bulanIni = date('m');
            $tahunIni = date('Y');
            $set_jam_kerja = Auth::user()->jam_kerja;
            $historyBulanIni = DB::table('absensi')
                ->where('id_user', $userAktif)
                ->whereMonth('tanggal_absen', $bulanIni)
                ->whereYear('tanggal_absen', $tahunIni)
                ->orderBy('tanggal_absen')
                ->get();
            $absenHariIni = DB::table('absensi')
                ->where('id_user', $userAktif)
                ->where('tanggal_absen', $hariIni)->first();

            return view('profile', compact('historyBulanIni', 'absenHariIni', 'set_jam_kerja'));
        }

        return view('profile_mobile');
    }

    protected function ensureCanEdit($id): void
    {
        if (Auth::id() != (int) $id && ! Gate::allows('is_admin')) {
            abort(403, 'Tidak diizinkan mengubah data pengguna lain.');
        }
    }

    public function edit_user($id, Request $request)
    {
        $this->ensureCanEdit($id);

        $data_valid = $request->validate([
            'nik' => ['nullable', 'string', 'max:16'],
            'nuptk' => ['nullable', 'string', 'max:16'],
            'nbm' => ['nullable', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:255'],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'email' => 'required|email|max:255|unique:users,email,'.$id,
        ]);
        $user = User::findOrFail($id);
        $user->update($data_valid);

        return redirect('profile')->with('success', 'Data Berhasil di Update');
    }

    public function edit_password_user_id($id, Request $request)
    {
        $this->ensureCanEdit($id);

        $data_valid = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::findOrFail($id);
        $user->update(['password' => $data_valid['password']]);

        return redirect('profile')->with('success', 'Password Berhasil diganti');
    }

    public function upload_pasfoto_id($id, Request $request)
    {
        $this->ensureCanEdit($id);

        $request->validate([
            'pas_foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
        $namaFoto = Str::random(10).'.'.$request->file('pas_foto')->getClientOriginalExtension();
        Storage::disk(config('filesystems.default'))->put(
            'pasFotoAbsen/'.$namaFoto,
            file_get_contents($request->file('pas_foto')->getRealPath())
        );
        $user = User::findOrFail($id);
        $user->update(['pasfoto' => $namaFoto]);

        return redirect('profile')->with('success', 'Pas Foto Berhasil Diupload');
    }

    public function history(Request $request)
    {
        $data = $request->validate([
            'bulan' => ['nullable', 'integer', 'min:1', 'max:12'],
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $userAktif = Auth::id();
        $bulan = $data['bulan'] ?? now('Asia/Jakarta')->month;
        $tahun = $data['tahun'] ?? now('Asia/Jakarta')->year;
        $set_jam_kerja = Auth::user()->jam_kerja;
        $history = DB::table('absensi')
            ->where('id_user', $userAktif)
            ->whereMonth('tanggal_absen', $bulan)
            ->whereYear('tanggal_absen', $tahun)
            ->orderBy('tanggal_absen')
            ->get();

        return view('history_mobile', compact('history', 'bulan', 'tahun', 'set_jam_kerja'));
    }
}
