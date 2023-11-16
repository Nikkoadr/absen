@php
    use Illuminate\Support\Carbon;

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
    <title>Print Absen Individu SMK Muhammadiyah Kandanghaur</title>
</head>
        <div class="page">
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
            <table>
                <tr>
                    <td rowspan="7">
                    @if($user->pasfoto == null)
                    <img style="width: 130px" src="{{ asset('assets/dist/img/defaultpp.jpg') }}"/>
                    @else
                    <img style="width: 130px" src="{{ asset('storage/absen_file/pasFotoAbsen/'. $user ->pasfoto) }}"/>
                    @endif
                    </td>
                    <td>
                        <b style="font-size:14pt !important;">PRIODE</b>
                    </td>
                    <td>
                        <b style="font-size:14pt !important;">:</b>
                    </td>
                    <td>
                        <b style="font-size:14pt !important;">{{ Carbon::create()->month($bulan)->isoFormat('MMMM') }} {{ $tahun }}</b>
                    </td>
                </tr>
                <tr>

                    <td>
                        NIK
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $user->nik }}
                    </td>
                </tr>
                <tr>
                    <td>
                        NUPTK
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $user->nuptk }}
                    </td>
                </tr>
                <tr>
                    <td>
                        NBM
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $user->nbm }}
                    </td>
                </tr>
                <tr>
                    <td>
                        NAMA
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $user->nama }}
                    </td>
                </tr>
                <tr>
                    <td>
                        JABATAN
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $user->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td>
                        JAM KERJA
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $user->jam_kerja }}
                    </td>
                </tr>
            </table>
                <br>
        </div>
        <table width="100%" class="it-grid">
            <tr style="background:#fbff00">
                <td style="padding:10px" colspan="8" align="center"><b style="font-size:16pt !important;"> Rekap Bulan {{  Carbon::create()->month($bulan)->isoFormat('MMMM') }}</b></td>
            </tr>
            <tr>
                <td width="100px" align="center"><b style="font-size: 12px">NO</b></td>
                <td align="center"><b style="font-size: 12px">TANGGAL ABSEN</b></td>
                <td align="center"><b style="font-size: 12px">FOTO MASUK</b></td>
                <td align="center"><b style="font-size: 12px">JAM MASUK</b></td>
                <td align="center"><b style="font-size: 12px">FOTO PULANG</b></td>
                <td align="center"><b style="font-size: 12px">JAM PULANG</b></td>
                <td align="center"><b style="font-size: 12px">JUMALH JAM</b></td>
                <td align="center"><b style="font-size: 12px">KETERANGAN</b></td>
            </tr>
            @foreach ($rekap as $data)
            <tr>
                <td width="100px" align="center" width="250px">{{ $loop->iteration }}</td>
                <td align="center" width="250px">{{ Carbon::parse($data->tanggal_absen ?? $bulan . '-01')->format('d F Y') }}</td>
                <td align="center" width="250px"><img style="width: 60px" src="{{ asset('storage/absen_file/'. $data->foto_masuk) }}" alt="fotoMasuk"></td>
                <td align="center" width="250px">
                    <span @if($data->jam_masuk > $data->jam_kerja)
                        style="background: yellow"
                        @else
                        style="background: #00FF00"
                        @endif>{{ $data->jam_masuk }}</span></td>

                <td align="center" width="250px">
                    @if($data->foto_keluar == null)
                    Belum Absen Pulang
                    @else
                    <img style="width: 60px" src="{{ asset('storage/absen_file/'. $data->foto_keluar) }}" alt="fotoMasuk"></td>
                    @endif
                <td align="center" width="250px">
                    @if($data->jam_keluar == null)
                    00:00:00
                    @else
                    {{ $data->jam_keluar }}</td>
                    @endif
                </td>
                <td align="center" width="250px">
                    @if($data->jam_keluar != null)
                    {{ selisih($data->jam_keluar, $data->jam_masuk) }}
                    @else
                    0
                    @endif
                </td>
                <td align="center" width="250px">
                    @if($data->jam_masuk > $data->jam_kerja)
                    Terlambat {{ selisih($data->jam_masuk, $data->jam_kerja) }}
                    @else
                    Tepat Waktu
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        <b style="font-size: 20px; float: right; margin-top :10px">Total Absen Selam Satu Bulan: {{ count($rekap) }}</b>
    </div>
    <script> window.print(); </script>
</body>
</html>
