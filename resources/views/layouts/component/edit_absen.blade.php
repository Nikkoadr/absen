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
        <h1 class="m-0">Ubah Presensi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/attendance">Kehadiran</a></li>
            <li class="breadcrumb-item active">Ubah Presensi</li>
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
            <h3 class="card-title">Ubah Presensi</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('update_absen', $data->id) }}" method="POST">
                            @method('put')
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $data->user->nama }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_absen">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal_absen" name="tanggal_absen" value="{{ $data->tanggal_absen }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="jam_masuk">Jam Masuk</label>
                                    <input type="time" step="1" class="form-control" id="jam_masuk" name="jam_masuk" value="{{ $data->jam_masuk }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="jam_keluar">Jam Pulang</label>
                                    <input type="time" step="1" class="form-control" id="jam_keluar" name="jam_keluar" value="{{ $data->jam_keluar }}">
                                </div>
                            </div>
                                <button type="submit" class="btn btn-primary m-2 float-right">Simpan</button>
                                <a href="/attendance" class="btn btn-secondary m-2 float-right">Kembali</a>
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