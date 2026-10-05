@extends('layouts.main')
@section('link')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection
@section('content')
<div class="content-wrapper" id="konten-utama">
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Audit Log</h1></div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Audit Log</li>
        </ol>
        </div>
    </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="saringAksi">Aksi</label>
                    <select id="saringAksi" class="form-control">
                        <option value="">Semua</option>
                        <option value="tambah">Tambah</option>
                        <option value="ubah">Ubah</option>
                        <option value="hapus">Hapus</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="saringTabel">Tabel</label>
                    <select id="saringTabel" class="form-control">
                        <option value="">Semua</option>
                        @foreach ($tabel as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
        <table id="table_audit" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pelaku</th>
                    <th>Aksi</th>
                    <th>Sasaran</th>
                    <th>Rincian</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        </div>
    </div>
    </div>
</section>
</div>
@endsection
@section('script')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script>
$(function () {
    var tabel = $("#table_audit").DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "autoWidth": false,
        "order": [],
        "ajax": {
            "url": "{{ route('audit.data') }}",
            "data": function (d) {
                d.aksi = $("#saringAksi").val();
                d.tabel = $("#saringTabel").val();
            },
            "error": function () {
                Presensi.galat('Gagal memuat audit log.');
            }
        },
        "columns": [
            { "data": "waktu", "orderable": false },
            { "data": "pelaku", "orderable": false },
            { "data": "aksi", "orderable": false },
            { "data": "sasaran", "orderable": false },
            { "data": "rincian", "orderable": false }
        ],
        "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" }
    });
    $("#saringAksi, #saringTabel").on("change", function () { tabel.ajax.reload(); });
});
</script>
@endsection
