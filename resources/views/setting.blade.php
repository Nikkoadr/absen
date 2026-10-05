@extends('layouts.main')
@section('link')
<link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endsection
@section('content')
<div class="content-wrapper" id="konten-utama">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Pengaturan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Pengaturan</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div><!-- /.content-header -->
    <!-- Main content -->
<section class="content">
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h3 class="card-title">Pengaturan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('editSetting') }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="nama_lokasi">Nama Lokasi:</label>
                        <input type="text" class="form-control" id="nama_lokasi" name="nama_lokasi" value="{{ $setting->nama_lokasi }}">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="latitude">Latitude:</label>
                            <input type="number" step="any" class="form-control" id="latitude" name="latitude" value="{{ $setting->latitude }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="longitude">Longitude:</label>
                            <input type="number" step="any" class="form-control" id="longitude" name="longitude" value="{{ $setting->longitude }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="radius">Radius (meter):</label>
                        <input type="number" min="1" class="form-control" id="radius" name="radius" value="{{ $setting->radius }}">
                    </div>
                    <div class="form-group">
                        <label for="limit_absen">Limit Absen Harian</label>
                        <input type="time" class="form-control" id="limit_absen" name="limit_absen" value="{{ $setting->limit_absen }}">
                    </div>
                    <div class="form-group">
                        <label for="telegram_bot_token">Token Bot Telegram</label>
                        <input type="text" class="form-control" id="telegram_bot_token" name="telegram_bot_token" value="{{ $setting->telegram_bot_token }}" placeholder="cth: 123456:ABC...">
                        <small class="form-text text-muted">Dari BotFather. Uji via <code>php artisan telegram:uji ID_CHAT</code>.</small>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="telegram_aktif" name="telegram_aktif" value="1" {{ $setting->telegram_aktif ? 'checked' : '' }}>
                        <label class="form-check-label" for="telegram_aktif">Aktifkan notifikasi ke orang tua</label>
                    </div>
                    <button type="submit" class="btn btn-primary float-right">Simpan</button>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
@endsection
@section('script')
<script src="assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script>
@if (session()->has('success'))
Presensi.sukses(@json(session('success')));
@endif
</script>
@endsection