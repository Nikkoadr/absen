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
        return User::leftJoin('karyawans', 'karyawans.user_id', '=', 'users.id')
            ->orderBy('users.nama')
            ->get([
                'users.id',
                'users.role',
                'karyawans.nik',
                'karyawans.nuptk',
                'karyawans.nbm',
                'users.nama',
                'karyawans.nomor_hp',
                'users.email',
                'karyawans.jabatan',
                'karyawans.jam_kerja',
                'karyawans.jam_pulang',
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
