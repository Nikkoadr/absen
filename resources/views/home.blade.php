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
        height: 180px;
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

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
            <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h3 class="card-title">Absen Masuk</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                    <input type="hidden" id="lokasi">
                    <div class="kamera">
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="map"></div>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col">
                                <button id="ambilFoto" class="btn btn-primary btn-block">
                                    <i class="fa-solid fa-camera-retro"></i> Absen Masuk</button>
                            </div>
                        </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
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
        navigator.geolocation.getCurrentPosition(berhasil, gagal)
    }
    function berhasil(position){
        lokasi.value = position.coords.latitude + "," + position.coords.longitude;
        var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 16);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        var circle = L.circle([-6.363041, 108.113627], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.3,
        radius: 70
    }).addTo(map);
    }

    function gagal(){
    
    }
    $("#ambilFoto").click(function(e){
        Webcam.snap(function(url){
            image = url;
        });
        var lokasi = $("#lokasi").val();
        $.ajax({
            type:'POST',
            url:'/absenMasuk',
            data: {
                _token: "{{ csrf_token() }}",
                image: image,
                lokasi: lokasi
            },
            cache: false,
            success: function(respond){

            }
        });
    })
</script>
@endsection
