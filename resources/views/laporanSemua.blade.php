@extends('layouts.main')
@section('link')
<!-- DataTables -->
<link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

@endsection
@section('content')
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Rekapitulasi Absensi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Rekap</li>
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
            <h3 class="card-title">Pilih Bulan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <form method="POST" action="/printLaporanSemua" target="_blank">
                        @csrf
                        @method('put')
                        <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="bulan">Bulan:</label>
                                        <select class="form-control" id="bulan" name="bulan">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ $i == $bulan ? 'selected' : '' }}>
                                                {{ Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tahun">Tahun:</label>
                                        <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Masukkan tahun" value="{{ $tahun }}" readonly>
                                    </div>
                                </div>
                        </div>
                        <button style="float: right;" type="submit" class="btn btn-primary">
                            Cetak Rekapitulasi
                        </button>
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
<!-- DataTables  & Plugins -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="assets/plugins/jszip/jszip.min.js"></script>
<script src="assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<!-- Page specific script -->
<script>
$(function () {
$("#table_ppdb").DataTable({
    "responsive": true, 
    "lengthChange": true, 
    "autoWidth": true,
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
}).buttons().container().appendTo('#table_ppdb_wrapper .col-md-6:eq(0)');
});
</script>
