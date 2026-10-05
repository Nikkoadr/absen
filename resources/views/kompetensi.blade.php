@extends('layouts.main')
@section('content')
<div class="content-wrapper" id="konten-utama">
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Kompetensi Keahlian</h1></div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Kompetensi Keahlian</li>
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
            <div class="card-header"><h3 class="card-title">Tambah Kompetensi</h3></div>
            <form action="{{ route('kompetensi.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">Nama Kompetensi</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="cth: Teknik Kendaraan Ringan" required>
                        @error('nama')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="singkatan">Singkatan</label>
                        <input type="text" name="singkatan" id="singkatan" value="{{ old('singkatan') }}" class="form-control @error('singkatan') is-invalid @enderror" placeholder="cth: TKR" required>
                        @error('singkatan')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="card-footer"><button type="submit" class="btn btn-primary float-right">Simpan</button></div>
            </form>
        </div>
        </div>
        <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Daftar Kompetensi</h3></div>
            <div class="card-body p-0">
            <table class="table table-striped">
                <thead><tr><th>Nama</th><th>Singkatan</th><th>Jumlah Kelas</th><th></th></tr></thead>
                <tbody>
                @forelse ($kompetensi as $k)
                    <tr>
                        <td>{{ $k->nama }}</td>
                        <td>{{ $k->singkatan }}</td>
                        <td>{{ $k->kelas_count }}</td>
                        <td>
                            <form action="{{ route('kompetensi.destroy', $k) }}" method="POST" class="d-inline konfirmasi-form" data-konfirmasi="Hapus kompetensi beserta kelasnya?">
                                @csrf @method('delete')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Belum ada kompetensi.</td></tr>
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
