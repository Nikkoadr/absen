<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IzinController extends Controller
{
    public function izin()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('home')->with('info', 'Halaman izin hanya untuk pengguna non-admin.');
        }

        return view('izin_mobile');
    }

    public function request_izin_user(Request $request, $id)
    {
        if ((int) $id !== (int) Auth::id()) {
            abort(403, 'Tidak diizinkan mengajukan izin untuk pengguna lain.');
        }

        $request->validate([
            'jenis' => ['nullable', 'string', 'max:50'],
            'tanggal' => ['nullable', 'date'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        return back()->with('info', 'Maaf, fitur izin masih dalam proses development.');
    }
}
