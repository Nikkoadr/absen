@php
    use Illuminate\Support\Carbon; 
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ asset('css/cetak.min.css') }}">
    <style>
        ol > li{
            padding:5px 10px;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print PPDB SMK Muhammadiyah Kandanghaur</title>
</head>
<body>
        <div class="page">
        <table width="100%">
            <tr>
                <td width="100px" align="center" valign="middle">
                    <img src="{{asset('assets/dist/img/dikdasmenmuh.png')}}" width="100%">
                </td>
                <td align="center" valign="middle">
                    <b style="color:#007bff;font-size:10pt !important;">MAJELIS PENDIDIKAN DASAR DAN MENENGAH</b><br>
                    <b style="color:#007bff;font-size:10pt !important;">PIMPINAN WILAYAH MUHAMMADIYAH JAWA BARAT</b><br>
                    <b style="color:#007bff;font-size:14pt !important;">SMK MUHAMMADIYAH KANDANGHAUR</b><br>
                    <b style="color:#007bff;;font-size:14pt !important;">SMK PUSAT KEUNGGULAN (PK)</b><br>
                    <b>Terakreditasi "A" (Unggul)</b><br>
                    <b>Nomor : 02.00/375/BAP-SM/XI/2008</b>
                    <div style="height:20px"></div>
                </td>
                <td width="100px" align="center" valign="middle">
                    <img src="{{asset('assets/dist/img/logo.png')}}" width="80%">
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <small style="font-size:8pt !important;">Program Keahlian : Teknik Kendaraan Ringan Otomotif(TKRO),Teknik Dan Bisnis Sepeda Motor(TBSM), Teknik Pengelasan dan Fabrikasi Logam(TPFL), Teknik Elektronika(TE), Teknik Jaringan Komputer dan Telekomunikasi(TJKT), Teknologi Farmasi(TF)</small><br>
                    <small style="font-size:8pt !important;">Jl. Raya Karanganyar No. 28/A Kec. Kandanghaur Kab. Indramayu 45254 Telp. (0234) 507239, email : smkmuhkdh@gmail.com website : smkmuhkandanghaur.sch.id</small>
                </td>
            </tr>
        </table>
        <div style="height:5px;border-bottom:solid 2px black;border-top:solid 1px black;margin:10px 0"></div>
        <div style="text-align:center; margin:40px auto 30px auto">
            <b style="font-size:20pt !important;">LAPORAN ABSENSI INDIVIDU</b>
        </div>
        
        <div style="text-align:left; margin:20px auto 20px auto">
            <b style="font-size:14pt !important;">Periode : {{ Carbon::create()->month($bulan)->isoFormat('MMMM') }} {{ $tahun }}</b>
        </div>
        <table width="100%" class="it-grid">
            <tr style="background:#f6ff00">
                <td style="padding:10px" colspan="4" align="center"><b style="font-size:12pt !important;">Rekap Bulan {{  Carbon::create()->month($bulan)->isoFormat('MMMM') }}</b></td>
            </tr>
            <tr>
                <td width="250px"><b>Foto Masuk</b></td>
                <td width="250px"><b>Jam Masuk</b></td>
                <td width="250px"><b>Foto Pulang</b></td>
                <td width="250px"><b>Jam Pulang</b></td>
            </tr>
    </div>
    <script> window.print(); </script>
</body>
</html>
