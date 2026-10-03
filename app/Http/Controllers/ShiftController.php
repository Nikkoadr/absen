<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        return view('shift', [
            'shifts' => Shift::orderBy('jam_masuk')->get(),
            'tugas' => ShiftAssignment::with(['user:id,nama', 'shift:id,nama,jam_masuk'])
                ->orderByDesc('tanggal_mulai')
                ->paginate(15),
            'karyawan' => User::orderBy('nama')->get(['id', 'nama']),
        ]);
    }

    public function storeShift(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:shifts,nama'],
            'jam_masuk' => ['required', 'date_format:H:i,H:i:s'],
            'jam_pulang' => ['nullable', 'date_format:H:i,H:i:s'],
        ]);

        Shift::create($data);

        return to_route('shift.index')->with('success', 'Shift berhasil ditambahkan.');
    }

    public function destroyShift(Shift $shift)
    {
        $shift->delete();

        return to_route('shift.index')->with('success', 'Shift beserta penugasannya berhasil dihapus.');
    }

    public function storeTugas(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'shift_id' => ['required', 'integer', 'exists:shifts,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        ShiftAssignment::create($data);

        return to_route('shift.index')->with('success', 'Penugasan shift berhasil disimpan.');
    }

    public function destroyTugas(ShiftAssignment $tugas)
    {
        $tugas->delete();

        return to_route('shift.index')->with('success', 'Penugasan shift berhasil dihapus.');
    }
}
