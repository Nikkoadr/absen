<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function selisih($jam_masuk, $jam_batas)
{
    list($h_masuk, $m_masuk, $s_masuk) = explode(":", $jam_masuk);
    $dtAwal = mktime($h_masuk, $m_masuk, $s_masuk, 1, 1, 1);

    list($h_batas, $m_batas, $s_batas) = explode(":", $jam_batas);
    $dtBatas = mktime($h_batas, $m_batas, $s_batas, 1, 1, 1);

    $dtSelisih = $dtAwal - $dtBatas;

    $totalmenit = $dtSelisih / 60;
    $jam = explode(".", $totalmenit / 60);
    $sisamenit = ($totalmenit / 60) - $jam[0];
    $sisamenit2 = $sisamenit * 60;

    return $jam[0] . ":" . round($sisamenit2);
}

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

// Judul laporan
$activeWorksheet->setCellValue('A1', 'Laporan Bulanan');
$activeWorksheet->mergeCells('A1:E1');
$activeWorksheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

// Tanggal periode
$activeWorksheet->setCellValue('A2', 'Periode: ' . \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAwal)->translatedFormat('d F Y') . ' - ' . \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAkhir)->translatedFormat('d F Y'));
$activeWorksheet->mergeCells('A2:E2');
$activeWorksheet->getStyle('A2')->getFont()->setBold(true);

// Header tabel
$activeWorksheet->setCellValue('A4', 'Nama');
$activeWorksheet->setCellValue('B4', 'Jumlah');
$activeWorksheet->setCellValue('C4', 'Keterangan');

// Mengatur lebar kolom
$activeWorksheet->getColumnDimension('A')->setAutoSize(true);
$activeWorksheet->getColumnDimension('B')->setAutoSize(true);
$activeWorksheet->getColumnDimension('C')->setAutoSize(true);

$startColumn = 'D';
$start = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAwal);
$end = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAkhir);

// Mengisi header tanggal
$columnIndex = 4;
while ($start <= $end) {
    $activeWorksheet->setCellValue($startColumn . '4', $start->day);
    $activeWorksheet->getColumnDimension($startColumn)->setAutoSize(true);
    $start->addDay();
    $columnIndex++;
    $startColumn++;
}

// Data rekap
$rowIndex = 5;
foreach ($rekap as $data) {
    $activeWorksheet->setCellValue('A' . $rowIndex, $data->nama);

    $start = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAwal);
    $end = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalAkhir);
    $total = 0;
    $totalTerlambat = 0;

    // Mengisi data kehadiran
    $columnIndex = 4;
    while ($start <= $end) {
        $cellValue = '';
        if ($data->{'tgl_'.$start->day}) {
            list($jamMasuk, $jamKeluar) = explode('-', $data->{'tgl_'.$start->day});
            $terlambat_harian = selisih($jamMasuk, $data->jam_kerja);
            $cellValue = $jamMasuk;
            $total++;
        }
        $activeWorksheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $cellValue);
        $start->addDay();
        $columnIndex++;
    }

        // Jumlah kehadiran
    $activeWorksheet->setCellValue('B' . $rowIndex, $total);

    // Keterangan terlambat
    $activeWorksheet->setCellValue('C' . $rowIndex, 'Terlambat Dalam 1 Bulan: ' . ($data->total_jam_terlambat * 60) . ' Menit');

    $rowIndex++;
}

// Style
$styleArray = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
];
$activeWorksheet->getStyle('A4:' . $startColumn . ($rowIndex - 1))->applyFromArray($styleArray);

// Menyimpan file
$filename = 'Laporan_bulanan.xlsx';
$filepath = __DIR__ . '/' . $filename;
$writer = new Xlsx($spreadsheet);
$writer->save($filepath);

// Atur header untuk file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Baca file dan keluarkan ke output
readfile($filepath);

// Hapus file setelah didownload (opsional)
unlink($filepath);