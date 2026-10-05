<?php

namespace App\Http\Controllers;

use App\Services\BackupService;

class BackupController extends Controller
{
    public function __construct(protected BackupService $backup) {}

    public function index()
    {
        return view('cadangan', ['daftar' => $this->backup->daftar()]);
    }

    public function buat()
    {
        try {
            $nama = $this->backup->buat();
        } catch (\Throwable $e) {
            return to_route('cadangan.index')->with('error', 'Gagal: '.$e->getMessage());
        }

        return to_route('cadangan.index')->with('success', "Cadangan {$nama} berhasil dibuat.");
    }

    public function unduh(string $nama)
    {
        $berkas = $this->backup->berkas($nama);
        abort_if(! $berkas, 404);

        return response()->download($berkas);
    }
}
