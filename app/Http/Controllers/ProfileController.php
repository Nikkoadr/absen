<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterTanggalRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfilRequest;
use App\Models\Absensi;
use App\Models\User;
use App\Services\JadwalService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class ProfileController extends Controller
{
    public function __construct(protected JadwalService $jadwal) {}

    public function index()
    {
        $pengguna = request()->user();
        $pengguna->loadMissing(['karyawan', 'siswa.kelas']);
        $bawaan = $this->jadwal->bawaanPengguna($pengguna);

        if ($pengguna->role === 'admin') {
            $zona = 'Asia/Jakarta';
            $hariIni = Carbon::today($zona)->toDateString();

            $historyBulanIni = Absensi::milikPengguna($pengguna->id)
                ->bulan((int) Carbon::now($zona)->month, (int) Carbon::now($zona)->year)
                ->orderBy('tanggal_absen')
                ->get();
            $this->jadwal->anotasiJamKerja($historyBulanIni, $bawaan);
            $absenHariIni = Absensi::milikPengguna($pengguna->id)->padaTanggal($hariIni)->first();

            return view('profile', [
                'historyBulanIni' => $historyBulanIni,
                'absenHariIni' => $absenHariIni,
                'set_jam_kerja' => $pengguna->jam_kerja,
            ]);
        }

        $riwayatTerakhir = Absensi::milikPengguna($pengguna->id)
            ->orderByDesc('tanggal_absen')
            ->take(5)
            ->get();
        $this->jadwal->anotasiJamKerja($riwayatTerakhir, $bawaan);

        return view('profile_mobile', compact('riwayatTerakhir'));
    }

    public function edit_user(UpdateProfilRequest $request, User $user)
    {
        $valid = $request->validated();
        $kunci = ['nik', 'nuptk', 'nbm', 'nomor_hp', 'jabatan', 'jam_kerja', 'jam_pulang'];
        $user->update(Arr::except($valid, $kunci));
        $user->karyawan()->updateOrCreate([], Arr::only($valid, $kunci));

        return to_route('profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function edit_password_user_id(UpdatePasswordRequest $request, User $user)
    {
        $this->authorizeProfil($user->id);
        $user->update($request->validated());

        return to_route('profile')->with('success', 'Kata sandi berhasil diganti.');
    }

    public function upload_pasfoto_id(Request $request, User $user)
    {
        $this->authorizeProfil($user->id);

        $request->validate([
            'pas_foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $berkas = $request->file('pas_foto');
        $namaBaru = $berkas->hashName();
        $berkas->storeAs('pasFotoAbsen', $namaBaru, config('filesystems.default'));

        if ($user->pasfoto) {
            \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->delete('pasFotoAbsen/'.$user->pasfoto);
        }

        $user->update(['pasfoto' => $namaBaru]);

        return to_route('profile')->with('success', 'Foto profil berhasil diunggah.');
    }

    public function history(FilterTanggalRequest $request)
    {
        $pengguna = $request->user();
        $pengguna->loadMissing(['karyawan', 'siswa.kelas']);
        $history = Absensi::milikPengguna($pengguna->id)
            ->bulan($request->bulan(), $request->tahun())
            ->orderBy('tanggal_absen')
            ->get();
        $this->jadwal->anotasiJamKerja($history, $this->jadwal->bawaanPengguna($pengguna));

        return view('history_mobile', [
            'history' => $history,
            'bulan' => $request->bulan(),
            'tahun' => $request->tahun(),
            'set_jam_kerja' => $pengguna->jam_kerja,
        ]);
    }

    protected function authorizeProfil(int $id): void
    {
        if ((int) request()->user()->id !== $id && ! request()->user()->can('is_admin')) {
            abort(403, 'Tidak diizinkan mengubah data pengguna lain.');
        }
    }
}
