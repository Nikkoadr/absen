<?php

namespace App\Http\Controllers;

use App\Http\Requests\IzinRequest;
use App\Models\LeaveRequest;

class IzinController extends Controller
{
    public function izin()
    {
        $pengguna = request()->user();

        if ($pengguna->role === 'admin') {
            return to_route('home')->with('info', 'Halaman pengajuan izin hanya untuk karyawan.');
        }

        $riwayat = LeaveRequest::milikPengguna($pengguna->id)->terbaru()->take(10)->get();

        return view('izin_mobile', compact('riwayat'));
    }

    public function request_izin_user(IzinRequest $request)
    {
        LeaveRequest::create([
            'user_id' => $request->user()->id,
            ...$request->validated(),
        ]);

        return back()->with('success', 'Pengajuan izin berhasil dikirim dan menunggu persetujuan.');
    }
}
