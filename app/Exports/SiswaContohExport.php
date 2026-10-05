<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class SiswaContohExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [new SiswaContohDataSheet, new SiswaPetunjukSheet];
    }
}

class SiswaContohDataSheet implements FromArray, WithHeadings, WithTitle, WithColumnWidths
{
    public function title(): string
    {
        return 'Contoh';
    }

    public function headings(): array
    {
        return ['nama', 'email', 'password', 'nis', 'nisn', 'tanggal_lahir', 'kelas', 'nama_ortu', 'nomor_hp_ortu', 'telegram_chat_id', 'rfid_uid'];
    }

    public function array(): array
    {
        return [
            ['Ahmad Fauzi', 'ahmad@sekolah.sch.id', 'password123', '2526001', '0061234567', '2010-05-12', 'X TKJ 1', 'H. Fauzi', '081234567890', '', ''],
            ['Sinta Dewi', 'sinta@sekolah.sch.id', 'password123', '2526002', '0061234568', '2010-08-03', 'X TKJ 1', 'Hj. Dewi', '081234567891', '', ''],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 20, 'B' => 25, 'C' => 14, 'D' => 12, 'E' => 14, 'F' => 14, 'G' => 12, 'H' => 20, 'I' => 16, 'J' => 16, 'K' => 14];
    }
}

class SiswaPetunjukSheet implements FromArray, WithTitle
{
    public function title(): string
    {
        return 'Petunjuk';
    }

    public function array(): array
    {
        return [
            ['CARA PAKAI'],
            ['1. Hapus baris contoh, isi dengan data siswa, lalu upload di halaman Data Siswa.'],
            ['2. Kolom kelas ditulis "TINGKAT NAMA", cth: X TKJ 1. Baris dilewati bila kelas tidak dikenal.'],
            [''],
            ['KOLOM'],
            ['nama, email — wajib. Email harus unik dan valid.'],
            ['password — opsional, default password123 bila dikosongkan.'],
            ['nis, nisn — opsional, harus unik bila diisi.'],
            ['tanggal_lahir — opsional. Format YYYY-MM-DD atau DD/MM/YYYY.'],
            ['kelas — opsional. Boleh dikosongkan, diisi belakangan.'],
            ['nama_ortu, nomor_hp_ortu, telegram_chat_id, rfid_uid — opsional, bisa dilengkapi belakangan.'],
        ];
    }
}
