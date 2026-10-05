@extends('layouts.main')
@section('link')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="konten-utama">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Dasbor Presensi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Dasbor</li>
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
        <div class="col-lg-3 col-6">
        <div class="card stat-card">
            <div class="card-body">
            <div class="stat-angka">{{ $hitungUser }}</div>
            <div class="stat-label">Civitas terdaftar</div>
            <a href="{{ route('data_user') }}" class="stat-link">Kelola data user</a>
            </div>
        </div>
        </div>
        <div class="col-lg-3 col-6">
        <div class="card stat-card">
            <div class="card-body">
            <div class="stat-angka">{{ $hitungMasukHariIni }}</div>
            <div class="stat-label">Masuk hari ini</div>
            <a href="{{ route('attendance') }}" class="stat-link">Lihat kehadiran</a>
            </div>
        </div>
        </div>
        <div class="col-lg-3 col-6">
        <div class="card stat-card">
            <div class="card-body">
            <div class="stat-angka">{{ $hitungPulang }}</div>
            <div class="stat-label">Pulang hari ini</div>
            <a href="{{ route('attendance') }}" class="stat-link">Lihat kehadiran</a>
            </div>
        </div>
        </div>
        <div class="col-lg-3 col-6">
        <div class="card stat-card">
            <div class="card-body">
            <div class="stat-angka">{{ $hitungAlfa }}</div>
            <div class="stat-label">Belum hadir hari ini</div>
            <a href="{{ route('attendance') }}" class="stat-link">Lihat kehadiran</a>
            </div>
        </div>
        </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Tren Kehadiran 7 Hari Terakhir</h4></div>
                    <div class="card-body"><canvas id="grafikTren" height="90"></canvas></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                        <div class="card-header">
                            <h4>Riwayat Presensi Hari Ini</h4>
                        </div>
                    <!-- /.card-header -->
                        <div class="card-body">
                        <table id="table_rekap" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Foto Masuk</th>
                                    <th>Jam Masuk</th>
                                    <th>Foto Keluar</th>
                                    <th>Jam Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($leaderboard as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>@if ($data->foto_masuk)<img class="img-presensi" src="{{ asset('storage/absen_file/'. $data->foto_masuk) }}" alt="Foto masuk {{ $data->nama }}" />@else<span class="badge badge-secondary">RFID</span>@endif</td>
                                <td><span class="badge
                                        @if(($data->jam_kerja_hari ?? $data->user?->jam_kerja) && $data->jam_masuk > ($data->jam_kerja_hari ?? $data->user?->jam_kerja)) badge-warning @else badge-success @endif ">{{ $data->jam_masuk }}</span>
                                </td>
                                <td>@if ($data->foto_keluar)<img class="img-presensi" src="{{ asset('storage/absen_file/'. $data->foto_keluar) }}" alt="Foto keluar {{ $data->nama }}" />@elseif ($data->jam_keluar)<span class="badge badge-secondary">RFID</span>@else<small>Belum Pulang</small>@endif</td>
                                <td>
                                    @if ($data->jam_keluar == null)
                                    <small>Belum Pulang</small>
                                    @else
                                    {{ $data->jam_keluar }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">Belum ada presensi hari ini.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                        </div>
                    <!-- /.card-body -->
                </div>
            <!-- /.card -->
            </div>
        <!-- /.col -->
        </div>
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

<script>
    $(function() {
        $("#table_rekap").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#table_rekap_wrapper .col-md-6:eq(0)');
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        var el = document.getElementById('grafikTren');
        if (!el || typeof Chart === 'undefined') return;
        new Chart(el, {
            type: 'bar',
            data: {
                labels: @json($tren7Hari->pluck('label')),
                datasets: [{ label: 'Hadir', data: @json($tren7Hari->pluck('hadir')), backgroundColor: '#0284c7' }], /* Batang 4.10:1 terhadap putih, lolos batas 3:1 grafik. */
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } },
        });
    })();
</script>

@endsection