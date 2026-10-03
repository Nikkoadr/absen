<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterTanggalRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfilRequest;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProfileController extends Controller
{
    public function index()
    {
        $pengguna = request()->user();

        if ($pengguna->role === 'admin') {
            $zona = 'Asia/Jakarta';
            $hariIni = Carbon::today($zona)->toDateString();

            $historyBulanIni = Absensi::milikPengguna($pengguna->id)
                ->bulan((int) Carbon::now($zona)->month, (int) Carbon::now($zona)->year)
                ->orderBy('tanggal_absen')
                ->get();
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

        return view('profile_mobile', compact('riwayatTerakhir'));
    }

    public function edit_user(UpdateProfilRequest $request, User $user)
    {
        $user->update($request->validated());

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
            'pas_foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $berkas = $request->file('pas_foto');
        $namaBaru = $berkas->hashName('pasFotoAbsen');
        $berkas->storeAs('pasFotoAbsen', basename($namaBaru), config('filesystems.default'));

        if ($user->pasfoto) {
            \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->delete('pasFotoAbsen/'.$user->pasfoto);
        }

        $user->update(['pasfoto' => basename($namaBaru)]);

        return to_route('profile')->with('success', 'Foto profil berhasil diunggah.');
    }

    public function history(FilterTanggalRequest $request)
    {
        $pengguna = $request->user();
        $history = Absensi::milikPengguna($pengguna->id)
            ->bulan($request->bulan(), $request->tahun())
            ->orderBy('tanggal_absen')
            ->get();

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
