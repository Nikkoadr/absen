@extends('layouts.main')
@section('title')
{{'Profil Admin'}}
@endsection
@section('link')
<link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="konten-utama">
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1>Profil</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Profil Administrator</li>
        </ol>
        </div>
    </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
            <div class="text-center">
                @if (Auth::user()->pasfoto)
                <img class="profile-user-img img-fluid"
                    src="{{ asset('storage/absen_file/pasFotoAbsen/'. Auth::user()->pasfoto) }}" alt="Pas foto {{ Auth::user()->nama }}">
                @else
                <span class="avatar-inisial besar" role="img" aria-label="Belum ada pas foto">{{ strtoupper(mb_substr(trim(Auth::user()->nama ?? '?'), 0, 1)) }}</span>
                @endif
            </div>
            <h3 class="profile-username text-center">{{ Auth::user()->nama }}</h3>
            <p class="text-muted text-center">{{ Auth::user()->jabatan }}</p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- About Me Box -->
        <div class="card card-primary">
            <div class="card-header">
            <h3 class="card-title">Jam Kerja Hari Ini</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <strong><i class="fa-solid fa-image mr-1" aria-hidden="true"></i>Foto Masuk :
            @if ($absenHariIni != null )
                <img class="img-presensi" src="{{ asset('storage/absen_file/'. $absenHariIni->foto_masuk) }}" alt="Foto masuk hari ini">
            @else
                <p class="text-muted">
                    Belum Ada Foto
                </p>
            @endif</strong>
            <hr>
            <strong><i class="fa-solid fa-clock mr-1" aria-hidden="true"></i>Jam Masuk : {{ $absenHariIni != null ? $absenHariIni->jam_masuk : '00:00:00' }}</strong>
            <p class="text-muted">

            </p>
            <hr>
            <strong><i class="fa-solid fa-image mr-1" aria-hidden="true"></i> Foto Pulang :
            @if ($absenHariIni != null && $absenHariIni->jam_keluar != null)
                <img class="img-presensi" src="{{ asset('storage/absen_file/'. $absenHariIni->foto_keluar) }}" alt="Foto pulang hari ini">
            @else
                <p class="text-muted">
                    Belum Ada Foto Pulang
                </p>
            @endif</strong>
            <hr>
            <strong><i class="fa-solid fa-clock mr-1" aria-hidden="true"></i> Jam Pulang : {{ $absenHariIni != null && $absenHariIni->jam_keluar != null ? $absenHariIni->jam_keluar : '00:00:00' }}</strong>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#historyBulanIni" data-toggle="tab">Riwayat Bulan Ini</a></li>
                <li class="nav-item"><a class="nav-link" href="#data_diri" data-toggle="tab">Data diri</a></li>
                <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Kata Sandi</a></li>
                <li class="nav-item"><a class="nav-link" href="#dokumen" data-toggle="tab">Dokumen</a></li>
                
            </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="historyBulanIni">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    Tanggal Absen
                                </th>
                                <th>
                                    Foto Masuk
                                </th>
                                <th>
                                    Jam Masuk
                                </th>
                                <th>
                                    Foto Pulang
                                </th>
                                <th>
                                    Jam Pulang
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ( $historyBulanIni as $data )
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ Illuminate\Support\Carbon::parse($data->tanggal_absen)->format('d-M-Y'); }}</td>
                                <td><img class="img-presensi" src="{{ asset('storage/absen_file/'. $data->foto_masuk) }}" alt="Foto masuk {{ Illuminate\Support\Carbon::parse($data->tanggal_absen)->format('d-M-Y') }}"></td>
                                <td><span class="badge
                                    @if(($data->jam_kerja_hari ?? Auth::user()->jam_kerja) && $data->jam_masuk > ($data->jam_kerja_hari ?? Auth::user()->jam_kerja)) badge-warning @else badge-success @endif ">{{ $data->jam_masuk }}</span>
                                </td>
                                <td>
                                    @if ($data->foto_keluar == null)
                                        <small>Belum Foto Pulang</small>
                                    @else
                                        <img class="img-presensi" src="{{ asset('storage/absen_file/'. $data->foto_keluar) }}" alt="Foto pulang {{ Illuminate\Support\Carbon::parse($data->tanggal_absen)->format('d-M-Y') }}" />
                                    @endif
                                </td>
                                <td>
                                    @if($data->jam_keluar == null)
                                    00:00:00
                                    @else
                                    {{ $data->jam_keluar }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">Belum ada presensi bulan ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="data_diri">
                    <form action="{{ route('profile.update', Auth::user()->id) }}" method="POST">
                        @csrf
                        @method('put')
                        <p class="text-muted small">Tanda * wajib diisi.</p>
                        <div class="col">
                            <div class="row mb-3">
                                <label for="nik" class="col-sm-3 col-form-label text-md-end">NIK : </label>
                                <div class="col-sm-9">
                                    <input id="nik" type="number" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ Auth::user()->nik }}" autocomplete="nik" autofocus>
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
                                    <input id="nuptk" type="number" class="form-control @error('nuptk') is-invalid @enderror" name="nuptk" value="{{ Auth::user()->nuptk }}" autocomplete="nuptk" autofocus>
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
                                    <input id="nbm" type="number" class="form-control @error('nbm') is-invalid @enderror" name="nbm" value="{{ Auth::user()->nbm }}" autocomplete="nbm" autofocus>
                                    @error('nbm')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nama" class="col-sm-3 col-form-label text-md-end">Nama <span style="color: #b91c1c;" aria-hidden="true">*</span> : </label>
                                <div class="col-sm-9">
                                    <input id="nama" type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ Auth::user()->nama }}" autocomplete="nama" autofocus>
                                    @error('nama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nomor_hp" class="col-sm-3 col-form-label text-md-end">Nomor Hp <span style="color: #b91c1c;" aria-hidden="true">*</span> : </label>
                                <div class="col-sm-9">
                                    <input id="nomor_hp" type="text" class="form-control @error('nomor_hp') is-invalid @enderror" name="nomor_hp" value="{{ Auth::user()->nomor_hp }}" autocomplete="nomor_hp" autofocus>
                                    @error('nomor_hp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-3 col-form-label text-md-end">E-mail <span style="color: #b91c1c;" aria-hidden="true">*</span> : </label>
                                <div class="col-sm-9">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email }}" autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane" id="password">
                    <form action="{{ route('profile.password', Auth::user()->id) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="col">
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
                                    <input type="password" class="form-control @error('password-confirm') is-invalid @enderror" name="password_confirmation" id="password-confirm" required>
                                    @error('password-confirm')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane" id="dokumen">
                <div class="row">
                    <div class="col-md-6 mt-2">
                    <div class="card h-100">
                        <div class="card-header">Pas Foto</div>
                        <div class="card-body">
                            @if (Auth::user()->pasfoto)
                            <img style="max-width: 100%;" alt="Pas foto {{ Auth::user()->nama }}" src="{{ asset('storage/absen_file/pasFotoAbsen/'. Auth::user()->pasfoto) }}" class="mt-3">
                            @else
                            <span class="avatar-inisial besar" role="img" aria-label="Belum ada pas foto">{{ strtoupper(mb_substr(trim(Auth::user()->nama ?? '?'), 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="card-footer">
                            <form action="{{ route('profile.pasfoto', Auth::user()->id) }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <div class="form-group">
                                <label for="pas_foto">Unggah pas foto berbentuk kotak</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('pas_foto') is-invalid @enderror" id="pas_foto" name="pas_foto" accept="image/jpeg,image/png,image/jpg">
                                    <label class="custom-file-label" for="pas_foto">Pilih file</label>
                                    </div>
                                    <div class="input-group-append">
                                    <button type="submit" class="input-group-text">Upload</button>
                                    </div>
                                </div>
                                @error('pas_foto')
                                    <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                    <div class="card h-100">
                        <div class="card-header">Wajah untuk Presensi Mandiri</div>
                        <div class="card-body text-center">
                            <p class="text-muted">Ambil foto wajah langsung dari kamera, lalu daftarkan.</p>
                            <video id="videoWajahDesktop" autoplay muted playsinline aria-label="Pratinjau kamera untuk pendaftaran wajah" style="width: 100%; max-width: 360px; border-radius: 12px; transform: scaleX(-1); background: #000;"></video>
                            <div class="mt-2"><span class="badge badge-secondary" id="statusWajahDesktop" role="status" aria-live="polite">Kamera belum aktif</span></div>
                            <div class="mt-2">
                                <button id="btnKameraWajahDesktop" type="button" class="btn btn-secondary">Aktifkan Kamera</button>
                                <button id="btnDaftarWajahDesktop" type="button" class="btn btn-primary" disabled>Daftarkan Wajah</button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
@endsection
@section('script')
<script src="assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.20.0/dist/face-api.min.js"></script>
<script>
document.getElementById('btnKameraWajahDesktop')?.addEventListener('click', async () => {
    const status = document.getElementById('statusWajahDesktop');
    const video = document.getElementById('videoWajahDesktop');
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
        document.getElementById('btnDaftarWajahDesktop').disabled = false;
    } catch (e) { console.error(e); status.textContent = 'Kamera/model gagal dimuat.'; }
});
document.getElementById('btnDaftarWajahDesktop')?.addEventListener('click', async () => {
    const status = document.getElementById('statusWajahDesktop');
    const video = document.getElementById('videoWajahDesktop');
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
$(function() {
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
});
@endif
</script>
<script>
$(function () {
bsCustomFileInput.init();
});
</script>
@endsection