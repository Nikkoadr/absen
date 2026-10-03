@extends('layouts.main_mobile')
@section('link')

@endsection
@section('content')
<div id="appCapsule">
<div class="sky-header text-center mb-2">
    <h1 style="font-size: 20px;">Profil Saya</h1>
    <p>{{ Auth::user()->nama }} — {{ ucfirst(Auth::user()->role) }}</p>
</div>
<div class="section mt-2">
    <div class="sky-card mb-2">
        <div class="card-header">Data Diri</div>
        <div class="card-body">
            <form action="{{ route('profile.update', Auth::user()->id) }}" method="POST">
                @csrf
                @method('put')
                <div class="row mb-3">
                    <label for="nik" class="col-sm-3 col-form-label text-md-end">NIK : </label>
                    <div class="col-sm-9">
                        <input id="nik" type="number" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ Auth::user()->nik }}" autocomplete="nik">
                        @error('nik')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="nuptk" class="col-sm-3 col-form-label text-md-end">NUPTK : </label>
                    <div class="col-sm-9">
                        <input id="nuptk" type="number" class="form-control @error('nuptk') is-invalid @enderror" name="nuptk" value="{{ Auth::user()->nuptk }}" autocomplete="nuptk">
                        @error('nuptk')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="nbm" class="col-sm-3 col-form-label text-md-end">NBM : </label>
                    <div class="col-sm-9">
                        <input id="nbm" type="number" class="form-control @error('nbm') is-invalid @enderror" name="nbm" value="{{ Auth::user()->nbm }}" autocomplete="nbm">
                        @error('nbm')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="nama" class="col-sm-3 col-form-label text-md-end">Nama <span style="color: red">*</span> : </label>
                    <div class="col-sm-9">
                        <input id="nama" type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ Auth::user()->nama }}" autocomplete="nama">
                        @error('nama')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="nomor_hp" class="col-sm-3 col-form-label text-md-end">Nomor HP : </label>
                    <div class="col-sm-9">
                        <input id="nomor_hp" type="text" class="form-control @error('nomor_hp') is-invalid @enderror" name="nomor_hp" value="{{ Auth::user()->nomor_hp }}" autocomplete="nomor_hp">
                        @error('nomor_hp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="email" class="col-sm-3 col-form-label text-md-end">E-mail <span style="color: red">*</span> : </label>
                    <div class="col-sm-9">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email }}" autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Simpan Data Diri</button>
            </form>
        </div>
    </div>

    <div class="sky-card mb-2">
        <div class="card-header">Perubahan Kata Sandi</div>
        <div class="card-body">
            <form action="{{ route('profile.password', Auth::user()->id) }}" method="POST">
                @csrf
                @method('put')
                <div class="row mb-3">
                    <label for="password" class="col-sm-3 col-form-label text-md-end">Kata Sandi Baru : </label>
                    <div class="col-sm-9">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="password-confirm" class="col-sm-3 col-form-label">Konfirmasi Kata Sandi :</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" name="password_confirmation" id="password-confirm" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Ubah Kata Sandi</button>
            </form>
        </div>
    </div>

    <div class="sky-card mb-2">
        <div class="card-header">Dokumen — Pas Foto</div>
        <div class="card-body text-center">
            @if (Auth::user()->pasfoto)
            <img style="max-width: 50%;" class="rounded mx-auto d-block" src="{{ asset('storage/absen_file/pasFotoAbsen/'. Auth::user()->pasfoto) }}">
            @else
            <img style="max-width: 50%;" class="rounded mx-auto d-block" src="{{ asset('assets/dist/img/logo.png') }}">
            @endif
            <form action="{{ route('profile.pasfoto', Auth::user()->id) }}" method="POST" enctype="multipart/form-data" class="form-horizontal mt-2">
                @csrf
                @method('put')
                <div class="form-group">
                    <label>Upload Pas Foto <br><small>Note : Gunakan Gambar yang berukuran kotak</small></label>
                    <label for="pas_foto" class="btn btn-outline-primary btn-block">Pilih Foto</label>
                    <input type="file" id="pas_foto" name="pas_foto" accept="image/*" style="display: none;">
                    <div id="namaFileDipilih" class="text-muted small text-center"></div>
                    @error('pas_foto')
                        <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-block">Upload Pas Foto</button>
            </form>
        </div>
    </div>

    <div class="sky-card mb-2">
        <div class="card-header">Dokumen — Wajah Presensi Mandiri</div>
        <div class="card-body text-center">
            <p class="text-muted">Ambil foto wajah langsung dari kamera, lalu daftarkan.</p>
            <video id="videoWajah" autoplay muted playsinline style="width: 100%; max-width: 320px; min-height: 240px; border-radius: 12px; transform: scaleX(-1); background: #0f172a;"></video>
            <canvas id="kanvasWajah" style="display: none;"></canvas>
            <div class="text-center mt-2"><span class="chip-sky" id="statusWajah">Kamera belum aktif</span></div>
            <button id="btnKameraWajah" type="button" class="btn btn-secondary btn-block btn-lg mt-2">Aktifkan Kamera</button>
            <button id="btnDaftarWajah" type="button" class="btn btn-sky btn-block btn-lg mt-2" disabled>Daftarkan Wajah</button>
        </div>
    </div>

    <div class="sky-card mb-2">
        <div class="card-header">Riwayat Terakhir</div>
        <ul class="listview image-listview flush">
            @forelse ($riwayatTerakhir ?? [] as $data)
                <li>
                    <div class="item">
                        <div class="icon-box bg-primary"><i class="fas fa-fingerprint"></i></div>
                        <div class="in">
                            <div>{{ Illuminate\Support\Carbon::parse($data->tanggal_absen)->format('d-M-Y') }}</div>
                            <span class="badge badge-success">{{ $data->jam_masuk }}</span>
                            <span class="badge badge-danger">{{ $data->jam_keluar ?? '00:00:00' }}</span>
                        </div>
                    </div>
                </li>
            @empty
                <li><div class="item"><div class="in"><div class="text-muted">Belum ada presensi.</div></div></div></li>
            @endforelse
        </ul>
        <div class="card-footer text-center">
            <a href="/history">Lihat Semua Riwayat</a>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script src="assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.20.0/dist/face-api.min.js"></script>
<script>
$(function () {
    bsCustomFileInput.init();
    document.getElementById('pas_foto')?.addEventListener('change', function () {
        document.getElementById('namaFileDipilih').textContent = this.files.length ? this.files[0].name : '';
    });
});
document.getElementById('btnKameraWajah')?.addEventListener('click', async () => {
    const status = document.getElementById('statusWajah');
    const video = document.getElementById('videoWajah');
    try {
        status.textContent = 'Memuat model AI…';
        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
        ]);
        const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
        video.srcObject = stream;
        await video.play();
        status.textContent = 'Arahkan wajah ke kamera lalu tekan Daftarkan Wajah.';
        document.getElementById('btnDaftarWajah').disabled = false;
    } catch (e) { console.error(e); status.textContent = 'Kamera/model gagal dimuat.'; }
});
document.getElementById('btnDaftarWajah')?.addEventListener('click', async () => {
    const status = document.getElementById('statusWajah');
    const video = document.getElementById('videoWajah');
    try {
        status.textContent = 'Mengenali wajah…';
        const det = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 512, scoreThreshold: 0.4 })).withFaceLandmarks().withFaceDescriptor();
        if (!det) { status.textContent = 'Wajah tidak ditemukan. Arahkan wajah ke kamera.'; return; }
        const res = await fetch("{{ route('profile.wajah', Auth::user()->id) }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ descriptor: Array.from(det.descriptor) }),
        });
        const j = await res.json();
        status.textContent = j.message || 'Berhasil';
        if (video.srcObject) video.srcObject.getTracks().forEach(t => t.stop());
    } catch (e) { console.error(e); status.textContent = 'Gagal mendaftarkan wajah.'; }
});
</script>
<script>
@if (session()->has('success'))
var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
    Toast.fire({
    icon: 'success',
    title: '{{ session('success') }}'
    })
@endif
</script>
@endsection
</body>
</html>
