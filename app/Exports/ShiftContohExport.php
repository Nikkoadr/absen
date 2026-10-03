<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class ShiftContohExport implements Export, WithMultipleSheets
{
    public function sheets(): array
    {
        return [new ShiftContohDataSheet, new ShiftPetunjukSheet];
    }
}

class ShiftContohDataSheet implements FromArray, WithHeadings, WithTitle, WithColumnWidths
{
    public function title(): string
    {
        return 'Contoh';
    }

    public function headings(): array
    {
        return ['email', 'nama_shift', 'jam_masuk', 'jam_pulang', 'tanggal_mulai', 'tanggal_selesai'];
    }

    public function array(): array
    {
        return [
            // Blok 2 mingguan: guru yang sama ganti shift setelah 2 minggu
            ['budi@sekolah.sch.id', 'Pagi Blok', '07:00', '15:00', '2026-11-03', '2026-11-14'],
            ['budi@sekolah.sch.id', 'Siang Blok', '10:00', '15:00', '2026-11-17', '2026-11-28'],
            // Shift yang sudah ada (Pagi/Siang): jam boleh dikosongkan
            ['siti@sekolah.sch.id', 'Pagi', '', '', '2026-11-03', ''],
            // Shift baru: otomatis dibuatkan dari jam yang diisi
            ['agus@sekolah.sch.id', 'Blok Produktif', '07:30', '16:00', '2026-11-03', '2026-11-14'],
            // Tanpa tanggal_selesai = berlaku seterusnya sampai ada jadwal baru
            ['dewi@sekolah.sch.id', 'Siang', '', '', '2026-12-01', ''],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 25, 'B' => 18, 'C' => 12, 'D' => 12, 'E' => 15, 'F' => 15];
    }
}

class ShiftPetunjukSheet implements FromArray, WithTitle
{
    public function title(): string
    {
        return 'Petunjuk';
    }

    public function array(): array
    {
        return [
            ['CARA PAKAI'],
            ['1. Ganti kolom email dengan email karyawan yang sudah terdaftar di aplikasi.'],
            ['2. Hapus baris contoh, isi dengan jadwal terbaru, lalu upload di halaman Jadwal Shift.'],
            ['3. Upload boleh dilakukan kapan saja — jadwal baru langsung berlaku sesuai tanggalnya.'],
            [''],
            ['KOLOM'],
            ['email — wajib. Harus sama persis dengan email karyawan. Baris dilewati bila tidak terdaftar.'],
            ['nama_shift — wajib. Bila belum ada, shift otomatis dibuatkan (wajib isi jam_masuk).'],
            ['jam_masuk — wajib hanya untuk shift baru. Format 07:00 atau 07:00:00.'],
            ['jam_pulang — opsional. Dikosongkan bila tidak ada.'],
            ['tanggal_mulai — wajib. Format YYYY-MM-DD atau DD/MM/YYYY.'],
            ['tanggal_selesai — opsional. Kosong = berlaku seterusnya. Harus >= tanggal_mulai.'],
            [''],
            ['ATURAN'],
            ['- Satu karyawan boleh punya banyak baris (rotasi/blok 2 mingguan).'],
            ['- Bila rentang bertumpuk, penugasan dengan tanggal_mulai terbaru yang dipakai.'],
            ['- Tanpa penugasan, dipakai jam kerja bawaan di Data Karyawan.'],
        ];
    }
}
