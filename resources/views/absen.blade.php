@extends('layouts.main')
@section('link')
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
    .kamera,
    .kamera video {
        display: inline-block;
        width: 100% !important;
        margin: auto;
        height: auto !important;
        border-radius: 15px;
    }
</style>
<style>
    #map {
        height: 620px;
        }
</style>
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Absensi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Admin</a></li>
            <li class="breadcrumb-item active">Absensi</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Bagian Kamera dan Tombol Absen -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Kamera Anda</h3>
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="lokasi">
                        <div class="kamera">
                            <!-- Isi dengan elemen kamera jika ada -->
                        </div>
                        <div class="row mt-3">
                        <div class="col">
                            @if($cek > 0)
                                @if($jam > Auth::user()->jam_pulang)
                                    <button id="ambilFoto" class="btn btn-danger btn-block">
                                        <i class="fa-solid fa-camera-retro"></i> Absen Pulang
                                    </button>
                                @else
                                    @if($jam < Auth::user()->jam_pulang)
                                        <button id="tombolpulang" class="btn btn-danger btn-block disabled">
                                            <i class="fa-solid fa-camera-retro"></i> Absen Pulang
                                        </button>
                                    @endif
                                @endif
                            @else
                            @if($jam > "22:00:00")
                                <button id="tombolmasuk" class="btn btn-primary btn-block disabled">
                                    <i class="fa-solid fa-camera-retro"></i> Absen Masuk
                                </button>
                                @else
                                <button id="ambilFoto" class="btn btn-primary btn-block">
                                    <i class="fa-solid fa-camera-retro"></i> Absen Masuk
                                </button>
                            @endif

                            @endif
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Peta -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Peta Lokasi</h3>
                    </div>
                    <div class="card-body">
                        <section id="map"></section>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>

</div>
<!-- /.content-wrapper -->
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 60
    });
    Webcam.attach( '.kamera' );

    var lokasi = document.getElementById('lokasi');
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(berhasil)
    }
    function berhasil(position){
        lokasi.value = position.coords.latitude + "," + position.coords.longitude;
        var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 16);
        var lokasi_absen_latitude = "{{ $setting->latitude }}"
        var lokasi_absen_longitude = "{{ $setting->longitude }}"
        var lokasi_absen_radius = "{{ $setting->radius }}"
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        var circle = L.circle([lokasi_absen_latitude, lokasi_absen_longitude], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.3,
        radius: lokasi_absen_radius
    }).addTo(map);
    }

    $("#ambilFoto").click(function(e){
        Webcam.snap(function(url){
            foto = url;
        });
        var lokasi = $("#lokasi").val();
        $.ajax({
            type:'POST',
            url:'/absenMasuk',
            data: {
                _token: "{{ csrf_token() }}",
                foto: foto,
                lokasi: lokasi
            },
            cache: false,
            success: function(respond){
                var status = respond.split("|");
                if(status[0]=="sukses"){
                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                            Toast.fire({
                            icon: 'success',
                            title: status[1]
                            })
                            setTimeout("location.href='/home'", 3000);
                } else {
                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                            Toast.fire({
                            icon: 'error',
                            title: status[1]
                            })
                }

            }
        });
    })
</script>
<script>
$("#tombolpulang").click(function() {
    var Toast = Swal.fire({
        title: "Opss..!!!",
        text: "Maaf Belum Waktunya Pulang ya...!!!",
        icon: "error"
    });
});
</script>
<script>
$("#tombolmasuk").click(function() {
    var Toast = Swal.fire({
        title: "Waduh..!!!",
        text: "Wis Lewat Absene gah balik bae langsung !!!",
        icon: "error"
    });
});
</script>
@endsection
