@extends('layouts.main')
@section('content')
<div class="content-wrapper" id="konten-utama">
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Cadangan Database</h1></div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Cadangan</li>
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
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">7 cadangan terbaru (otomatis tiap jam 2 pagi)</h3>
            <div class="card-tools">
                <form action="{{ route('cadangan.buat') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary">Buat Sekarang</button>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead><tr><th>Berkas</th><th>Ukuran</th><th>Waktu</th><th></th></tr></thead>
            <tbody>
            @forelse ($daftar as $b)
                <tr>
                    <td>{{ $b['nama'] }}</td>
                    <td>{{ number_format($b['ukuran'] / 1024, 1) }} KB</td>
                    <td>{{ \Carbon\Carbon::createFromTimestamp($b['waktu'], 'Asia/Jakarta')->isoFormat('D MMM Y HH:mm') }}</td>
                    <td><a href="{{ route('cadangan.unduh', $b['nama']) }}" class="btn btn-sm btn-info">Unduh</a></td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Belum ada cadangan.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
    </div>
</section>
</div>
@endsection
