@extends('layouts.main')
@section('content')
<div class="content-wrapper" id="konten-utama">
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Jadwal Shift</h1></div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">Jadwal Shift</li>
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
            <div class="card-header"><h3 class="card-title">Tambah Shift</h3></div>
            <form action="{{ route('shift.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">Nama Shift</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="cth: Pagi, Siang, Blok Produktif" required>
                        @error('nama')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="jam_masuk">Jam Masuk</label>
                            <input type="time" step="1" name="jam_masuk" id="jam_masuk" value="{{ old('jam_masuk') }}" class="form-control @error('jam_masuk') is-invalid @enderror" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="jam_pulang">Jam Pulang</label>
                            <input type="time" step="1" name="jam_pulang" id="jam_pulang" value="{{ old('jam_pulang') }}" class="form-control @error('jam_pulang') is-invalid @enderror">
                        </div>
                    </div>
                </div>
                <div class="card-footer"><button type="submit" class="btn btn-primary float-right">Simpan</button></div>
            </form>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Impor Jadwal (Excel)</h3></div>
            <div class="card-body">
                <p class="text-muted">Unggah jadwal terbaru sekaligus. Nama shift yang belum ada dibuat baru; baris tak dikenal dilewati dan dilaporkan.</p>
                <a href="{{ route('shift.contoh') }}" class="btn btn-info btn-block mb-2">Unduh Contoh Excel</a>
                <form action="{{ route('shift.impor') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('berkas') is-invalid @enderror" id="berkas" name="berkas" accept=".xlsx,.xls,.csv">
                            <label class="custom-file-label" for="berkas">Pilih file</label>
                        </div>
                        @error('berkas')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Upload Jadwal</button>
                </form>
                @if (session('warning_impor'))
                    <div class="alert alert-warning mt-2 mb-0">
                        <ul class="mb-0 pl-3">
                            @foreach (session('warning_impor') as $w)
                                <li>{{ $w }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Daftar Shift</h3></div>
            <div class="card-body p-0">
            <table class="table table-striped">
                <thead><tr><th>Nama</th><th>Masuk</th><th>Pulang</th><th></th></tr></thead>
                <tbody>
                @forelse ($shifts as $s)
                    <tr>
                        <td>{{ $s->nama }}</td>
                        <td>{{ substr($s->jam_masuk, 0, 5) }}</td>
                        <td>{{ $s->jam_pulang ? substr($s->jam_pulang, 0, 5) : '-' }}</td>
                        <td>
                            <form action="{{ route('shift.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus shift ini beserta penugasannya?')">
                                @csrf @method('delete')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Belum ada shift.</td></tr>
                @endforelse
                </tbody>
            </table>
            </div>
        </div>
        </div>
        <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Tugaskan Shift per Periode (cth: blok 2 mingguan)</h3></div>
            <form action="{{ route('shift.tugas.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="user_id">Karyawan</label>
                            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                <option value="">- Pilih -</option>
                                @foreach ($karyawan as $k)
                                    <option value="{{ $k->id }}" @selected(old('user_id') == $k->id)>{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="shift_id">Shift</label>
                            <select name="shift_id" id="shift_id" class="form-control @error('shift_id') is-invalid @enderror" required>
                                <option value="">- Pilih -</option>
                                @foreach ($shifts as $s)
                                    <option value="{{ $s->id }}" @selected(old('shift_id') == $s->id)>{{ $s->nama }} ({{ substr($s->jam_masuk, 0, 5) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="form-control @error('tanggal_mulai') is-invalid @enderror" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tanggal_selesai">Tanggal Selesai (kosongkan = seterusnya)</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="form-control @error('tanggal_selesai') is-invalid @enderror">
                        </div>
                    </div>
                    @error('user_id')<div class="text-danger">{{ $message }}</div>@enderror
                    @error('tanggal_selesai')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="card-footer"><button type="submit" class="btn btn-primary float-right">Simpan Penugasan</button></div>
            </form>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Penugasan Aktif &amp; Mendatang</h3></div>
            <div class="card-body p-0">
            <table class="table table-striped">
                <thead><tr><th>Karyawan</th><th>Shift</th><th>Periode</th><th></th></tr></thead>
                <tbody>
                @forelse ($tugas as $t)
                    <tr>
                        <td>{{ $t->user->nama }}</td>
                        <td>{{ $t->shift->nama }} ({{ substr($t->shift->jam_masuk, 0, 5) }})</td>
                        <td>{{ $t->tanggal_mulai->format('d M Y') }} s.d. {{ $t->tanggal_selesai?->format('d M Y') ?? 'seterusnya' }}</td>
                        <td>
                            <form action="{{ route('shift.tugas.destroy', $t) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus penugasan ini?')">
                                @csrf @method('delete')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Belum ada penugasan. Tanpa penugasan, dipakai jam kerja bawaan karyawan.</td></tr>
                @endforelse
                </tbody>
            </table>
            </div>
            <div class="card-footer">{{ $tugas->links() }}</div>
        </div>
        </div>
    </div>
    </div>
</section>
</div>
@endsection
