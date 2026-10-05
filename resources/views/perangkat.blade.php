@extends('layouts.main')
@section('content')
<div class="content-wrapper" id="konten-utama">
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Perangkat RFID</h1></div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Perangkat RFID</li>
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
            <div class="card-header"><h3 class="card-title">Tambah Perangkat</h3></div>
            <form action="{{ route('perangkat.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">Nama Gerbang</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="cth: Gerbang Depan" required>
                        @error('nama')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="latitude">Latitude</label>
                            <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude') }}" class="form-control" placeholder="opsional">
                        </div>
                        <div class="form-group col-6">
                            <label for="longitude">Longitude</label>
                            <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude') }}" class="form-control" placeholder="opsional">
                        </div>
                    </div>
                    <p class="text-muted small">Koordinat opsional: bila diisi, radius dihitung dari gerbang ini. Kunci perangkat tampil sekali setelah disimpan.</p>
                </div>
                <div class="card-footer"><button type="submit" class="btn btn-primary float-right">Simpan</button></div>
            </form>
        </div>
        </div>
        <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Daftar Perangkat</h3></div>
            <div class="card-body p-0">
            <table class="table table-striped">
                <thead><tr><th>Nama</th><th>Status</th><th>Terakhir Aktif</th><th></th></tr></thead>
                <tbody>
                @forelse ($perangkat as $p)
                    <tr>
                        <td>{{ $p->nama }}</td>
                        <td>
                            @if ($p->aktif)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $p->terakhir_aktif?->isoFormat('D MMM Y HH:mm') ?? 'Belum pernah' }}</td>
                        <td class="text-nowrap">
                            <form action="{{ route('perangkat.toggle', $p) }}" method="POST" class="d-inline">
                                @csrf @method('put')
                                <button class="btn btn-sm btn-warning">{{ $p->aktif ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                            </form>
                            <form action="{{ route('perangkat.regenerasi', $p) }}" method="POST" class="d-inline konfirmasi-form" data-konfirmasi="Buat kunci baru? Kunci lama langsung mati.">
                                @csrf @method('put')
                                <button class="btn btn-sm btn-info">Kunci Baru</button>
                            </form>
                            <form action="{{ route('perangkat.destroy', $p) }}" method="POST" class="d-inline konfirmasi-form" data-konfirmasi="Hapus perangkat ini?">
                                @csrf @method('delete')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Belum ada perangkat. Kunci global <code>RFID_DEVICE_KEY</code> tetap berlaku.</td></tr>
                @endforelse
                </tbody>
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
<script>
Presensi.konfirmasiForm(document, 'Anda yakin ingin menghapus data ini?');
</script>
@endsection
