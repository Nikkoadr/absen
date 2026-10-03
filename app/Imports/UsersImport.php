<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row): Model|array|null
    {
        $email = trim((string) ($row['email'] ?? ''));
        if ($email === '' || User::where('email', $email)->exists()) {
            return null;
        }

        return new User([
            'role' => $row['role'] ?? 'siswa',
            'nik' => $row['nik'] ?? null,
            'nuptk' => $row['nuptk'] ?? null,
            'nbm' => $row['nbm'] ?? null,
            'nama' => $row['nama'] ?? $email,
            'nomor_hp' => $row['nomor_hp'] ?? null,
            'email' => $email,
            'password' => (string) ($row['password'] ?? 'password123'),
            'jabatan' => $row['jabatan'] ?? null,
            'jam_kerja' => $this->parseTime($row['jam_kerja'] ?? null),
            'jam_pulang' => $this->parseTime($row['jam_pulang'] ?? null),
        ]);
    }

    protected function parseTime($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value)->format('H:i:s');
        }

        if (is_numeric($value)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value))->format('H:i:s');
            } catch (\Throwable) {
                return null;
            }
        }

        $text = trim((string) $value);
        foreach (['H:i:s', 'H:i', 'G:i', 'G:i:s'] as $format) {
            try {
                return Carbon::createFromFormat($format, $text)->format('H:i:s');
            } catch (\Throwable) {
                continue;
            }
        }

        return null;
    }
}
