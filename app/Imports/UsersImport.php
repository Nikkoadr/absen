<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new User([
            'role' => $row['role'],
            'nik'     => $row['nik'],
            'nuptk'     => $row['nuptk'],
            'nbm'     => $row['nbm'],
            'nama'     => $row['nama'],
            'email'    => $row['email'],
            'password' => Hash::make($row['password']),
            'jabatan' => $row['jabatan'],
            'jam_kerja' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['jam_kerja'])
        ]);
    }
}
