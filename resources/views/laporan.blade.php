@extends('layouts.main')
@section('content')
<div class="content-wrapper" id="konten-utama">
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">{{ $judul }}</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Beranda</a></li>
            <li class="breadcrumb-item active">{{ $judul }}</li>
        </ol>
        </div>
    </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pilih Rentang Tanggal</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('printSemuaLaporan') }}" target="_blank">
                            @csrf
                            <input type="hidden" name="kelompok" value="{{ $kelompok }}">
                            <div class="form-row">
                                <div class="form-group col-4">
                                    <label for="tanggal_awal" class="col-form-label">Tanggal Awal</label>
                                    <input id="tanggal_awal" type="date" class="form-control @error('tanggal_awal') is-invalid @enderror" name="tanggal_awal" required autofocus>
                                    @error('tanggal_awal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-4">
                                    <label for="tanggal_akhir" class="col-form-label">Tanggal Akhir</label>
                                    <input id="tanggal_akhir" type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror" name="tanggal_akhir" required>
                                    @error('tanggal_akhir')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                @if ($kelompok === 'siswa')
                                <div class="form-group col-4">
                                    <label for="kelas_id" class="col-form-label">Kelas</label>
                                    <select id="kelas_id" class="form-control" name="kelas_id">
                                        <option value="">Semua kelas</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}">{{ $k->tingkat }} {{ $k->nama }} ({{ $k->kompetensi->singkatan }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">Cetak Laporan</button>
                                <button type="submit" formaction="{{ route('downloadLaporanBulanan') }}" class="btn btn-success">Unduh Excel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
