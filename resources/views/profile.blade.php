@extends('layouts.main')
@section('title')
{{'Profile Admin'}}
@endsection
@section('link')
<link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1>Profile</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Profile Administrator</li>
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
                    src="{{ asset('storage/absen_file/pasFotoAbsen/'. Auth::user()->pasfoto) }}" alt="User profile picture">
                @else
                <img class="profile-user-img img-fluid img-circle"
                    src="{{ asset('assets/dist/img/defaultpp.jpg') }}" alt="User profile picture">
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
            <strong><i class="fa-solid fa-image mr-1"></i></i>Foto Masuk : 
            @if ($absenHariIni != null )
                <img style="width: 60px" src="{{ asset('storage/absen_file/'. $absenHariIni->foto_masuk) }}">
            @else
                <p class="text-muted">
                    Belum Ada Foto
                </p>
            @endif</strong>
            <hr>
            <strong><i class="fa-solid fa-clock mr-1"></i></i>Jam Masuk : {{ $absenHariIni != null ? $absenHariIni->jam_masuk : '00:00:00' }}</strong>
            <p class="text-muted">
                
            </p>
            <hr>
            <strong><i class="fa-solid fa-image mr-1"></i></i> Foto Pulang :
            @if ($absenHariIni != null && $absenHariIni->jam_keluar != null)
                <img style="width: 60px" src="{{ asset('storage/absen_file/'. $absenHariIni->foto_keluar) }}">
            @else
                <p class="text-muted">
                    Belum Ada Foto Pulang
                </p>
            @endif</strong>
            <hr>
            <strong><i class="fa-solid fa-clock mr-1"></i></i> Jam Pulang : {{ $absenHariIni != null && $absenHariIni->jam_keluar != null ? $absenHariIni->jam_keluar : '00:00:00' }}</strong>
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
                <li class="nav-item"><a class="nav-link active" href="#historyBulanIni" data-toggle="tab">History Bulan Ini</a></li>
                <li class="nav-item"><a class="nav-link" href="#data_diri" data-toggle="tab">Data diri</a></li>
                <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Password</a></li>
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
                                    jam Masuk
                                </th>
                                <th>
                                    Foto Pulang
                                </th>
                                <th>
                                    jam Pulang
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $historyBulanIni as $data )
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ Illuminate\Support\Carbon::parse($data->tanggal_absen)->format('d-M-Y'); }}</td>
                                <td><img style="width: 60px" src="{{ asset('storage/absen_file/'. $data->foto_masuk) }}"></td>
                                <td><span class="badge 
                                    @if($data->jam_masuk > Auth::user()->jam_kerja) badge-warning @else badge-success @endif ">{{ $data->jam_masuk }}</span>
                                </td>
                                <td>
                                    @if ($data->foto_keluar == null)
                                        <small>Belum Foto Pulang</small>
                                    @else
                                        <img style=" width: 60px" src="{{ asset('storage/absen_file/'. $data->foto_keluar) }}" alt="image" class="image" />
                                    @endif
                                </td>
                                <td>
                                    <span>
                                    @if($data->jam_keluar == null)
                                    00:00:00
                                    @else
                                    {{ $data->jam_keluar }}
                                    </span>
                                </td>
                                    @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="data_diri">
                    <form action="edit/profile_id{{ Auth::user()->id }}" method="POST">
                        @csrf
                        @method('put')
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
                                <label for="nama" class="col-sm-3 col-form-label text-md-end">Nama <span style="color: red">*</span> : </label>
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
                            <button type="submit" class="btn btn-primary float-right">
                                Update
                            </button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane" id="password">
                    <form action="edit/password_user_id{{ Auth::user()->id }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="col">
                            <div class="row mb-3">
                                <label for="email" class="col-sm-3 col-form-label text-md-end">Password : </label>
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
                                <label for="password-confirm" class="col-sm-3 col-form-label">Konfirmasi Password :</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control @error('password-confirm') is-invalid @enderror" name="password_confirmation" id="password-confirm" required>
                                    @error('password-confirm')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">
                                Update
                            </button>
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
                            <img style="max-width: 100%;"  src="{{ asset('storage/absen_file/pasFotoAbsen/'. Auth::user()->pasfoto) }}" class="mt-3">
                            @else
                            <img style="max-width: 100%;"  src="{{ asset('assets/dist/img/logo.png') }}" class="mt-3">
                            @endif
                        </div>
                        <div class="card-footer">
                            <form action="upload_pasfoto_id{{ Auth::user()->id }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <div class="form-group">
                                <label for="pas_foto">Upload Pas Foto</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('pas_foto') is-invalid @enderror" id="pas_foto" name="pas_foto">
                                    <label class="custom-file-label" for="pasfoto">Pilih file</label>
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