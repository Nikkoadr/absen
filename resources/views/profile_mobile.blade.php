@extends('layouts.main_mobile')
@section('link')
@endsection
@section('content')

<div class="section mt-3 mb-5">
    <h3>Profile User : </h3>
    <div class="card">
        <form action="edit/profile" method="POST" enctype="multipart/form-data">
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
                    <label for="nama" class="col-sm-3 col-form-label text-md-end">Nama : </label>
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
                    <label for="email" class="col-sm-3 col-form-label text-md-end">E-mail : </label>
                    <div class="col-sm-9">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email }}" autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
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
                <div class="mb-3 row">
                    <label for="password-confirm" class="col-sm-3 col-form-label">Pas Foto : </label>
                    <div class="col-sm-9">
                            <div class="custom-file">
                            <input type="file" class="custom-file-input @error('pasFoto') is-invalid @enderror" id="pasFoto" name="pasFoto">
                            <label class="custom-file-label" for="pasFoto">Pilih file</label>
                            </div>
                        @error('password-confirm')
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
@endsection
@section('script')
<script src="assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
$(function () {
    bsCustomFileInput.init();
});
</script>
@endsection
</body>
</html>