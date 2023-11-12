<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
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
        if (Auth::user()->role == 'admin') {
            return view('profile');
        } else {
            return view('profile_mobile');
        }
    }

    public function edit_user($id, Request $request)
    {
        $data_valid = $request->validate([
            'nik'       => ['nullable', 'max:16'],
            'nuptk'     => ['nullable', 'max:16'],
            'nbm'       => ['nullable', 'max:20'],
            'nama'      => ['required', 'string', 'max:255'],
            'email' => 'required|unique:users,email,' . $request->id,
        ]);
        $user = User::find($id);
        $user->update($data_valid);
        return redirect('profile')->with('success', 'Data Berhasil di Update');
    }

    public function edit_password_user_id($id, Request $request)
    {
        $data_valid = $request->validate([
            'password'      => ['required', 'confirmed'],
        ]);

        $hash = Hash::make($data_valid['password']);
        $user = User::find($id);
        $user->update(['password' => $hash]);
        return redirect('profile')->with('success', 'Password Berhasil diganti');
    }
    public function upload_pasfoto_id($id, Request $request)
    {
        $request->validate([
            'pas_foto' => 'required|image|mimes:jpeg,png,jpg,gif|file|max:5120',
        ]);
        $namaFoto = 'pasfoto' . '_' . Auth::user()->id . '_' . Auth::user()->nama . '.' . $request->pas_foto->getClientOriginalExtension();
        Storage::disk(env('STORAGE_DISK'))->put('pasFotoAbsen/' . $namaFoto, file_get_contents($request->pas_foto));
        $user = User::find($id);
        $user->update(['pasfoto' => $namaFoto]);
        return redirect('profile')->with('success', 'Pas Foto Berhasil Diupload');
    }
    public function history(Request $request)
    {
        $userAktif = Auth::user()->id;
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $history = DB::table('absensi')
            ->where('id_user', $userAktif)
            ->whereRaw('MONTH(tanggal_absen)="' . $bulan . '"')
            ->whereRaw('YEAR(tanggal_absen)="' . $tahun . '"')
            ->orderBy('tanggal_absen')
            ->get();
        return view('history_mobile', compact('history', 'bulan', 'tahun'));
    }
}
