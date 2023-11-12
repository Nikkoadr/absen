<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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

    public function index(Request $request)
    {
        $this->authorize('is_admin');
        $data_user = User::all();
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        return view('data_user', compact('data_user', 'bulan', 'tahun'));
    }

    public function importUser()
    {
        Excel::import(new UsersImport, request()->file('import'));
        return back()->with(['success' => 'Data Berhasil Diimport!']);
    }

    public function exportUser()
    {
        return Excel::download(new UsersExport, 'data_users.xlsx');
    }

    public function tambah_user(Request $request)
    {
        $this->validate($request, [
            'role' => ['required', 'string'],
            'nik' => ['nullable', 'max:13'],
            'nuptk' => ['nullable', 'max:16'],
            'nbm' => ['nullable', 'max:20'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'jabatan' => ['nullable',],
            'jam_kerja' => ['nullable',],
            'pas_foto' => ['nullable',],
        ]);
        User::create([
            'role'   => $request->role,
            'nik'     => $request->nik,
            'nuptk'     => $request->nuptk,
            'nbm'     => $request->nbm,
            'nama'     => $request->nama,
            'email'   => $request->email,
            'password'   => Hash::make($request->password),
            'jabatan'   => $request->jabatan,
            'jam_kerja'   => $request->jam_kerja,
        ]);
        return redirect()->route('data_user')->with(['success' => 'Data Berhasil Ditambahkan!']);
    }

    public function edit_user($id, Request $request)
    {
        $data_valid = $request->validate([
            'role'      => ['required', 'string'],
            'nik'       => ['nullable', 'max:13'],
            'nuptk'     => ['nullable', 'max:16'],
            'nbm'       => ['nullable', 'max:20'],
            'nama'      => ['required', 'string', 'max:255'],
            'email' => 'required|unique:users,email,' . $request->id,
            'jabatan' => ['nullable',],
            'jam_kerja' => ['nullable',],
        ]);
        $user = User::find($id);
        $user->update($data_valid);
        return redirect('data_user')->with('success', 'Data Berhasil di Update');
    }

    public function ubah_password($id, Request $request)
    {
        $data_valid = $request->validate([
            'password'      => ['required', 'confirmed'],
        ]);

        $hash = Hash::make($data_valid['password']);
        $user = User::find($id);
        $user->update(['password' => $hash]);
        return redirect('data_user')->with('success', 'Password Berhasil diganti');
    }

    public function hapus_data_user($id)
    {
        $data = User::find($id);
        $data->delete();
        return redirect('data_user')->with('success', 'Data berhasil dihapus');
    }
}
