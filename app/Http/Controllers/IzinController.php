<?php

namespace App\Http\Controllers;

use App\Http\Requests\IzinRequest;
use App\Models\LeaveRequest;
use Illuminate\Support\Carbon;

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

    public function daftar()
    {
        $menunggu = LeaveRequest::with('user:id,nama')
            ->where('status', 'pending')
            ->terbaru()
            ->paginate(15, ['*'], 'menunggu');

        return view('perizinan', compact('menunggu'));
    }

    public function setujui(LeaveRequest $perizinan)
    {
        abort_if($perizinan->status !== 'pending', 422, 'Hanya pengajuan pending yang bisa diproses.');

        $perizinan->update([
            'status' => 'disetujui',
            'approved_by' => request()->user()->id,
            'decided_at' => Carbon::now('Asia/Jakarta'),
        ]);

        return back()->with('success', 'Pengajuan izin disetujui.');
    }

    public function tolak(LeaveRequest $perizinan)
    {
        abort_if($perizinan->status !== 'pending', 422, 'Hanya pengajuan pending yang bisa diproses.');

        $perizinan->update([
            'status' => 'ditolak',
            'approved_by' => request()->user()->id,
            'decided_at' => Carbon::now('Asia/Jakarta'),
        ]);

        return back()->with('success', 'Pengajuan izin ditolak.');
    }
}
