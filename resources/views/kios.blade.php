<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta name="theme-color" content="#0ea5e9" />
    <title>Presensi Mandiri Tanpa Login</title>
    <link rel="stylesheet" href="{{ asset('assets/mobile/css/inc/bootstrap/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:400,500,700&display=swap" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free-6.4.2/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/mobile/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/mobile/css/sky.css') }}" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <style>
        .kamera { position: relative; display: flex; justify-content: center; background: #fff; padding: 10px; border-radius: 18px; max-width: 420px; margin: auto; }
        .kamera video { width: 100%; border-radius: 12px; transform: scaleX(-1); }
        .kamera canvas { position: absolute; top: 10px; left: 10px; }
        #map { height: 160px; border-radius: 16px; }
        #namaTerdeteksi { font-weight: 700; color: #0369a1; }
    </style>
</head>
<body>
<div id="loader"><div class="spinner-border text-primary" role="status"></div></div>
<div class="sky-header text-center">
    <h1>Presensi Mandiri</h1>
    <p>Arahkan wajah ke kamera — tanpa perlu login</p>
</div>

<div class="container mt-3" style="margin-bottom: 40px">
    <div class="sky-card p-3">
        <div class="text-center mb-2"><span class="chip-sky" id="statusModel">Memuat model AI…</span></div>
        <div class="kamera mb-2"><video id="video" autoplay muted playsinline></video></div>
        <div class="text-center mb-2">
            <img id="fotoTerdeteksi" src="" alt="" style="display: none; width: 72px; height: 72px; object-fit: cover; border-radius: 50%; border: 3px solid #7dd3fc;">
            <div>Terdeteksi: <strong id="namaTerdeteksi">—</strong> <span class="chip-sky" id="skorTerdeteksi"></span></div>
        </div>
        <input type="hidden" id="lokasi">
        <input type="hidden" id="userId">
        <input type="hidden" id="skor">
        <button id="btnAbsen" class="btn btn-sky btn-block" disabled>Ambil Presensi</button>
        <div class="text-center mt-2"><a href="{{ route('login') }}">Masuk dengan akun</a></div>
    </div>

    <div class="sky-card p-3 mt-3">
        <h5 class="text-center">Lokasi Anda</h5>
        <div id="map"></div>
    </div>
</div>

<script src="{{ asset('assets/mobile/js/lib/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('assets/mobile/js/lib/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/mobile/js/base.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.20.0/dist/face-api.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const statusEl = document.getElementById('statusModel');
const namaEl = document.getElementById('namaTerdeteksi');
const skorEl = document.getElementById('skorTerdeteksi');
const btn = document.getElementById('btnAbsen');
const fotoEl = document.getElementById('fotoTerdeteksi');

function tampilkanOrang(profil, jarak) {
    const nilai = Number(jarak).toFixed(2);

    if (!profil) {
        namaEl.textContent = 'Tidak dikenal';
        skorEl.textContent = nilai;
        fotoEl.style.display = 'none';
        btn.disabled = true;
        document.getElementById('userId').value = '';
        return;
    }

    namaEl.textContent = profil.nama + ' (ID ' + profil.id + ')';
    skorEl.textContent = nilai;

    if (profil.foto) {
        fotoEl.src = profil.foto;
        fotoEl.style.display = 'inline-block';
    } else {
        fotoEl.style.display = 'none';
    }

    document.getElementById('userId').value = profil.id;
    document.getElementById('skor').value = jarak;
    btn.disabled = false;
}
let matcher = null;
const profilWajah = {};

Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
    faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
]).then(boot).catch(e => { statusEl.textContent = 'Gagal memuat model AI'; console.error(e); });

async function boot() {
    const res = await fetch('/api/deskriptor-wajah');
    const daftar = await res.json();
    if (!daftar.length) {
        statusEl.textContent = 'Belum ada data wajah terdaftar. Login lalu daftarkan wajah di Profil.';
        return;
    }
    matcher = new faceapi.FaceMatcher(
        daftar.map(d => {
            profilWajah[String(d.id)] = d;
            return new faceapi.LabeledFaceDescriptors(String(d.id), [new Float32Array(d.descriptor)]);
        }),
        0.6
    );
    statusEl.textContent = daftar.length + ' wajah terdaftar. Arahkan wajah ke kamera.';
    startVideo();
}

function startVideo() {
    const video = document.getElementById('video');
    navigator.mediaDevices.getUserMedia({ video: {} }).then(s => video.srcObject = s)
        .catch(e => { statusEl.textContent = 'Kamera tidak dapat diakses'; console.error(e); });
    video.addEventListener('loadedmetadata', () => {
        video.play();
        const canvas = faceapi.createCanvasFromMedia(video);
        document.querySelector('.kamera').append(canvas);
        const size = { width: video.offsetWidth, height: video.offsetHeight };
        faceapi.matchDimensions(canvas, size);
        setInterval(async () => {
            const det = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.5 }))
                .withFaceLandmarks().withFaceDescriptor();
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            if (!det) {
                namaEl.textContent = '—'; skorEl.textContent = ''; btn.disabled = true;
                document.getElementById('userId').value = '';
                return;
            }
            const r = faceapi.resizeResults(det, size);
            const hasil = matcher.findBestMatch(r.descriptor);
            const profil = hasil.label === 'unknown' ? null : (profilWajah[hasil.label] || null);
            new faceapi.draw.DrawBox(r.detection.box, { label: profil ? profil.nama : 'wajah' }).draw(canvas);
            if (!profil) {
                tampilkanOrang(null, hasil.distance);
                return;
            }
            tampilkanOrang(profil, hasil.distance);
        }, 1200);
    });
}

btn.addEventListener('click', async () => {
    const nama = namaEl.textContent;
    const konfirmasi = await Swal.fire({
        title: 'Konfirmasi Presensi',
        text: 'Catat presensi untuk ' + nama + '?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Catat',
        cancelButtonText: 'Batal',
    });
    if (!konfirmasi.isConfirmed) return;
    const video = document.getElementById('video');
    const c = document.createElement('canvas');
    c.width = video.videoWidth; c.height = video.videoHeight;
    const x = c.getContext('2d');
    x.translate(c.width, 0); x.scale(-1, 1);
    x.drawImage(video, 0, 0, c.width, c.height);
    const foto = c.toDataURL('image/jpeg', 0.8);
    const payload = new URLSearchParams({
        _token: '{{ csrf_token() }}',
        user_id: document.getElementById('userId').value,
        lokasi: document.getElementById('lokasi').value,
        foto: foto,
        skor: document.getElementById('skor').value || '0',
    });
    try {
        const r = await fetch('/presensi-mandiri', { method: 'POST', headers: { 'Accept': 'application/json' }, body: payload });
        const j = await r.json();
        Swal.fire({ title: j.status === 'sukses' ? 'Terima kasih' : 'Opss', text: j.message, icon: j.status === 'sukses' ? 'success' : 'error' });
    } catch (e) {
        Swal.fire({ title: 'Opss', text: 'Terjadi kesalahan. Coba lagi.', icon: 'error' });
    }
});

function initMap(lat, lon) {
    document.getElementById('lokasi').value = lat + ',' + lon;
    const map = L.map('map').setView([lat, lon], 16);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
    L.marker([lat, lon]).addTo(map);
    @if($setting)
    L.circle([{{ $setting->latitude }}, {{ $setting->longitude }}], { color: 'red', fillOpacity: 0.3, radius: {{ (int) $setting->radius }} }).addTo(map);
    @endif
}
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(p => initMap(p.coords.latitude, p.coords.longitude), e => console.error(e), { timeout: 10000 });
}
</script>
</body>
</html>
