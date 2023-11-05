@extends('layouts.main_mobile')
@section('link')
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
                Kamera Absensi
            </div>
            <div class="wide-block pt-2 pb-2">
                <div class="card-body mb-5">
                    <input type="hidden" id="lokasi">
                    <div class="row">
                        <div class="col">
                            <p><b>Lokasi Anda Saat Ini : </b></p>
                            <div id="map">
                            </div>
                        </div>
                    </div>
                    <div class="kamera">
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
@endsection
</body>
</html>