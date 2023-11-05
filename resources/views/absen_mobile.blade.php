<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Absen SMK</title>
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="assets/mobile/img/favicon.png" sizes="32x32" />
    <link rel="apple-touch-icon" sizes="180x180" href="assets/mobile/img/icon/192x192.png" />
    <link rel="stylesheet" href="assets/mobile/css/inc/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/mobile/css/inc/owl-carousel/owl.carousel.min.css" />
    <link rel="stylesheet" href="assets/mobile/css/inc/owl-carousel/owl.theme.default.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:400,500,700&display=swap" />
    <link rel="stylesheet" href="assets/mobile/fontawesome-free/css/all.min.css" />
    <link rel="stylesheet" href="assets/mobile/css/style.css" />
    <style>
    .kamera,
    .kamera video {
        display: inline-block;
        width: 100% !important;
        margin: auto;
        max-height: 100vh;
        height: auto !important;
        border-radius: 15px;
    }
</style>
<style>
    #map {
        height: 120px;
        border-radius: 15px;
        }
</style>
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<!-- Font Awesome Icons -->
<link rel="stylesheet" href=" {{ asset('assets/plugins/fontawesome-free-6.4.2/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
</head>
<body>
    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <i class="fas fa-arrow-left fa-2x"></i>
            </a>
        </div>
        <div class="pageTitle">Absen SMK</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule">
        <div class="section full mt-2">
            <div class="section-title">
                Title
            </div>
            <div class="wide-block pt-2 pb-2">
                <div class="card-body">
                    <input type="hidden" id="lokasi">
                    <div class="kamera">
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="map">
                            </div>
                        </div>
                    </div>
            <div class="row mt-2">
                <div class="col">
                    @if($cek > 0)
                        <button id="ambilFoto" class="btn btn-danger btn-block">
                        <i class="fa-solid fa-camera-retro"></i> Absen Pulang</button>
                        @else
                        <button id="ambilFoto" class="btn btn-primary btn-block">
                        <i class="fa-solid fa-camera-retro"></i> Absen Masuk</button>
                    @endif
                    
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

    <!-- App Bottom Menu -->
    <div class="appBottomMenu">
        <a href="/home" class="item {{ request()->is('home') ? 'active' : '' }}">
            <div class="col">
                <i class="fas fa-home fa-3x text-dark"></i>
                <strong>Dashboard</strong>
            </div>
        </a>
        <a href="#" class="item  active">
            <div class="col">
                <i class="fas fa-calendar-alt fa-3x "></i>
                <strong>Calendar</strong>
            </div>
        </a>
        <a href="#" class="item">
            <div class="col">
                <div class="action-button large">
                    <i class="fas fa-camera text-white fa-3x"></i>
                </div>
            </div>
        </a>
        <a href="#" class="item">
            <div class="col">
                <i class="fas fa-file-alt fa-3x text-dark"></i>
                <strong>Docs</strong>
            </div>
        </a>
        <a href="javascript:;" class="item">
            <div class="col">
                <i class="fas fa-user-tie fa-3x text-dark"></i>
                <strong>Profile</strong>
            </div>
        </a>
    </div>
    <!-- * App Bottom Menu -->



    <!-- Jquery -->
    <script src="assets/mobile/js/lib/jquery-3.4.1.min.js"></script>
    <!-- Bootstrap-->
    <script src="assets/mobile/js/lib/popper.min.js"></script>
    <script src="assets/mobile/js/lib/bootstrap.min.js"></script>

    <!-- jQuery Circle Progress -->
    <script src="assets/mobile/js/plugins/jquery-circle-progress/circle-progress.min.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <!-- Base Js File -->
    <script src="assets/mobile/js/base.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
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


</body>

</html>