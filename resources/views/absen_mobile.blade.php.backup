@extends('layouts.main_mobile')
@section('link')
    <style>
.kamera,
.kamera video {
    display: inline-block;
    width: 50% !important;
    margin: auto;
    height: auto !important;
    border-radius: 15px;
}

</style>
<style>
    #map {
        margin-bottom: 10px;
        height: 120px;
        border-radius: 15px;
        }
</style>
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endsection
@section('content')
    <!-- App Capsule -->
    <div id="appCapsule">
        <div class="section full mt-2">
            <div class="section-title">
                <span>Menu Absensi</span>
            </div>
            <div class="wide-block pt-2 pb-2">
                    <h5 class="text-center mb-3">Kamera Anda</h5>
                    <div class="kamera">
                    </div>
                    <div class="row mt-2">
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
                            @if($jam > $limit_absen)
                                <button id="tombolmasuk" class="btn btn-primary btn-block disabled">
                                    <i class="fa-solid fa-camera-retro"></i> Absen Masuk
                                </button>
                            @elseif($jam < '06:00:00')
                                <button id="mulai_absen" class="btn btn-primary btn-block disabled">
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
                <div class="card-body mb-5">
                    <input type="hidden" id="lokasi">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-center mb-3">Lokasi Anda</h5>
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    Webcam.set({
        width: 320,
        height: 240,
        flip_horiz: true,
        image_format: 'jpeg',
        jpeg_quality: 60
    });
    Webcam.attach( '.kamera' );

    var lokasi = document.getElementById('lokasi');
    function initMap(latitude, longitude) {
    lokasi.value = latitude + "," + longitude;
        var map = L.map('map').setView([latitude, longitude], 16);
        var lokasi_absen_latitude = "{{ $setting->latitude }}"
        var lokasi_absen_longitude = "{{ $setting->longitude }}"
        var lokasi_absen_radius = "{{ $setting->radius }}"
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        var marker = L.marker([latitude, longitude]).addTo(map);
        var circle = L.circle([lokasi_absen_latitude, lokasi_absen_longitude], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.3,
        radius: lokasi_absen_radius
    }).addTo(map);
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                initMap(position.coords.latitude, position.coords.longitude);
            },
            function (error) {
                console.error('Error getting geolocation:', error);
            },
            { timeout: 10000 }
        );
    }

    $("#ambilFoto").click(function (e) {
        Webcam.snap(function (url) {
            foto = url;
            sendAbsenRequest();
        });
    });

    function sendAbsenRequest() {
        var lokasi = $("#lokasi").val();
        $.ajax({
            type: 'POST',
            url: '/absenMasuk',
            data: {
                _token: "{{ csrf_token() }}",
                foto: foto,
                lokasi: lokasi
            },
            cache: false,
            success: function (respond) {
                var status = respond.split("|");
                if (status[0] == "sukses") {
                    var Toast = Swal.fire({
                        title: "Terimakasih",
                        text: status[1],
                        icon: "success"
                    });
                    setTimeout(function () {
                        location.href = '/home';
                    }, 2000);
                } else {
                    var Toast = Swal.fire({
                        title: "Opss..!!!",
                        text: status[1],
                        icon: "error"
                    });
                }
            }
        });
    }
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
        text: "Sudah melebihi waktunya absen masuk...!!!",
        icon: "error"
    });
});
</script>
<script>
$("#mulai_absen").click(function() {
    var Toast = Swal.fire({
        title: "Waduh..!!!",
        text: "Maaf, Waktu mulai absensi jam 06.00 WIB...!!!",
        icon: "error"
    });
});
</script>
@endsection
</body>
</html>