@extends('layouts.main')
@section('link')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('content')
<div class="content-wrapper" id="konten-utama">
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Siswa</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Data Siswa</li>
        </ol>
        </div>
    </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
            <button type="button" class="btn btn-success m-1" data-toggle="modal" data-target="#modalTambahSiswa"><i class="fa-solid fa-user-plus" aria-hidden="true"></i> Tambah</button>
            @include('layouts.component.modal_tambah_siswa')
            <a href="{{ route('siswa.contoh') }}" class="btn btn-info m-1"><i class="fa-solid fa-file-arrow-down" aria-hidden="true"></i> Contoh Excel</a>
            <button type="button" class="btn btn-primary m-1" data-toggle="modal" data-target="#modalImporSiswa"><i class="fa-solid fa-file-import" aria-hidden="true"></i> Impor</button>
            @include('layouts.component.modal_impor_siswa')
            </div>
            <div class="card-body">
            <table id="table_siswa" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Kontak</th>
                        <th>Orang Tua/Wali</th>
                        <th>Telegram</th>
                        <th>RFID</th>
                        <th data-orderable="false">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="modalWadahSiswa"></div>
            </div>
        </div>
        </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
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
<script>
$(function () {
$("#table_siswa").DataTable({
    "processing": true,
    "serverSide": true,
    "responsive": true,
    "lengthChange": true,
    "autoWidth": false,
    "pageLength": 25,
    "ajax": {
        "url": "{{ route('siswa.data') }}",
        "dataSrc": function (json) {
            document.getElementById('modalWadahSiswa').innerHTML = json.modals || '';
            return json.data;
        },
        "error": function () {
            Presensi.galat('Gagal memuat data siswa.');
        }
    },
    "columns": [
        { "data": "siswa", "orderable": true },
        { "data": "kontak", "orderable": true },
        { "data": "ortu", "orderable": false },
        { "data": "telegram", "orderable": false, "searchable": false },
        { "data": "rfid", "orderable": false, "searchable": false },
        { "data": "aksi", "orderable": false, "searchable": false }
    ],
    "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
}).buttons().container().appendTo('#table_siswa_wrapper .col-md-6:eq(0)');
});
</script>
<script>
Presensi.konfirmasiForm(document.getElementById('table_siswa'), 'Anda yakin ingin menghapus data ini?');
</script>
<script>
@if (session()->has('success'))
Presensi.sukses(@json(session('success')));
@endif
</script>
@endsection
