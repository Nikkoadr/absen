<?php

namespace App\Exports;

use App\Services\LaporanService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanBulananExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected string $tanggalAwal,
        protected string $tanggalAkhir,
    ) {}

    public function collection(): Collection
    {
        $rekap = app(LaporanService::class)->rekap($this->tanggalAwal, $this->tanggalAkhir);
        $mulai = Carbon::parse($this->tanggalAwal);
        $selesai = Carbon::parse($this->tanggalAkhir);

        return $rekap->map(function ($baris) use ($mulai, $selesai) {
            $hadir = 0;
            $presensi = [];
            $cursor = $mulai->copy();
            while ($cursor->lte($selesai)) {
                $kunci = 'tgl_'.$cursor->day;
                $nilai = $baris->$kunci ?? '';

                if ($nilai === 'Izin') {
                    $presensi[] = 'I';
                } else {
                    $presensi[] = $nilai !== '' ? substr((string) $nilai, 0, 5) : '';

                    if ($nilai !== '' && preg_match('/^\d{2}:\d{2}/', (string) $nilai)) {
                        $hadir++;
                    }
                }

                $cursor->addDay();
            }

            return array_merge(
                [$baris->nama, $baris->jabatan, $hadir, 'Terlambat: '.round($baris->total_jam_terlambat * 60).' menit. Izin: '.($baris->jumlah_izin ?? 0).' hari. Kosong = Alfa (A).'],
                $presensi
            );
        });
    }

    public function headings(): array
    {
        $kepala = ['Nama', 'Jabatan', 'Jumlah Hadir', 'Keterangan'];
        $mulai = Carbon::parse($this->tanggalAwal);
        $selesai = Carbon::parse($this->tanggalAkhir);
        $cursor = $mulai->copy();
        while ($cursor->lte($selesai)) {
            $kepala[] = 'Tgl '.$cursor->day;
            $cursor->addDay();
        }

        return [
            ["Rekap Presensi {$this->tanggalAwal} s.d. {$this->tanggalAkhir}"],
            $kepala,
        ];
    }
}
