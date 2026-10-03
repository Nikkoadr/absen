@extends('layouts.main')
@section('content')
<div class="content-wrapper">
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
    <div class="row">
        <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Sinkron Otomatis (API)</h3></div>
            <form action="{{ route('libur.sinkron') }}" method="POST">
                @csrf
                <div class="card-body">
                    <p class="text-muted">Ambil kalender libur nasional Indonesia dari data terbuka, realtime per tahun.</p>
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
                        @error('tanggal')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="nama">Keterangan</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="cth: Tahun Baru Masehi" required>
                        @error('nama')<span class="invalid-feedback">{{ $message }}</span>@enderror
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
            <div class="card-body p-0">
            <table class="table table-striped">
                <thead><tr><th>No</th><th>Tanggal</th><th>Keterangan</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse ($libur as $h)
                    <tr>
                        <td>{{ $loop->iteration + ($libur->currentPage() - 1) * $libur->perPage() }}</td>
                        <td>{{ \Carbon\Carbon::parse($h->tanggal)->isoFormat('dddd, D MMMM Y') }}</td>
                        <td>{{ $h->nama }}</td>
                        <td>
                            <form action="{{ route('libur.destroy', $h) }}" method="POST" class="d-inline konfirmasi-form">
                                @csrf @method('delete')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Belum ada hari libur. Akhir pekan otomatis libur.</td></tr>
                @endforelse
                </tbody>
            </table>
            </div>
            <div class="card-footer">{{ $libur->links() }}</div>
        </div>
        </div>
    </div>
    </div>
</section>
</div>
@endsection
@section('script')
<script>
document.querySelectorAll('.konfirmasi-form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        Swal.fire({ text: 'Hapus hari libur ini?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus!' })
            .then((r) => { if (r.isConfirmed) form.submit(); });
    });
});
</script>
@endsection
