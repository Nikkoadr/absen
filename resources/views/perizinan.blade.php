@extends('layouts.main')
@section('content')
<div class="content-wrapper" id="konten-utama">
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Persetujuan Izin</h1></div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Persetujuan Izin</li>
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
    <div class="card">
        <div class="card-header"><h3 class="card-title">Menunggu Persetujuan</h3></div>
        <div class="card-body p-0">
        <table class="table table-striped">
            <thead><tr><th>No</th><th>Nama</th><th>Jenis</th><th>Periode</th><th>Keterangan</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse ($menunggu as $izin)
                <tr>
                    <td>{{ $loop->iteration + ($menunggu->currentPage() - 1) * $menunggu->perPage() }}</td>
                    <td>{{ $izin->user->nama }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $izin->jenis)) }}</td>
                    <td>{{ $izin->tanggal_mulai->format('d M Y') }}@if($izin->tanggal_selesai) s.d. {{ $izin->tanggal_selesai->format('d M Y') }}@endif</td>
                    <td>{{ $izin->keterangan ?? '-' }}</td>
                    <td class="text-nowrap">
                        <form action="{{ route('perizinan.setujui', $izin) }}" method="POST" class="d-inline">
                            @csrf @method('put')
                            <button class="btn btn-sm btn-success">Setujui</button>
                        </form>
                        <form action="{{ route('perizinan.tolak', $izin) }}" method="POST" class="d-inline konfirmasi-form" data-konfirmasi="Tolak pengajuan izin ini?">
                            @csrf @method('put')
                            <button class="btn btn-sm btn-danger">Tolak</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada pengajuan menunggu.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
        <div class="card-footer">{{ $menunggu->links() }}</div>
    </div>
    </div>
</section>
</div>
@endsection
@section('script')
<script>
Presensi.konfirmasiForm(document, 'Anda yakin ingin menghapus data ini?');
</script>
@endsection
