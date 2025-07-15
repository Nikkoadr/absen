@extends('layouts.main')

@section('link')
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.20.0/dist/face-api.min.js"></script>

<style>
.kamera {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #ffffff;
    padding: 10px;
    border-radius: 15px;
}
.kamera video {
    width: 100% !important;
    height: auto !important;
    border-radius: 10px;
    transform: scaleX(-1);
}
.kamera canvas {
    position: absolute;
    top: 0;
    left: 0;
}
#map {
    height: 620px;
    border-radius: 15px;
}
</style>
@endsection

@section('content')
<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Absensi</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Admin</a></li>
                <li class="breadcrumb-item active">Absensi</li>
            </ol>
            </div>
        </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Kamera dan tombol absen -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Kamera Anda</h3>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="lokasi">
                            <div class="kamera mb-3">
                                <video id="video" autoplay muted playsinline></video>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    @if($cek > 0)
                                        @if($jam > Auth::user()->jam_pulang)
                                            <button id="ambilFoto" class="btn btn-danger btn-block">
                                                <i class="fa-solid fa-camera-retro"></i> Absen Pulang
                                            </button>
                                        @else
                                            <button id="tombolpulang" class="btn btn-danger btn-block disabled">
                                                <i class="fa-solid fa-camera-retro"></i> Absen Pulang
                                            </button>
                                        @endif
                                    @else
                                        @if($jam > $limit_absen)
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

                <!-- Peta -->
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
        </div>
    </div>

</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let faceDetected = false;

Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri('/models')
]).then(startVideo).catch(err => console.error(err));

function startVideo() {
    const video = document.getElementById('video');
    navigator.mediaDevices.getUserMedia({ video: {} })
        .then(stream => video.srcObject = stream)
        .catch(err => console.error("Camera error:", err));

    video.addEventListener('loadedmetadata', () => {
        video.play();

        const canvas = faceapi.createCanvasFromMedia(video);
        document.querySelector('.kamera').append(canvas);

        const displaySize = { width: video.offsetWidth, height: video.offsetHeight };
        faceapi.matchDimensions(canvas, displaySize);

        setInterval(async () => {
            const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.5 }));
            const resized = faceapi.resizeResults(detections, displaySize);

            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            ctx.save();
            ctx.scale(-1, 1);
            ctx.translate(-canvas.width, 0);

            resized.forEach(det => {
                const { x, y, width, height } = det.box;
                ctx.strokeStyle = "#00FF00";
                ctx.lineWidth = 2;
                ctx.strokeRect(x, y, width, height);

                const score = (det.score * 100).toFixed(2) + "%";
                ctx.font = "16px Arial";

                ctx.save();
                ctx.scale(-1, 1);
                ctx.fillStyle = "#00FF00";

                const textWidth = ctx.measureText(score).width;
                const textX = -(x + (width / 2) + (textWidth / 2));
                const textY = y + height + 20;

                ctx.fillText(score, textX, textY);
                ctx.restore();
            });

            ctx.restore();

            faceDetected = detections.length > 0;
        }, 1000);
    });
}

function takePhoto() {
    const video = document.getElementById('video');
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');

    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    return canvas.toDataURL('image/jpeg', 0.8);
}

$("#ambilFoto").click(function () {
    if (!faceDetected) {
        Swal.fire({
            title: "Opss..!!!",
            text: "Wajah belum terdeteksi, pastikan wajah terlihat jelas di kamera.",
            icon: "error"
        });
        return;
    }
    const foto = takePhoto();
    sendAbsenRequest(foto);
});

function sendAbsenRequest(foto) {
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
                Swal.fire({ title: "Terimakasih", text: status[1], icon: "success" });
                setTimeout(() => location.href = '/home', 2000);
            } else {
                Swal.fire({ title: "Opss..!!!", text: status[1], icon: "error" });
            }
        }
    });
}

var lokasi = document.getElementById('lokasi');
function initMap(latitude, longitude) {
    lokasi.value = latitude + "," + longitude;
    var map = L.map('map').setView([latitude, longitude], 16);
    var lokasi_absen_latitude = "{{ $setting->latitude }}";
    var lokasi_absen_longitude = "{{ $setting->longitude }}";
    var lokasi_absen_radius = "{{ $setting->radius }}";

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    L.marker([latitude, longitude]).addTo(map);
    L.circle([lokasi_absen_latitude, lokasi_absen_longitude], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.3,
        radius: lokasi_absen_radius
    }).addTo(map);
}

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        position => initMap(position.coords.latitude, position.coords.longitude),
        error => console.error('Error getting geolocation:', error),
        { timeout: 10000 }
    );
}

$("#tombolpulang").click(() => {
    Swal.fire({ title: "Opss..!!!", text: "Maaf Belum Waktunya Pulang ya !", icon: "error" });
});
$("#tombolmasuk").click(() => {
    Swal.fire({ title: "Maaf !", text: "Absen masuknya sudah tidak bisa karena terlalu siang", icon: "error" });
});
</script>
@endsection
