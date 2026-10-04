<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
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
    public function index()
    {
        return view('data_user');
    }

    public function data(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $mulai = max(0, (int) $request->input('start', 0));
        $panjang = (int) $request->input('length', 10);
        $panjang = $panjang < 1 || $panjang > 100 ? 10 : $panjang;
        $cari = trim((string) $request->input('search.value', ''));

        $dasar = User::query();
        $total = (clone $dasar)->count();

        if ($cari !== '') {
            $dasar->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                    ->orWhere('email', 'like', "%{$cari}%")
                    ->orWhere('nik', 'like', "%{$cari}%")
                    ->orWhere('nuptk', 'like', "%{$cari}%")
                    ->orWhere('nbm', 'like', "%{$cari}%")
                    ->orWhere('jabatan', 'like', "%{$cari}%");
            });
        }
        $tersaring = (clone $dasar)->count();

        $kolom = [1 => 'nama', 3 => 'jam_kerja'];
        $urut = $kolom[(int) $request->input('order.0.column', 1)] ?? 'nama';
        $arah = strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        $baris = $dasar->orderBy($urut, $arah)->skip($mulai)->take($panjang)->get();
        $bulan = now('Asia/Jakarta')->month;
        $tahun = now('Asia/Jakarta')->year;

        $data = [];
        $modals = '';
        foreach ($baris as $u) {
            $data[] = [
                'centang' => '<input type="checkbox" class="pilih-user" value="'.$u->id.'" aria-label="Pilih '.e($u->nama).'">',
                'karyawan' => '<strong>'.e($u->nama).'</strong><br><small class="text-muted">'.e(ucfirst($u->role)).($u->jabatan ? ' · '.e($u->jabatan) : '').'</small><br><small class="text-muted">'.e($u->email).'</small><br><small class="text-muted">'.e($u->nomor_hp ?? '-').'</small>',
                'identitas' => '<small class="text-muted">NIK: '.e($u->nik ?? '-').'<br>NUPTK: '.e($u->nuptk ?? '-').'<br>NBM: '.e($u->nbm ?? '-').'</small>',
                'jam' => '<span class="text-nowrap">'.e($u->jam_kerja ? substr($u->jam_kerja, 0, 5) : '-').' - '.e($u->jam_pulang ? substr($u->jam_pulang, 0, 5) : '-').'</span>',
                'aksi' => view('layouts.component.tombol_user', ['data' => $u])->render(),
            ];
            $modals .= view('layouts.component.modal_edit_user', ['data' => $u])->render()
                .view('layouts.component.modal_ubah_password', ['data' => $u])->render()
                .view('layouts.component.modal_print_laporan', ['data' => $u, 'bulan' => $bulan, 'tahun' => $tahun])->render();
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $tersaring,
            'data' => $data,
            'modals' => $modals,
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

    public function hapusBanyakUser(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:users,id'],
        ]);

        $korban = User::whereIn('id', $data['ids'])->where('id', '!=', $request->user()->id)->get();
        $dilewati = count($data['ids']) - $korban->count();

        foreach ($korban as $user) {
            if ($user->pasfoto) {
                Storage::disk(config('filesystems.default'))->delete('pasFotoAbsen/'.$user->pasfoto);
            }
            $user->absensi()->delete();
            $user->delete();
        }

        $pesan = $korban->count().' data karyawan beserta presensi terkait berhasil dihapus.';
        if ($dilewati > 0) {
            $pesan .= ' Akun sendiri tidak ikut dihapus.';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'sukses',
                'message' => $pesan,
                'ids' => $korban->pluck('id')->all(),
            ]);
        }

        return to_route('data_user')->with('success', $pesan);
    }
}
