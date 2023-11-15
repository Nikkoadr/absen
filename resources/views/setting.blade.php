@extends('layouts.main')
@section('link')
<link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endsection
@section('content')
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Setting</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">setting</li>
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
            <h3 class="card-title">Setting</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="/editSetting" method="POST">
                    @csrf
                    @method('put')
                    <input type="hidden" name="id" value="{{ $setting->id }}">
                    <div class="form-group">
                        <label for="namaLokasi">Nama Lokasi:</label>
                        <input type="text" class="form-control" id="namaLokasi" name="namaLokasi" value="{{ $setting->namaLokasi }}">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="latitude">Latitude:</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" value="{{ $setting->latitude }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="longitude">Longitude:</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" value="{{ $setting->longitude }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="radius">Radius:</label>
                        <input type="text" class="form-control" id="radius" name="radius" value="{{ $setting->radius }}">
                    </div>
                    <button style="float: right" type="submit" class="btn btn-primary">Edit</button>
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