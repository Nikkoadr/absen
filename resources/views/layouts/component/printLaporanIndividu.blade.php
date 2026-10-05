<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Individu {{ $user->nama }} - {{ $bulan }}/{{ $tahun }}</title>
<style>
    * { box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; color: #1e293b; margin: 24px; }
    .kop td { vertical-align: middle; }
    .kop-nama { color: #075985; }
    .garis { height: 5px; border-bottom: solid 2px #000; border-top: solid 1px #000; margin: 10px 0; }
    .judul { text-align: center; margin: 18px 0 4px; }
    .judul h2 { margin: 0; font-size: 20pt; letter-spacing: 1px; }
    .judul p { margin: 4px 0 0; font-size: 12pt; color: #475569; }
    .identitas { display: flex; gap: 20px; border: 1px solid #cbd5e1; border-radius: 12px; padding: 16px 20px; margin: 16px 0; align-items: center; }
    .identitas img { width: 110px; height: 140px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1; }
    .identitas .inisial { width: 110px; height: 140px; border-radius: 8px; background: #075985; color: #fff; font-size: 44px; font-weight: bold; display: flex; align-items: center; justify-content: center; }
    .identitas table { border-collapse: collapse; font-size: 11pt; }
    .identitas td { padding: 3px 8px 3px 0; }
    .identitas td.k { color: #475569; width: 130px; }
    .ringkas { display: flex; gap: 12px; margin: 16px 0; }
    .kotak { flex: 1; border: 1px solid #cbd5e1; border-radius: 12px; padding: 12px; text-align: center; }
    .kotak .angka { font-size: 22pt; font-weight: bold; color: #075985; }
    .kotak .label { font-size: 10pt; color: #475569; }
    .kotak.peringatan .angka { color: #b91c1c; }
    table.data { border-collapse: collapse; width: 100%; margin-top: 8px; font-size: 10pt; }
    table.data th { background: #075985; color: #fff; padding: 9px 8px; text-align: center; }
    table.data td { border: 1px solid #94a3b8; padding: 7px 8px; text-align: center; }
    table.data tr.ganjil td { background: #f1f5f9; }
    table.data tr.libur td { background: #f1f5f9; color: #64748b; }
    table.data tr.telat td { background: #fef2f2; }
    .lencana { display: inline-block; border-radius: 999px; padding: 2px 12px; font-size: 9pt; font-weight: bold; }
    .lencana.ok { background: #dcfce7; color: #166534; }
    .lencana.telat { background: #fee2e2; color: #b91c1c; }
    .ttd { display: flex; justify-content: space-between; margin-top: 36px; font-size: 11pt; }
    .ttd div { text-align: center; width: 260px; }
    .ttd .ruang { height: 70px; }
    .no-print { position: fixed; top: 16px; right: 16px; }
    .no-print button { background: #075985; color: #fff; border: none; border-radius: 8px; padding: 10px 20px; font-size: 12pt; cursor: pointer; }
    @media print {
        body { margin: 0; }
        .no-print { display: none; }
        * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
</head>
<body>
<div class="no-print"><button onclick="window.print()">Cetak</button></div>

<table class="kop" width="100%">
    <tr>
        <td width="100px" align="center"><img src="{{ asset('assets/dist/img/dikdasmenmuh.png') }}" width="90" alt="Logo Dikdasmen Muhammadiyah"></td>
        <td align="center">
            <b class="kop-nama" style="font-size:13pt;">MAJELIS PENDIDIKAN DASAR MENENGAH DAN PENDIDIKAN NONFORMAL</b><br>
            <b class="kop-nama" style="font-size:13pt;">PIMPINAN WILAYAH MUHAMMADIYAH JAWA BARAT</b><br>
            <b class="kop-nama" style="font-size:15pt;">SMK MUHAMMADIYAH KANDANGHAUR</b><br>
            <b class="kop-nama" style="font-size:15pt;">SMK PUSAT KEUNGGULAN (PK)</b><br>
            <b>Terakreditasi "A" (Unggul)</b><br>
            <b>Nomor : 18572022/BAN-SM/SK/2022</b>
        </td>
        <td width="100px" align="center"><img src="{{ asset('assets/dist/img/logo.png') }}" width="70" alt="Logo SMK Muhammadiyah Kandanghaur"></td>
    </tr>
    <tr>
        <td colspan="3" align="center">
            <small style="font-size:8pt;">Konsentrasi Keahlian : Teknik Kendaraan Ringan (TKR), Teknik Sepeda Motor (TSM), Teknik Pengelasan (TPL), Teknik Elektronika Industri (TEI), Teknik Komputer dan Jaringan (TKJ), Farmasi Klinis Dan Komunitas (FKK)</small><br>
            <small style="font-size:8pt;">Jl. Raya Karanganyar No. 28/A Kec. Kandanghaur Kab. Indramayu 45254 Telp. 081122207770, email : smkmuhkdh@gmail.com website : https://www.smkmuhkandanghaur.sch.id</small>
        </td>
    </tr>
</table>
<div class="garis"></div>

<div class="judul">
    <h2>LAPORAN ABSENSI INDIVIDU</h2>
    <p>Periode {{ \Illuminate\Support\Carbon::create()->month($bulan)->isoFormat('MMMM') }} {{ $tahun }}</p>
</div>

@php
    $isSiswa = $user->role === 'siswa';
    $kelasTxt = ($user->siswa?->kelas ? $user->siswa->kelas->tingkat.' '.$user->siswa->kelas->nama : '-');
    $hadir = 0; $kaliTelat = 0; $menitTelat = 0;
@endphp
<div class="identitas">
    @if ($user->pasfoto)
        <img src="{{ asset('storage/absen_file/pasFotoAbsen/'.$user->pasfoto) }}" alt="Pas foto {{ $user->nama }}">
    @else
        <span class="inisial">{{ strtoupper(mb_substr(trim($user->nama ?? '?'), 0, 1)) }}</span>
    @endif
    <table>
        <tr><td class="k">Nama</td><td><strong>{{ $user->nama }}</strong></td></tr>
        <tr><td class="k">{{ $isSiswa ? 'NIS / NISN' : 'NIK' }}</td><td>{{ $isSiswa ? ($user->siswa?->nis ?? '-').' / '.($user->siswa?->nisn ?? '-') : ($user->karyawan?->nik ?? '-') }}</td></tr>
        <tr><td class="k">{{ $isSiswa ? 'Kelas' : 'Jabatan' }}</td><td>{{ $isSiswa ? $kelasTxt : ($user->karyawan?->jabatan ?? '-') }}</td></tr>
        <tr><td class="k">Jam Kerja</td><td>{{ $user->jam_kerja ? substr($user->jam_kerja, 0, 5) : ($user->siswa?->kelas ? substr($user->siswa->kelas->jam_masuk, 0, 5) : '-') }}</td></tr>
    </table>
</div>

<table class="data">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Hari</th>
            <th>Masuk</th>
            <th>Pulang</th>
            <th>Durasi</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($rekap as $data)
            @php
                $tgl = \Illuminate\Support\Carbon::parse($data->tanggal_absen);
                $batas = $data->jam_kerja_hari ?? $user->jam_kerja;
                $telat = $batas && substr($data->jam_masuk, 0, 5) > substr($batas, 0, 5);
                $hadir++;
                if ($telat) {
                    $kaliTelat++;
                    $menitTelat += abs(\Illuminate\Support\Carbon::parse(substr($data->jam_masuk, 0, 5))->diffInMinutes(\Illuminate\Support\Carbon::parse(substr($batas, 0, 5))));
                }
                $durasi = $data->jam_keluar
                    ? \App\Support\WaktuKerja::formatSelisih($data->jam_masuk, $data->jam_keluar)
                    : '-';
            @endphp
            <tr class="{{ $telat ? 'telat' : '' }} {{ $loop->even ? 'ganjil' : '' }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tgl->format('d-m-Y') }}</td>
                <td>{{ $tgl->isoFormat('dddd') }}</td>
                <td>{{ substr($data->jam_masuk, 0, 5) }}</td>
                <td>{{ $data->jam_keluar ? substr($data->jam_keluar, 0, 5) : '-' }}</td>
                <td>{{ $durasi }}</td>
                <td>
                    @if ($telat)
                        <span class="lencana telat">Terlambat</span>
                    @else
                        <span class="lencana ok">Tepat waktu</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7">Belum ada presensi pada bulan ini.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="ringkas">
    <div class="kotak"><div class="angka">{{ $hadir }}</div><div class="label">Hari hadir</div></div>
    <div class="kotak peringatan"><div class="angka">{{ $kaliTelat }}</div><div class="label">Kali terlambat ({{ $menitTelat }} menit)</div></div>
    <div class="kotak"><div class="angka">{{ $jumlahIzin ?? 0 }}</div><div class="label">Hari izin disetujui</div></div>
</div>

<div class="ttd">
    <div>
        Mengetahui,<br>Kepala Sekolah<br><br><br><br><br>
        <u>( ........................................ )</u><br>NIP.
    </div>
    <div>
        Kandanghaur, {{ \Illuminate\Support\Carbon::now('Asia/Jakarta')->isoFormat('D MMMM Y') }}<br>
        {{ $isSiswa ? 'Siswa' : 'Pegawai' }} yang bersangkutan<br><br><br><br><br>
        <u>( {{ $user->nama }} )</u>
    </div>
</div>

<script>window.print();</script>
</body>
</html>
