<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        return view('audit', [
            'tabel' => AuditLog::select('tabel')->distinct()->orderBy('tabel')->pluck('tabel'),
        ]);
    }

    public function data(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $mulai = max(0, (int) $request->input('start', 0));
        $panjang = (int) $request->input('length', 10);
        $panjang = $panjang < 1 || $panjang > 100 ? 10 : $panjang;

        $dasar = AuditLog::with('pelaku:id,nama')->orderByDesc('id');
        $total = (clone $dasar)->count();

        if ($request->filled('aksi')) {
            $dasar->where('aksi', $request->input('aksi'));
        }
        if ($request->filled('tabel')) {
            $dasar->where('tabel', $request->input('tabel'));
        }

        $tersaring = (clone $dasar)->count();
        $baris = $dasar->skip($mulai)->take($panjang)->get();

        $label = ['tambah' => 'success', 'ubah' => 'warning', 'hapus' => 'danger'];
        $data = $baris->map(fn ($a) => [
            'waktu' => $a->created_at?->isoFormat('D MMM Y HH:mm'),
            'pelaku' => e($a->pelaku->nama ?? 'Sistem'),
            'aksi' => '<span class="badge badge-'.($label[$a->aksi] ?? 'secondary').'">'.e($a->aksi).'</span>',
            'sasaran' => '<small>'.e($a->tabel).' #'.e((string) $a->record_id).'</small>',
            'rincian' => '<small class="text-muted">'.e($this->ringkas($a)).'</small>',
        ])->all();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $tersaring,
            'data' => $data,
        ]);
    }

    protected function ringkas(AuditLog $a): string
    {
        $gabung = array_merge((array) $a->data_lama, (array) $a->data_baru);

        return collect($gabung)
            ->except(['created_at', 'updated_at', 'id', 'user_id'])
            ->take(4)
            ->map(fn ($v, $k) => "{$k}: ".(is_scalar($v) ? substr((string) $v, 0, 30) : '?'))
            ->implode('; ');
    }
}
