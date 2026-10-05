@extends('layouts.main')
@section('content')
<div class="content-wrapper" id="konten-utama">
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Kenaikan Kelas</h1></div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Kenaikan Kelas</li>
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
        <div class="card-header"><h3 class="card-title">Pindahkan siswa per kelas tahun ajaran baru</h3></div>
        <form action="{{ route('kenaikan.proses') }}" method="POST">
            @csrf
            <div class="card-body p-0">
            <table class="table table-striped">
                <thead><tr><th>Kelas Asal</th><th>Jumlah Siswa</th><th>Kelas Tujuan</th></tr></thead>
                <tbody>
                @forelse ($kelas as $k)
                    <tr>
                        <td>{{ $k->tingkat }} {{ $k->nama }} ({{ $k->kompetensi->singkatan }})</td>
                        <td>{{ $k->siswa_count }}</td>
                        <td>
                            <select name="peta[{{ $k->id }}]" class="form-control" aria-label="Tujuan {{ $k->tingkat }} {{ $k->nama }}">
                                <option value="">- Tetap -</option>
                                @foreach ($tujuan as $t)
                                    @if ($t->id !== $k->id)
                                    <option value="{{ $t->id }}">{{ $t->tingkat }} {{ $t->nama }} ({{ $t->kompetensi->singkatan }})</option>
                                    @endif
                                @endforeach
                                <option value="lulus">Lulus (akun dinonaktifkan)</option>
                            </select>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">Belum ada kelas.</td></tr>
                @endforelse
                </tbody>
            </table>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right" id="tombolKenaikan">Jalankan Kenaikan</button>
            </div>
        </form>
    </div>
    </div>
</section>
</div>
@endsection
@section('script')
<script>
document.querySelector('form[action="{{ route('kenaikan.proses') }}"]').addEventListener('submit', function (e) {
    e.preventDefault();
    var form = this;
    Presensi.konfirmasi('Jalankan kenaikan sesuai pilihan?', 'Ya, Jalankan!').then(function (ya) {
        if (ya) form.submit();
    });
});
</script>
@endsection
