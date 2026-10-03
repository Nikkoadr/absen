<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Http\Requests\FilterTanggalRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(FilterTanggalRequest $request)
    {
        $data_user = User::orderBy('nama')->get();

        return view('data_user', [
            'data_user' => $data_user,
            'bulan' => $request->bulan(),
            'tahun' => $request->tahun(),
        ]);
    }

    public function importUser(Request $request)
    {
        $request->validate([
            'import' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        Excel::import(new UsersImport, $request->file('import'));

        return back()->with('success', 'Data karyawan berhasil diimpor.');
    }

    public function exportUser()
    {
        return Excel::download(new UsersExport, 'data_karyawan.xlsx');
    }

    public function tambah_user(StoreUserRequest $request)
    {
        User::create($request->validated());

        return to_route('data_user')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function edit_user(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return to_route('data_user')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function ubah_password(UpdatePasswordRequest $request, User $user)
    {
        $user->update($request->validated());

        return to_route('data_user')->with('success', 'Kata sandi berhasil diganti.');
    }

    public function hapus_data_user(User $user)
    {
        if ($user->pasfoto) {
            Storage::disk(config('filesystems.default'))->delete('pasFotoAbsen/'.$user->pasfoto);
        }

        $user->absensi()->delete();
        $user->delete();

        return to_route('data_user')->with('success', 'Data karyawan beserta presensi terkait berhasil dihapus.');
    }
}
