<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection(): Enumerable
    {
        return User::orderBy('nama')->get([
            'id', 'role', 'nik', 'nuptk', 'nbm', 'nama',
            'nomor_hp', 'email', 'jabatan', 'jam_kerja', 'jam_pulang',
        ]);
    }

    public function headings(): array
    {
        return [
            'ID', 'Role', 'NIK', 'NUPTK', 'NBM', 'Nama',
            'Nomor HP', 'Email', 'Jabatan', 'Jam Kerja', 'Jam Pulang',
        ];
    }
}
