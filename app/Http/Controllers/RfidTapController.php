<?php

namespace App\Http\Controllers;

use App\Jobs\KirimNotifikasiOrtu;
use App\Models\Perangkat;
use App\Models\Setting;
use App\Models\Siswa;
use App\Services\AbsensiService;
use Illuminate\Http\Request;

/**
 * Tap kartu RFID siswa dari gerbang (PC/ESP32).
 * POST /api/rfid-tap { uid, key } -> JSON { status, message }.
 */
class RfidTapController extends Controller
{
    public function __construct(protected AbsensiService $absensi) {}

    public function tap(Request $request)
    {
        $data = $request->validate([
            'uid' => ['required', 'string', 'max:50'],
            'key' => ['required', 'string'],
        ]);

        $perangkat = Perangkat::where('key_hash', hash('sha256', $data['key']))->first();

        if (! $perangkat && ! hash_equals((string) config('services.rfid.key'), $data['key'])) {
            return response()->json(['status' => 'error', 'message' => 'Kunci perangkat salah.'], 403);
        }

        if ($perangkat && ! $perangkat->aktif) {
            return response()->json(['status' => 'error', 'message' => 'Perangkat dinonaktifkan. Hubungi admin.'], 403);
        }

        $siswa = Siswa::with(['user:id,nama', 'kelas:id,tingkat,nama'])->where('rfid_uid', $data['uid'])->first();

        if (! $siswa || ! $siswa->user) {
            return response()->json(['status' => 'error', 'message' => 'Kartu belum terdaftar.'], 404);
        }

        $setting = Setting::first();
        $acuan = $perangkat && $perangkat->latitude && $perangkat->longitude
            ? ['lat' => (float) $perangkat->latitude, 'lon' => (float) $perangkat->longitude, 'nama' => $perangkat->nama]
            : null;
        $hasil = $this->absensi->catat(
            $siswa->user,
            $setting ? "{$setting->latitude},{$setting->longitude}" : '0,0',
            null,
            true,
            $acuan,
            $perangkat?->id
        );

        if ($hasil['status'] !== 'sukses') {
            return response()->json($hasil, $hasil['http']);
        }

        $perangkat?->update(['terakhir_aktif' => now('Asia/Jakarta')]);
        KirimNotifikasiOrtu::dispatch($siswa->user->id, $hasil['notifikasi']['jenis'] ?? 'MASUK', $hasil['notifikasi']['jam'] ?? '-', true);

        return response()->json([
            'status' => 'sukses',
            'message' => "Hadir tercatat untuk {$siswa->user->nama}.",
            'nama' => $siswa->user->nama,
        ]);
    }

    /** Tap via HP operator (USB reader): identitas dari akun login, bukan kunci alat. */
    public function layar()
    {
        return redirect()->route('monitor');
    }

    public function tapOperator(Request $request)
    {
        abort_unless(in_array($request->user()->role, ['admin', 'guru']), 403);

        $data = $request->validate([
            'uid' => ['required', 'string', 'max:50'],
        ]);

        $siswa = Siswa::with(['user:id,nama,pasfoto', 'kelas:id,tingkat,nama'])->where('rfid_uid', $data['uid'])->first();

        if (! $siswa || ! $siswa->user) {
            return response()->json(['status' => 'error', 'message' => 'Kartu belum terdaftar.'], 404);
        }

        $setting = Setting::first();
        $hasil = $this->absensi->catat(
            $siswa->user,
            $setting ? "{$setting->latitude},{$setting->longitude}" : '0,0',
            null,
            true
        );

        if ($hasil['status'] !== 'sukses') {
            return response()->json($hasil, $hasil['http']);
        }

        KirimNotifikasiOrtu::dispatch($siswa->user->id, $hasil['notifikasi']['jenis'] ?? 'MASUK', $hasil['notifikasi']['jam'] ?? '-', true);

        $kelas = $siswa->kelas ? "{$siswa->kelas->tingkat} {$siswa->kelas->nama}" : 'Tanpa kelas';

        return response()->json([
            'status' => 'sukses',
            'message' => "Hadir tercatat untuk {$siswa->user->nama}.",
            'nama' => $siswa->user->nama,
            'kelas' => $kelas,
            'foto' => $siswa->user->pasfoto ? asset('storage/absen_file/pasFotoAbsen/'.$siswa->user->pasfoto) : null,
            'inisial' => strtoupper(mb_substr(trim($siswa->user->nama ?? '?'), 0, 1)),
            'jam' => $hasil['notifikasi']['jam'] ?? '-',
        ]);
    }
}
