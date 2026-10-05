@extends('layouts.main')
@section('content')
<div class="content-wrapper" id="konten-utama">
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Kelas Saya</h1></div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Kelas Saya</li>
        </ol>
        </div>
    </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
    @forelse ($kelas as $k)
    <div class="card">
        <div class="card-header"><h3 class="card-title">{{ $k->tingkat }} {{ $k->nama }} ({{ $k->kompetensi->singkatan }})</h3></div>
        <div class="card-body p-0">
        <table class="table table-striped">
            <thead><tr><th>Nama</th><th>Masuk</th><th>Pulang</th><th>Status</th></tr></thead>
            <tbody>
            @forelse ($k->siswa as $s)
                @php($absen = $kehadiran[$s->user_id] ?? null)
                <tr>
                    <td>{{ $s->user->nama }}</td>
                    <td>{{ $absen->jam_masuk ?? '-' }}</td>
                    <td>{{ $absen->jam_keluar ?? '-' }}</td>
                    <td>
                        @if ($absen)
                            <span class="badge badge-success">Hadir</span>
                        @else
                            <span class="badge badge-secondary">Belum hadir</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Belum ada siswa di kelas ini.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
    @empty
    <div class="card"><div class="card-body text-muted">Anda belum ditugaskan sebagai wali kelas mana pun.</div></div>
    @endforelse
    </div>
</section>
</div>
@endsection
