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
        <h1 class="m-0">Hari Libur</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Hari Libur</li>
        </ol>
        </div>
    </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="row">
        <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Sinkron Otomatis (API)</h3></div>
            <form action="{{ route('libur.sinkron') }}" method="POST">
                @csrf
                <div class="card-body">
                    <p class="text-muted">Ambil kalender libur nasional Indonesia dari data terbuka GitHub per tahun yang dipilih.</p>
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="number" name="tahun" id="tahun" value="{{ now()->year }}" min="2020" max="2100" class="form-control">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success float-right">Sinkron dari API</button>
                </div>
            </form>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Tambah Hari Libur</h3></div>
            <form action="{{ route('libur.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" class="form-control @error('tanggal') is-invalid @enderror" required>
                        @error('tanggal')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nama">Keterangan</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="cth: Cuti bersama" required>
                        @error('nama')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right">Simpan</button>
                </div>
            </form>
        </div>
        </div>
        <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Daftar Hari Libur</h3></div>
            <div class="card-body">
            <table id="table_libur" class="table table-bordered table-striped">
                <thead>
                <tr><th>No</th><th>Tanggal</th><th>Keterangan</th><th data-orderable="false">Aksi</th></tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>
        </div>
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
    $("#table_libur").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: {
            url: "{{ route('libur.data') }}",
            error: function () {
                Presensi.galat('Gagal memuat daftar libur.');
            }
        },
        columns: [
            { data: "no", orderable: false, searchable: false },
            { data: "tanggal" },
            { data: "nama" },
            { data: "aksi", orderable: false, searchable: false },
        ],
        language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
    });
    document.getElementById("table_libur").addEventListener("submit", function (event) {
        if (!event.target.matches(".hapus-libur")) return;
        event.preventDefault();
        const form = event.target;
        Presensi.konfirmasi("Hapus hari libur ini?").then((ya) => { if (ya) form.submit(); });
    });
});
</script>
@endsection
