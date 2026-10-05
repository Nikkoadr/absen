@extends('layouts.main_mobile')

@section('link')
<style>
    #map {
        margin-bottom: 10px;
        height: 150px;
        border-radius: 15px;
    }
    .btn-absen {
        font-size: 16px;
        font-weight: 600;
        padding: 12px;
        border-radius: 12px;
        min-height: 52px;
    }
    .btn-danger { background-color: #b91c1c; border: none; } /* Solid: putih di atasnya 6.47:1, tidak menyaingi tombol masuk. */
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endsection

@section('content')
<div id="appCapsule">
    <div class="sky-header text-center mb-2">
        <h1 style="font-size: 20px;">Ambil Presensi</h1>
        <p>Pastikan wajah terlihat dan lokasi di area sekolah</p>
    </div>
    <div class="section full mt-2">
        <div class="wide-block pt-2 pb-2">
            <h2 class="text-center mb-3" style="font-size: 18px;">Kamera</h2>
            <div class="kamera mb-3">
                <video id="video" autoplay muted playsinline aria-label="Pratinjau kamera untuk verifikasi wajah"></video>
            </div>
            <p id="statusKamera" role="status" class="text-muted text-center mb-0">Memuat kamera…</p>

            <div class="row mt-2">
                <div class="col">
                    @if($cek > 0)
                        @if($jam > Auth::user()->jam_pulang)
                            <button id="ambilFoto" class="btn btn-danger btn-block btn-absen">
                                <i class="fa-solid fa-camera-retro"></i> Presensi Pulang
                            </button>
                        @else
                            <button id="tombolpulang" class="btn btn-danger btn-block btn-absen">
                                <i class="fa-solid fa-camera-retro"></i> Presensi Pulang
                            </button>
                        @endif
                    @else
                        @if($jam > $limit_absen)
                            <button id="tombolmasuk" class="btn btn-primary btn-block btn-absen">
                                <i class="fa-solid fa-camera-retro"></i> Presensi Masuk
                            </button>
                        @elseif($jam < '06:00:00')
                            <button id="mulai_absen" class="btn btn-primary btn-block btn-absen">
                                <i class="fa-solid fa-camera-retro"></i> Presensi Masuk
                            </button>
                        @else
                            <button id="ambilFoto" class="btn btn-primary btn-block btn-absen">
                                <i class="fa-solid fa-camera-retro"></i> Presensi Masuk
                            </button>
                        @endif
                    @endif
                </div>
            </div>

            <div class="card-body mb-5">
                <input type="hidden" id="lokasi">
                <h2 class="text-center mb-3 mt-4" style="font-size: 18px;">Lokasi</h2>
                <div id="map" role="img" aria-label="Peta lokasi Anda dan radius area sekolah"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.20.0/dist/face-api.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let faceDetected = false;

Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri('/models')
]).then(startVideo).catch(err => console.error(err));

function startVideo() {
    const video = document.getElementById('video');
    const status = document.getElementById('statusKamera');
    navigator.mediaDevices.getUserMedia({ video: {} })
        .then(stream => video.srcObject = stream)
        .catch(err => {
            console.error("Camera error:", err);
            if (status) status.textContent = 'Kamera tidak dapat diakses. Periksa izin kamera browser.';
        });

    video.addEventListener('loadedmetadata', () => {
        video.play();
        if (status) status.textContent = 'Arahkan wajah ke kamera hingga bingkai hijau muncul.';

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
    ctx.scale(-1, 1); // Foto dibalik agar tidak mirror.
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    return canvas.toDataURL('image/jpeg', 0.8);
}

$("#ambilFoto").click(function () {
    if (!faceDetected) {
        Swal.fire({
            title: "Gagal",
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
            var status, message;
            if (typeof respond === 'string') {
                var parts = respond.split("|");
                status = parts[0];
                message = parts[1] || respond;
            } else {
                status = respond.status;
                message = respond.message;
            }
            if (status == "sukses") {
                Swal.fire({ title: "Berhasil", text: message, icon: "success" });
                setTimeout(() => location.href = '/home', 2000);
            } else {
                Swal.fire({ title: "Gagal", text: message, icon: "error" });
            }
        },
        error: function (xhr) {
            var message = 'Terjadi kesalahan. Coba lagi.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                message = Object.values(xhr.responseJSON.errors).flat().join(' ');
            }
            Presensi.galat(message);
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
        error => {
            console.error('Error getting geolocation:', error);
            document.getElementById('map').innerHTML = '<p class="text-muted text-center p-3 mb-0">Lokasi tidak dapat dibaca. Aktifkan GPS lalu muat ulang.</p>';
        },
        { timeout: 10000 }
    );
}

$("#tombolpulang").click(() => {
    Presensi.galat("Belum waktunya pulang.");
});
$("#tombolmasuk").click(() => {
    Presensi.galat("Presensi masuk sudah ditutup karena terlalu siang.");
});
</script>
@endsection
