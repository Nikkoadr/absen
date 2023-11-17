@extends('layouts.main_mobile')
@section('link')

@endsection
@section('content')
            <div class="presencetab mt-2">
                <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                    <ul class="nav nav-tabs style1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#dataDiri" role="tab">
                                Data Diri
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#password" role="tab">
                                Password
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#foto" role="tab">
                                Foto
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content mt-2" style="margin-bottom: 100px">
                    <div class="tab-pane fade show active" id="dataDiri" role="tabpanel">
                        <div class="section mt-3 mb-5">
                            <div class="card">
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
                                            <div style="margin-bottom: 50px" class="form-group boxed">
                                                <div class="input-wrapper">
                                                    <button type="submit" class="btn btn-primary btn-block">
                                                        <ion-icon name="refresh-outline"></ion-icon>
                                                        Update
                                                    </button>
                                                </div>
                                            </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="password" role="tabpanel">
                        <div class="section mt-3 mb-5">
                            <div class="card">
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
                                            <div class="form-group boxed">
                                                <div class="input-wrapper">
                                                    <button type="submit" class="btn btn-primary btn-block">
                                                        Update
                                                    </button>
                                                </div>
                                            </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="foto" role="tabpanel">
                        <div class="section mt-3 mb-5">
                    <div class="card">
                        <div class="card-header">Pas Foto</div>
                        <div class="card-body">
                            @if (Auth::user()->pasfoto)
                            <img style="max-width: 50%;" class="rounded mx-auto d-block" src="{{ asset('storage/absen_file/pasFotoAbsen/'. Auth::user()->pasfoto) }}">
                            @else
                            <img style="max-width: 50%;" class="rounded mx-auto d-block" src="{{ asset('assets/dist/img/logo.png') }}">
                            @endif
                        </div>
                        <div class="card-footer">
                            <form action="upload_pasfoto_id{{ Auth::user()->id }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <div class="form-group">
                                <label for="pas_foto">Upload Pas Foto <br><small>Note : Gunakan Gambar yang berukuran kotak</small> </label>
                                <div class="input-group">
                                    <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('pasfoto') is-invalid @enderror" id="pas_foto" name="pas_foto">
                                    <label class="custom-file-label" for="pas_foto">Pilih file</label>
                                </div>
                                </div>
                                @error('pas_foto')
                                    <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                                    <div class="form-group boxed">
                                        <div class="input-wrapper">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <ion-icon name="refresh-outline"></ion-icon>
                                                Update
                                            </button>
                                        </div>
                                    </div>
                            </div>
                            </form>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
@section('script')
<script src="assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
$(function () {
    bsCustomFileInput.init();
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