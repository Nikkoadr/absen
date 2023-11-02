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
    public function index()
    {
        $this->authorize('is_admin');
        $data_user = User::latest()->paginate(100);
        return view('data_user', compact('data_user'));
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
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string'],
        ]);
        User::create([
            'nama'     => $request->nama,
            'email'   => $request->email,
            'password'   => Hash::make($request->password),
            'role'   => $request->role,
        ]);
        return redirect()->route('data_user')->with(['success' => 'Data Berhasil Ditambahkan!']);
    }

    public function edit_user($id, Request $request)
    {
        $data_valid = $request->validate([
            'nama' => ['required', 'string'],
            'email' => ['required', 'string'],
            'role' => ['required', 'string'],
        ]);
        $user = User::find($id);
        $user->update($data_valid);
        return redirect('data_user')->with('success', 'Data Berhasil di Update');
    }

    public function hapus_data_user($id)
    {
        $data = User::find($id);
        $data->delete();
        return redirect('data_user')->with('success', 'Data berhasil dihapus');
    }
}
