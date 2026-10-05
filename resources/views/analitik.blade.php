@extends('layouts.main')
@section('content')
<div class="content-wrapper" id="konten-utama">
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Analitik Kehadiran</h1></div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Analitik</li>
        </ol>
        </div>
    </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('analitik.index') }}">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="tanggal_awal">Tanggal Awal</label>
                        <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="{{ $tanggal_awal }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="tanggal_akhir">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggal_akhir }}" required>
                    </div>
                    <div class="form-group col-md-4 align-self-end">
                        <button type="submit" class="btn btn-primary btn-block">Tampilkan</button>
                    </div>
                </div>
                @error('tanggal_awal')<span class="text-danger">{{ $message }}</span>@enderror
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Tren kehadiran harian</h3></div>
            <div class="card-body"><canvas id="grafikTren" height="120"></canvas></div>
        </div>
        </div>
        <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">10 paling sering terlambat</h3></div>
            <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead><tr><th>Nama</th><th class="text-right">Menit</th></tr></thead>
                <tbody>
                @forelse ($topTerlambat as $t)
                    <tr><td>{{ $t['nama'] }}</td><td class="text-right">{{ $t['menit'] }}</td></tr>
                @empty
                    <tr><td colspan="2" class="text-center">Tidak ada keterlambatan.</td></tr>
                @endforelse
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Perbandingan kelas</h3></div>
        <div class="card-body">
            <canvas id="grafikKelas" height="90"></canvas>
            <table class="table table-striped mt-3">
                <thead><tr><th>Kelas</th><th class="text-right">Siswa</th><th class="text-right">Presensi</th><th class="text-right">Terlambat (mnt)</th></tr></thead>
                <tbody>
                @forelse ($perKelas as $k)
                    <tr><td>{{ $k['kelas'] }}</td><td class="text-right">{{ $k['siswa'] }}</td><td class="text-right">{{ $k['presensi'] }}</td><td class="text-right">{{ $k['terlambat_menit'] }}</td></tr>
                @empty
                    <tr><td colspan="4" class="text-center">Belum ada kelas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
</section>
</div>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    if (typeof Chart === 'undefined') return;
    var tren = document.getElementById('grafikTren');
    if (tren) {
        new Chart(tren, {
            type: 'bar',
            data: {
                labels: @json($tren->pluck('tanggal')),
                datasets: [{ label: 'Hadir', data: @json($tren->pluck('hadir')), backgroundColor: '#0284c7' }],
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } },
        });
    }
    var kelas = document.getElementById('grafikKelas');
    if (kelas) {
        new Chart(kelas, {
            type: 'bar',
            data: {
                labels: @json($perKelas->pluck('kelas')),
                datasets: [{ label: 'Menit terlambat', data: @json($perKelas->pluck('terlambat_menit')), backgroundColor: '#b91c1c' }],
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } },
        });
    }
})();
</script>
@endsection
