<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HolidayController extends Controller
{
    public function index()
    {
        return view('libur');
    }

    public function data(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $mulai = max(0, (int) $request->input('start', 0));
        $panjang = (int) $request->input('length', 10);
        $panjang = $panjang < 1 || $panjang > 100 ? 10 : $panjang;
        $cari = trim((string) $request->input('search.value', ''));

        $dasar = Holiday::query();
        $total = (clone $dasar)->count();

        if ($cari !== '') {
            $dasar->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                    ->orWhere('tanggal', 'like', "%{$cari}%");
            });
        }
        $tersaring = (clone $dasar)->count();

        $kolom = [1 => 'tanggal', 2 => 'nama'];
        $urut = $kolom[(int) $request->input('order.0.column', 1)] ?? 'tanggal';
        $arah = strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        $baris = $dasar->orderBy($urut, $arah)
            ->skip($mulai)
            ->take($panjang)
            ->get();

        $data = $baris->map(function (Holiday $h, $i) use ($mulai) {
            return [
                'no' => $mulai + $i + 1,
                'tanggal' => $h->tanggal->isoFormat('dddd, D MMMM Y'),
                'nama' => e($h->nama),
                'aksi' => '<form action="'.route('libur.destroy', $h).'" method="POST" class="d-inline hapus-libur">'
                    .csrf_field().method_field('delete')
                    .'<button type="submit" class="btn btn-sm btn-danger">Hapus</button></form>',
            ];
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $tersaring,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date', 'unique:holidays,tanggal'],
            'nama' => ['required', 'string', 'max:150'],
            'berulang_tiap_tahun' => ['nullable', 'boolean'],
        ]);

        Holiday::create([
            'tanggal' => $data['tanggal'],
            'nama' => $data['nama'],
            'berulang_tiap_tahun' => (bool) ($data['berulang_tiap_tahun'] ?? false),
        ]);

        return to_route('libur.index')->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function destroy(Holiday $libur)
    {
        $libur->delete();

        return to_route('libur.index')->with('success', 'Hari libur berhasil dihapus.');
    }

    public function sinkron(Request $request)
    {
        $data = $request->validate([
            'tahun' => ['nullable', 'integer', 'min:2020', 'max:2100'],
        ]);

        $tahun = (string) ($data['tahun'] ?? now('Asia/Jakarta')->year);
        $kode = Artisan::call('holidays:sync', ['tahun' => $tahun]);

        return to_route('libur.index')->with(
            $kode === 0 ? 'success' : 'error',
            $kode === 0 ? "Sinkron libur {$tahun} dari API berhasil." : "Sinkron libur {$tahun} gagal. Coba lagi."
        );
    }
}
