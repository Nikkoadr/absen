<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToCollection, WithHeadingRow
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

            if (! filter_var($email, FILTER_VALIDATE_EMAIL) || User::where('email', $email)->exists()) {
                $this->dilewati[] = "Baris {$no}: email tidak valid atau sudah terdaftar.";

                continue;
            }

            $nama = trim((string) ($row['nama'] ?? ''));
            if ($nama === '') {
                $this->dilewati[] = "Baris {$no}: nama wajib diisi.";

                continue;
            }

            $kelasId = $this->cariKelas((string) ($row['kelas'] ?? ''));
            if (($row['kelas'] ?? '') !== '' && ! $kelasId) {
                $this->dilewati[] = "Baris {$no}: kelas '{$row['kelas']}' tidak dikenal (cth: XII TKJ 1).";

                continue;
            }

            if (($row['nis'] ?? '') !== '' && \App\Models\Siswa::where('nis', $row['nis'])->exists()) {
                $this->dilewati[] = "Baris {$no}: NIS sudah terdaftar.";

                continue;
            }

            $user = User::create([
                'nama' => $nama,
                'email' => $email,
                'password' => (string) ($row['password'] ?? 'password123'),
                'tanggal_lahir' => $this->parseTanggal($row['tanggal_lahir'] ?? null),
                'role' => 'siswa',
            ]);
            $user->siswa()->create([
                'nis' => $row['nis'] ?? null,
                'nisn' => $row['nisn'] ?? null,
                'kelas_id' => $kelasId,
                'nama_ortu' => $row['nama_ortu'] ?? null,
                'nomor_hp_ortu' => $row['nomor_hp_ortu'] ?? null,
                'telegram_chat_id' => $row['telegram_chat_id'] ?? null,
                'rfid_uid' => $row['rfid_uid'] ?? null,
            ]);

            $this->diimpor++;
        }
    }

    /** "XII TKJ 1" -> id kelas, atau null bila kosong. */
    protected function cariKelas(string $teks): ?int
    {
        $teks = trim($teks);
        if ($teks === '') {
            return null;
        }

        [$tingkat, $nama] = array_pad(preg_split('/\s+/', $teks, 2), 2, null);
        if (! $nama || ! in_array(strtoupper($tingkat), ['X', 'XI', 'XII', 'XIII'], true)) {
            return null;
        }

        return Kelas::where('tingkat', strtoupper($tingkat))->where('nama', $nama)->value('id');
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
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value))->toDateString();
            } catch (\Throwable) {
                return null;
            }
        }

        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y'] as $format) {
            try {
                return Carbon::createFromFormat($format, trim((string) $value))->toDateString();
            } catch (\Throwable) {
                continue;
            }
        }

        return null;
    }
}
