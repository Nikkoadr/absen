@extends('layouts.main_mobile')
@section('link')
    
@endsection
@section('content')
<div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="/history">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="bulan">Bulan:</label>
                                        <select class="form-control" id="bulan" name="bulan">
                                            {{-- Menampilkan opsi bulan dalam bahasa Indonesia --}}
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ $i == $bulan ? 'selected' : '' }}>
                                                {{ Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tahun">Tahun:</label>
                                        <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Masukkan tahun" value="{{ $tahun }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary form-control">Cari</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="section mt-3 mb-5">
    <div class="card">
        <ul class="listview image-listview">
            @foreach ($history as $data)
                <li>
                    <div class="item">
                        <div class="icon-box bg-primary">
                            <i class="fas fa-fingerprint"></i>
                        </div>
                        <div class="in">
                            <div>{{ Illuminate\Support\Carbon::parse($data->tanggal_absen)->format('d-M-Y'); }}</div>
                            <span class="badge 
                            @if($data->jam_masuk > "07:00")
                                badge-warning
                                @else
                                badge-success
                            @endif
                            ">{{ $data->jam_masuk }}</span>
                            <span class="badge badge-danger">
                                @if($data->jam_keluar == null)
                                    00:00:00
                                @else
                                    {{ $data->jam_keluar }}
                            @endif</span>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
@section('script')

@endsection