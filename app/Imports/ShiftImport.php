<?php

namespace App\Imports;

use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ShiftImport implements ToCollection, WithHeadingRow
{
    public int $diimpor = 0;

    /** @var string[] */
    public array $dilewati = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $barisKe => $row) {
            $no = $barisKe + 2; // + header
            $email = trim((string) ($row['email'] ?? ''));

            if ($email === '') {
                continue; // baris kosong
            }

            $user = User::where('email', $email)->first();
            if (! $user) {
                $this->dilewati[] = "Baris {$no}: email {$email} tidak terdaftar.";

                continue;
            }

            $namaShift = trim((string) ($row['nama_shift'] ?? ''));
            if ($namaShift === '') {
                $this->dilewati[] = "Baris {$no}: nama_shift wajib diisi.";

                continue;
            }

            $mulai = $this->parseTanggal($row['tanggal_mulai'] ?? null);
            if (! $mulai) {
                $this->dilewati[] = "Baris {$no}: tanggal_mulai tidak valid.";

                continue;
            }

            $selesai = $this->parseTanggal($row['tanggal_selesai'] ?? null);
            if ($selesai && $selesai < $mulai) {
                $this->dilewati[] = "Baris {$no}: tanggal_selesai sebelum tanggal_mulai.";

                continue;
            }

            $shift = Shift::whereRaw('LOWER(nama) = ?', [mb_strtolower($namaShift)])->first();

            if (! $shift) {
                $jamMasuk = $this->parseJam($row['jam_masuk'] ?? null);
                if (! $jamMasuk) {
                    $this->dilewati[] = "Baris {$no}: shift '{$namaShift}' belum ada, isi jam_masuk (cth 07:00).";

                    continue;
                }

                $shift = Shift::create([
                    'nama' => $namaShift,
                    'jam_masuk' => $jamMasuk,
                    'jam_pulang' => $this->parseJam($row['jam_pulang'] ?? null),
                ]);
            }

            ShiftAssignment::firstOrCreate([
                'user_id' => $user->id,
                'shift_id' => $shift->id,
                'tanggal_mulai' => $mulai,
            ], [
                'tanggal_selesai' => $selesai,
            ]);

            $this->diimpor++;
        }
    }

    protected function parseTanggal($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value)->toDateString();
        }

        if (is_numeric($value)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value))->toDateString();
            } catch (\Throwable) {
                return null;
            }
        }

        $text = trim((string) $value);
        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'd M Y'] as $format) {
            try {
                return Carbon::createFromFormat($format, $text)->toDateString();
            } catch (\Throwable) {
                continue;
            }
        }

        try {
            return Carbon::parse($text)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    protected function parseJam($value): ?string
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
