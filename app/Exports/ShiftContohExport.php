<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Carbon;

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
        $senin = Carbon::now('Asia/Jakarta')->startOfWeek(Carbon::MONDAY);
        $blok1Mulai = $senin->copy()->toDateString();
        $blok1Akhir = $senin->copy()->addDays(11)->toDateString();
        $blok2Mulai = $senin->copy()->addDays(14)->toDateString();
        $blok2Akhir = $senin->copy()->addDays(25)->toDateString();

        return [
            // Blok 2 mingguan: guru yang sama ganti shift setelah 2 minggu
            ['budi@sekolah.sch.id', 'Pagi Blok', '07:00', '15:00', $blok1Mulai, $blok1Akhir],
            ['budi@sekolah.sch.id', 'Siang Blok', '10:00', '15:00', $blok2Mulai, $blok2Akhir],
            // Shift yang sudah ada (Pagi/Siang): jam boleh dikosongkan
            ['siti@sekolah.sch.id', 'Pagi', '', '', $blok1Mulai, ''],
            // Shift baru: dibuat dari jam yang diisi
            ['agus@sekolah.sch.id', 'Blok Produktif', '07:30', '16:00', $blok1Mulai, $blok1Akhir],
            // Tanpa tanggal_selesai = berlaku seterusnya sampai ada jadwal baru
            ['dewi@sekolah.sch.id', 'Siang', '', '', $blok2Mulai, ''],
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
            ['- Rentang yang bertumpuk dengan penugasan lain karyawan yang sama ditolak.'],
            ['- Tanpa penugasan, dipakai jam kerja bawaan di Data Karyawan.'],
        ];
    }
}
