<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('is_admin');

        $data = $request->validate([
            'bulan' => ['nullable', 'integer', 'min:1', 'max:12'],
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $data_user = User::orderBy('nama')->get();
        $bulan = $data['bulan'] ?? now('Asia/Jakarta')->month;
        $tahun = $data['tahun'] ?? now('Asia/Jakarta')->year;

        return view('data_user', compact('data_user', 'bulan', 'tahun'));
    }

    public function importUser(Request $request)
    {
        Gate::authorize('is_admin');

        $request->validate([
            'import' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        Excel::import(new UsersImport, $request->file('import'));

        return back()->with(['success' => 'Data Berhasil Diimport!']);
    }

    public function exportUser()
    {
        Gate::authorize('is_admin');

        return Excel::download(new UsersExport, 'data_users.xlsx');
    }

    public function tambah_user(Request $request)
    {
        Gate::authorize('is_admin');

        $validated = $request->validate([
            'role' => ['required', 'string', 'in:admin,karyawan,guru,siswa'],
            'nik' => ['nullable', 'string', 'max:16'],
            'nuptk' => ['nullable', 'string', 'max:16'],
            'nbm' => ['nullable', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:255'],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'jam_kerja' => ['nullable', 'date_format:H:i,H:i:s'],
            'jam_pulang' => ['nullable', 'date_format:H:i,H:i:s'],
        ]);

        User::create([
            'role' => $validated['role'],
            'nik' => $validated['nik'] ?? null,
            'nuptk' => $validated['nuptk'] ?? null,
            'nbm' => $validated['nbm'] ?? null,
            'nama' => $validated['nama'],
            'nomor_hp' => $validated['nomor_hp'] ?? null,
            'email' => $validated['email'],
            'password' => $validated['password'],
            'jabatan' => $validated['jabatan'] ?? null,
            'jam_kerja' => $validated['jam_kerja'] ?? null,
            'jam_pulang' => $validated['jam_pulang'] ?? null,
        ]);

        return redirect()->route('data_user')->with(['success' => 'Data Berhasil Ditambahkan!']);
    }

    public function edit_user($id, Request $request)
    {
        Gate::authorize('is_admin');

        $data_valid = $request->validate([
            'role' => ['required', 'string', 'in:admin,karyawan,guru,siswa'],
            'nik' => ['nullable', 'string', 'max:16'],
            'nuptk' => ['nullable', 'string', 'max:16'],
            'nbm' => ['nullable', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:255'],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'jabatan' => ['nullable', 'string', 'max:255'],
            'jam_kerja' => ['nullable', 'date_format:H:i,H:i:s'],
            'jam_pulang' => ['nullable', 'date_format:H:i,H:i:s'],
        ]);
        $user = User::findOrFail($id);
        $user->update($data_valid);

        return redirect('data_user')->with('success', 'Data Berhasil di Update');
    }

    public function ubah_password($id, Request $request)
    {
        Gate::authorize('is_admin');

        $data_valid = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::findOrFail($id);
        $user->update(['password' => $data_valid['password']]);

        return redirect('data_user')->with('success', 'Password Berhasil diganti');
    }

    public function hapus_data_user($id)
    {
        Gate::authorize('is_admin');

        $user = User::findOrFail($id);

        DB::transaction(function () use ($id, $user) {
            DB::table('absensi')->where('id_user', $id)->delete();
            $user->delete();
        });

        return redirect('data_user')->with('success', 'Data berhasil dihapus beserta data absensi terkait.');
    }
}
