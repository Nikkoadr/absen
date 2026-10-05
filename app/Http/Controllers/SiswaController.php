<?php

namespace App\Http\Controllers;

use App\Exports\SiswaContohExport;
use App\Imports\SiswaImport;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index()
    {
        return view('siswa', [
            'kelas' => Kelas::with('kompetensi:id,singkatan')->orderBy('tingkat')->orderBy('nama')->get(),
        ]);
    }

    public function data(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $mulai = max(0, (int) $request->input('start', 0));
        $panjang = (int) $request->input('length', 10);
        $panjang = $panjang < 1 || $panjang > 100 ? 10 : $panjang;
        $cari = trim((string) $request->input('search.value', ''));

        $dasar = User::where('role', 'siswa')->with(['siswa.kelas.kompetensi']);
        $total = (clone $dasar)->count();

        if ($cari !== '') {
            $dasar->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                    ->orWhere('email', 'like', "%{$cari}%")
                    ->orWhereHas('siswa', fn ($s) => $s
                        ->where('nis', 'like', "%{$cari}%")
                        ->orWhere('nisn', 'like', "%{$cari}%")
                        ->orWhere('nama_ortu', 'like', "%{$cari}%"));
            });
        }
        $tersaring = (clone $dasar)->count();

        $kolom = [0 => 'nama', 1 => 'email'];
        $urut = $kolom[(int) $request->input('order.0.column', 1)] ?? 'nama';
        $arah = strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        $baris = $dasar->orderBy($urut, $arah)->skip($mulai)->take($panjang)->get();
        $daftarKelas = Kelas::orderBy('tingkat')->orderBy('nama')->get(['id', 'nama', 'tingkat']);

        $data = [];
        $modals = '';
        foreach ($baris as $u) {
            $s = $u->siswa;
            $kelas = $s?->kelas ? $s->kelas->tingkat.' '.$s->kelas->nama.' ('.$s->kelas->kompetensi->singkatan.')' : 'Tanpa kelas';
            $data[] = [
                'siswa' => '<strong>'.e($u->nama).'</strong><br><small class="text-muted">NIS: '.e($s->nis ?? '-').' · NISN: '.e($s->nisn ?? '-').'</small><br><small class="text-muted">'.e($kelas).'</small>',
                'kontak' => '<small class="text-muted">'.e($u->email).'</small>',
                'ortu' => '<small class="text-muted">'.e($s->nama_ortu ?? '-').($s->nomor_hp_ortu ? '<br>'.e($s->nomor_hp_ortu) : '').'</small>',
                'telegram' => $s->telegram_chat_id
                    ? '<span class="badge badge-success">Terhubung</span>'
                    : '<span class="badge badge-secondary">Belum diisi</span>',
                'rfid' => $s->rfid_uid
                    ? '<small class="text-muted">'.e($s->rfid_uid).'</small>'
                    : '<small class="text-muted">Belum ada</small>',
                'aksi' => view('layouts.component.tombol_siswa', ['data' => $u])->render(),
            ];
            $modals .= view('layouts.component.modal_edit_siswa', ['data' => $u, 'kelas' => $daftarKelas])->render();
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $tersaring,
            'data' => $data,
            'modals' => $modals,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'nis' => ['nullable', 'string', 'max:20', 'unique:siswas,nis'],
            'nisn' => ['nullable', 'string', 'max:20', 'unique:siswas,nisn'],
            'kelas_id' => ['nullable', 'integer', 'exists:kelas,id'],
            'nama_ortu' => ['nullable', 'string', 'max:100'],
            'nomor_hp_ortu' => ['nullable', 'string', 'max:15'],
            'telegram_chat_id' => ['nullable', 'string', 'max:50'],
            'rfid_uid' => ['nullable', 'string', 'max:50', 'unique:siswas,rfid_uid'],
        ]);

        $user = User::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => $data['password'],
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'role' => 'siswa',
        ]);
        $user->siswa()->create([
            'nis' => $data['nis'] ?? null,
            'nisn' => $data['nisn'] ?? null,
            'kelas_id' => $data['kelas_id'] ?? null,
            'nama_ortu' => $data['nama_ortu'] ?? null,
            'nomor_hp_ortu' => $data['nomor_hp_ortu'] ?? null,
            'telegram_chat_id' => $data['telegram_chat_id'] ?? null,
            'rfid_uid' => $data['rfid_uid'] ?? null,
        ]);

        return to_route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function update(Request $request, User $siswa)
    {
        abort_if($siswa->role !== 'siswa', 422);

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($siswa->id)],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'nis' => ['nullable', 'string', 'max:20', Rule::unique('siswas', 'nis')->ignore($siswa->siswa?->id)],
            'nisn' => ['nullable', 'string', 'max:20', Rule::unique('siswas', 'nisn')->ignore($siswa->siswa?->id)],
            'kelas_id' => ['nullable', 'integer', 'exists:kelas,id'],
            'nama_ortu' => ['nullable', 'string', 'max:100'],
            'nomor_hp_ortu' => ['nullable', 'string', 'max:15'],
            'telegram_chat_id' => ['nullable', 'string', 'max:50'],
            'rfid_uid' => ['nullable', 'string', 'max:50', Rule::unique('siswas', 'rfid_uid')->ignore($siswa->siswa?->id)],
        ]);

        $siswa->update([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
        ]);
        $siswa->siswa()->updateOrCreate([], [
            'nis' => $data['nis'] ?? null,
            'nisn' => $data['nisn'] ?? null,
            'kelas_id' => $data['kelas_id'] ?? null,
            'nama_ortu' => $data['nama_ortu'] ?? null,
            'nomor_hp_ortu' => $data['nomor_hp_ortu'] ?? null,
            'telegram_chat_id' => $data['telegram_chat_id'] ?? null,
            'rfid_uid' => $data['rfid_uid'] ?? null,
        ]);

        return to_route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(User $siswa)
    {
        abort_if($siswa->role !== 'siswa', 422);

        if ($siswa->pasfoto) {
            Storage::disk(config('filesystems.default'))->delete('pasFotoAbsen/'.$siswa->pasfoto);
        }

        $siswa->absensi()->delete();
        $siswa->delete();

        return to_route('siswa.index')->with('success', 'Data siswa beserta presensi terkait berhasil dihapus.');
    }

    public function contoh()
    {
        return Excel::download(new SiswaContohExport, 'contoh_import_siswa.xlsx');
    }

    public function impor(Request $request)
    {
        $request->validate([
            'berkas' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        $impor = new SiswaImport;
        Excel::import($impor, $request->file('berkas'));

        $pesan = "{$impor->diimpor} data siswa berhasil diimpor.";
        if ($impor->dilewati) {
            $pesan .= ' Dilewati '.count($impor->dilewati).': '.implode(' ', array_slice($impor->dilewati, 0, 3));

            return to_route('siswa.index')->with('success', $pesan)->with('warning_impor', $impor->dilewati);
        }

        return to_route('siswa.index')->with('success', $pesan);
    }
}
