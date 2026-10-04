@extends('layouts.main')
@section('link')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('content')
<div class="content-wrapper" id="konten-utama">
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1>Data Kehadiran</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Data Kehadiran</li>
        </ol>
        </div>
    </div>
    </div>
</section>

<section class="content">
    <div class="card col-12">
        <div class="card-header">
            <h3 class="card-title">Pilih Bulan</h3>
        </div>
        <div class="card-body">
            <form method="get" action="/attendance">
                <div class="form-row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hari">Hari:</label>
                            <input type="number" class="form-control" id="hari" name="hari" placeholder="1-31" min="1" max="31" value="{{ $hari }}">
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tahun">Tahun:</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" placeholder="Contoh: 2026" min="2020" max="2100" value="{{ $tahun }}">
                        </div>
                    </div>
                    <div class="col-md-3 align-self-end">
                        <div class="form-group">
                            <label for="cari" class="d-none d-md-block" aria-hidden="true">&nbsp;</label>
                            <button type="submit" id="cari" class="btn btn-primary btn-block">Cari</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Kehadiran</h3>
    </div>
            <div class="card-body">
            <table id="table_att" class="table table-bordered table-striped">
                <thead>
                <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($attendance as $data )
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data -> nama }}</td>
                    <td>{{ Illuminate\Support\Carbon::parse($data->tanggal_absen)->format('d-M-Y'); }}</td>
                    <td>{{ $data -> jam_masuk }}</td>
                    <td>{{ $data -> jam_keluar }}</td>
                    <td>
                        <a href="{{ route('edit_absen', $data->id) }}" class="btn btn-info" aria-label="Ubah kehadiran {{ $data->nama }}" title="Ubah"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                        <form action="{{ route('hapus_absen', $data->id) }}" method="POST" class="d-inline konfirmasi-form">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger m-1" aria-label="Hapus kehadiran {{ $data->nama }}" title="Hapus"><i class="far fa-trash-alt" aria-hidden="true"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">Tidak ada data pada filter ini.</td></tr>
                @endforelse
                </tbody>
            </table>
            </div>
    </div>
</section>
</div>
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
<script>
$(function () {
$("#table_att").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": true,
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
}).buttons().container().appendTo('#table_att_wrapper .col-md-6:eq(0)');
});
</script>
<script>
document.querySelectorAll('.konfirmasi-form').forEach(function(form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        Swal.fire({
            text: "Anda yakin ingin menghapus data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
<script>
@if (session()->has('success'))
var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 5000
});
    Toast.fire({
    icon: 'success',
    title: '{{ session('success') }}'
    })
@endif
</script>
@endsection
