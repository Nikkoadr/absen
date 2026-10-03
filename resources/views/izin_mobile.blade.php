@extends('layouts.main_mobile')
@section('link')

@endsection
@section('content')
<div class="presencetab">
<div class="sky-header text-center mb-2">
    <h1 style="font-size: 20px;">Pengajuan Izin</h1>
</div>
    @if (session('success'))
        <div class="alert alert-success m-3">{{ session('success') }}</div>
    @endif
    <div class="tab-content mt-2" style="margin-bottom: 100px">
        <div class="tab-pane fade show active" id="dataDiri" role="tabpanel">
            <div class="section mt-3 mb-5">
                <div class="card">
                    <form action="{{ route('request_izin_user') }}" method="POST">
                        @csrf
                        <div class="col">
                            <div class="row mb-3">
                                <label for="nama" class="col-sm-3 col-form-label text-md-end">Nama <span style="color: red">*</span> : </label>
                                <div class="col-sm-9">
                                    <input id="nama" readonly type="text" class="form-control" name="nama" value="{{ Auth::user()->nama }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="jenis" class="col-sm-3 col-form-label text-md-end">Jenis Izin <span style="color: red">*</span> : </label>
                                <div class="col-sm-9">
                                    <select id="jenis" class="form-control @error('jenis') is-invalid @enderror" name="jenis" required>
                                        <option value="" disabled selected>Pilih jenis izin</option>
                                        <option value="izin" @selected(old('jenis') === 'izin')>Izin</option>
                                        <option value="sakit" @selected(old('jenis') === 'sakit')>Sakit</option>
                                        <option value="cuti" @selected(old('jenis') === 'cuti')>Cuti</option>
                                        <option value="dinas_luar" @selected(old('jenis') === 'dinas_luar')>Dinas Luar</option>
                                    </select>
                                    @error('jenis')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="tanggal_mulai" class="col-sm-3 col-form-label text-md-end">Tanggal Mulai <span style="color: red">*</span> : </label>
                                <div class="col-sm-9">
                                    <input id="tanggal_mulai" type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                                    @error('tanggal_mulai')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="tanggal_selesai" class="col-sm-3 col-form-label text-md-end">Tanggal Selesai :</label>
                                <div class="col-sm-9">
                                    <input id="tanggal_selesai" type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
                                    @error('tanggal_selesai')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="keterangan" class="col-sm-3 col-form-label text-md-end">Keterangan :</label>
                                <div class="col-sm-9">
                                    <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" rows="3" placeholder="Isi keterangan izin Anda">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div style="margin-bottom: 50px" class="form-group boxed">
                                <div class="input-wrapper">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <ion-icon name="refresh-outline"></ion-icon>
                                        Ajukan Izin
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if (! empty($riwayat) && $riwayat->count())
                    <div class="card mt-3">
                        <div class="card-header">Riwayat Pengajuan Terakhir</div>
                        <ul class="list-group list-group-flush">
                            @foreach ($riwayat as $izin)
                                <li class="list-group-item">
                                    {{ $izin->tanggal_mulai->format('d M Y') }} — {{ ucwords(str_replace('_', ' ', $izin->jenis)) }}
                                    <span class="badge bg-info float-end">{{ ucfirst($izin->status) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
@endsection
</body>
</html>
