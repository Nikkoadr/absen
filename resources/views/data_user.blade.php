@extends('layouts.main')
@section('link')
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

<style>
    .kamera,
    .kamera video {
        display: inline-block;
        width: 100% !important;
        margin: auto;
        height: auto !important;
        border-radius: 15px;
    }
</style>
<style>
    #map {
        height: 180px;
        }
</style>
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Users</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Admin</a></li>
            <li class="breadcrumb-item active">Absensi</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
            <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
            <button type="button" class="btn btn-primary m-1" data-toggle="modal" data-target="#modal_import"><i class="fa-solid fa-file-import"></i> Import</button>
            <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target=""><i class="fa-solid fa-file-export"></i> Export</button>
            <button type="button" class="btn btn-success m-1" data-toggle="modal" data-target=""><i class="fa-solid fa-user-plus"></i> Tambah</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <table id="table_user" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th data-orderable="false">Menu</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no=1; ?>
                @foreach ( $data_user as $data )
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>{{ $data->nama }}</td>
                        <td>{{ $data->email }}</td>
                        <td>{{ $data->status }}</td>
                        <td width="10%" style="text-align: center">
                            <div style=style="display: inline;">
                                <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#"><i class="fa-regular fa-pen-to-square"></i></button>
                                @include('layouts.component.modal_import')
                                <a href="hapus_data_ppdb/{{ $data->id }}" class="btn btn-danger konfirmasi m-1"><i class="far fa-trash-alt"></i></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
@section('script')
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script>
$(function () {
    bsCustomFileInput.init();
});
</script>
<script>
$(function () {
$("#table_user").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
}).buttons().container().appendTo('#table_user_wrapper .col-md-6:eq(0)');
});
</script>
@endsection
