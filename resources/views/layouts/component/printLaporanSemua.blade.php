<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<title>Rekap Absensi Bulanan</title>



<!-- Set page size here: A5, A4 or A3 -->
<!-- Set also "landscape" if you need -->

</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body >

<!-- Each sheet element should have the class "sheet" -->
<!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
<section >
        <table width="100%">
            <tr>
                <td width="100px" align="center" valign="middle">
                    <img src="{{asset('assets/dist/img/dikdasmenmuh.png')}}" width="100%">
                </td>
                <td align="center" valign="middle">
                    <b style="color:#007bff;font-size:15pt !important;">MAJELIS PENDIDIKAN DASAR DAN MENENGAH</b><br>
                    <b style="color:#007bff;font-size:15pt !important;">PIMPINAN WILAYAH MUHAMMADIYAH JAWA BARAT</b><br>
                    <b style="color:#007bff;font-size:17pt !important;">SMK MUHAMMADIYAH KANDANGHAUR</b><br>
                    <b style="color:#007bff;;font-size:17pt !important;">SMK PUSAT KEUNGGULAN (PK)</b><br>
                    <b>Terakreditasi "A" (Unggul)</b><br>
                    <b>Nomor : 18572022/BAN-SM/SK/2022</b>
                </td>
                <td width="100px" align="center" valign="middle">
                    <img src="{{asset('assets/dist/img/logo.png')}}" width="80%">
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    {{-- <small style="font-size:8pt !important;">Program Keahlian : Teknik Kendaraan Ringan Otomotif(TKRO),Teknik Dan Bisnis Sepeda Motor(TBSM), Teknik Pengelasan dan Fabrikasi Logam(TPFL), Teknik Elektronika(TE), Teknik Jaringan Komputer dan Telekomunikasi(TJKT), Teknologi Farmasi(TF)</small><br> --}}
                    <small style="font-size:8pt !important;">Jl. Raya Karanganyar No. 28/A Kec. Kandanghaur Kab. Indramayu 45254 Telp. (0234) 507239, email : smkmuhkdh@gmail.com website : https://www.smkmuhkandanghaur.sch.id</small>
                </td>
            </tr>
        </table>
        <div style="height:5px;border-bottom:solid 2px black;border-top:solid 1px black;margin:10px 0"></div>
        <div style="text-align:center; margin:10px">
            <b style="font-size:20pt !important;">Laporan Bulanan</b>
        </div>
<h3>Periode : {{ \Carbon\Carbon::create()->month($bulan)->format('F') }} {{ $tahun }}</h3>
<table style="border: 1px solid black;">
    <thead>
        <tr>
            <th style="border: 1px solid black;" rowspan="2">Nama</th>
            <th style="border: 1px solid black;" colspan="31">tanggal</th>
            <th style="border: 1px solid black;" rowspan="2">Jumlah</th>
        </tr>
        <tr>
            @for ($hari = 1; $hari <= 31; $hari++)
                <th style="border: 1px solid black;">{{ $hari }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ($rekap as $data)
            <tr>
                <td style="border: 1px solid black;">{{ $data->nama }}</td>
                @php
                    $total = 0;
                @endphp
                @for ($hari = 1; $hari <= 31; $hari++)
                        <td style="border: 1px solid black;">
                    @if ($data->{'tgl_'.$hari})
                        @php
                            list($jamMasuk, $jamKeluar) = explode('-', $data->{'tgl_'.$hari});
                        @endphp
                        @if($jamMasuk > $data->jam_kerja)
                            <span style="color: red">T</span>
                            @else
                            H
                        @endif
                        {{-- <strong>Jam Masuk:</strong> {{ $jamMasuk }} <br>
                        <strong>Jam Keluar:</strong> {{ $jamKeluar }} --}}
                        @php
                            // Tambahkan nilai ke total jika ada data
                            $total++;
                        @endphp
                    @endif
                        </td>
                @endfor
                <td style="border: 1px solid black;">{{ $total }}</td>
            </tr>
        @endforeach
    </tbody>

</table>
</section>
</body>

</html>