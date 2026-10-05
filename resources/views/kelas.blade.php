@extends('layouts.main')
@section('content')
<div class="content-wrapper" id="konten-utama">
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Kelas</h1></div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Kelas</li>
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
            <div class="card-header"><h3 class="card-title">Tambah Kelas</h3></div>
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="kompetensi_id">Kompetensi Keahlian</label>
                        <select name="kompetensi_id" id="kompetensi_id" class="form-control @error('kompetensi_id') is-invalid @enderror" required>
                            <option value="">- Pilih -</option>
                            @foreach ($kompetensi as $k)
                                <option value="{{ $k->id }}" @selected(old('kompetensi_id') == $k->id)>{{ $k->nama }} ({{ $k->singkatan }})</option>
                            @endforeach
                        </select>
                        @error('kompetensi_id')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="tingkat">Tingkat</label>
                            <select name="tingkat" id="tingkat" class="form-control @error('tingkat') is-invalid @enderror" required>
                                <option value="">- Pilih -</option>
                                @foreach (['X', 'XI', 'XII', 'XIII'] as $t)
                                    <option value="{{ $t }}" @selected(old('tingkat') == $t)>{{ $t }}</option>
                                @endforeach
                            </select>
                            @error('tingkat')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="nama">Nama Kelas</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="cth: TKJ 1" required>
                            @error('nama')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="jam_masuk">Jam Masuk</label>
                            <input type="time" step="1" name="jam_masuk" id="jam_masuk" value="{{ old('jam_masuk', '07:00') }}" class="form-control @error('jam_masuk') is-invalid @enderror" required>
                            @error('jam_masuk')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="jam_pulang">Jam Pulang</label>
                            <input type="time" step="1" name="jam_pulang" id="jam_pulang" value="{{ old('jam_pulang') }}" class="form-control @error('jam_pulang') is-invalid @enderror">
                            @error('jam_pulang')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="wali_kelas_user_id">Wali Kelas</label>
                        <select name="wali_kelas_user_id" id="wali_kelas_user_id" class="form-control @error('wali_kelas_user_id') is-invalid @enderror">
                            <option value="">- Belum ditentukan -</option>
                            @foreach ($guru as $g)
                                <option value="{{ $g->id }}" @selected(old('wali_kelas_user_id') == $g->id)>{{ $g->nama }}</option>
                            @endforeach
                        </select>
                        @error('wali_kelas_user_id')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="card-footer"><button type="submit" class="btn btn-primary float-right">Simpan</button></div>
            </form>
        </div>
        </div>
        <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Daftar Kelas</h3></div>
            <div class="card-body p-0">
            <table class="table table-striped">
                <thead><tr><th>Kelas</th><th>Kompetensi</th><th>Jam</th><th>Wali Kelas</th><th></th></tr></thead>
                <tbody>
                @forelse ($kelas as $k)
                    <tr>
                        <td>{{ $k->tingkat }} {{ $k->nama }}</td>
                        <td>{{ $k->kompetensi->singkatan }}</td>
                        <td>{{ substr($k->jam_masuk, 0, 5) }}{{ $k->jam_pulang ? ' - '.substr($k->jam_pulang, 0, 5) : '' }}</td>
                        <td>{{ $k->waliKelas->nama ?? 'Belum ditentukan' }}</td>
                        <td>
                            <form action="{{ route('kelas.destroy', $k) }}" method="POST" class="d-inline konfirmasi-form" data-konfirmasi="Hapus kelas ini? Siswa di dalamnya menjadi tanpa kelas.">
                                @csrf @method('delete')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Belum ada kelas.</td></tr>
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
