@extends('layouts.main_mobile')
@section('link')
    
@endsection
@section('content')
    <!-- App Capsule -->
    <div id="appCapsule">
        <div class="section bg-primary" id="user-section">
            <div id="user-detail">
                <div class="avatar">
                    @if(Auth::user()->pasfoto==null)
                    <img src="{{ asset('assets/dist/img/defaultpp.jpg') }}" alt="avatar" class="imaged w64 rounded" />
                    @else
                    <img src="{{ asset('storage/absen_file/pasFotoAbsen/'. Auth::user()->pasfoto) }}" alt="avatar" class="imaged w64 rounded" />
                    @endif
                </div>
                <div id="user-info">
                    <h2 id="user-name">{{ Auth::user()->nama }}</h2>
                    <span id="user-role">{{ Auth::user()->jabatan }}</span>
                </div>
            </div>
        </div>

        <div class="section" id="menu-section">
            <div class="card">
                <div class="card-body text-center">
                    <div class="list-menu">
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="/profile" class="green" style="font-size: 40px"><i class="fas fa-user"></i>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Profil</span>
                            </div>
                        </div>

                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="/history" class="warning" style="font-size: 40px">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Histori</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="section mt-2" id="presence-section">
            <div class="todaypresence">
                <div class="row">
                    <div class="col-6">
                        <div class="card bg-success">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence">
                                        @if ($absenHariIni != null )
                                            <img style="width: 60px" src="{{ asset('storage/absen_file/'. $absenHariIni->foto_masuk) }}">
                                        @else
                                            <i class="fas fa-clock"></i>
                                        @endif
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="presencetitle"><a style="color: white" href="/absen">Masuk</a></h4>
                                        <span>{{ $absenHariIni != null ? $absenHariIni->jam_masuk : 'Belum Absen' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-danger">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence">
                                        @if ($absenHariIni != null && $absenHariIni->jam_keluar != null)
                                            <img style="width: 60px" src="{{ asset('storage/absen_file/'. $absenHariIni->foto_keluar) }}">
                                        @else
                                            <i class="fas fa-clock"></i>
                                        @endif
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="presencetitle"><a style="color: white" href="/absen">Pulang</a></h4>
                                        <span>{{ $absenHariIni != null && $absenHariIni->jam_keluar != null ? $absenHariIni->jam_keluar : 'Belum Absen' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rekappresence mt-1">
                <h3>Rekap Bulan {{ $namaBulan[$bulanIni] }} Tahun {{ $tahunIni }} : </h3>
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence primary">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="rekappresencetitle">Hadir</h4>
                                        <span class="rekappresencedetail">{{ $rekapAbsensi->jumlahHadir }} Hari</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence warning">
                                        <i class="fa fa-clock"></i>
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="rekappresencetitle">Terlambat</h4>
                                        <span class="rekappresencedetail">{{ $rekapAbsensi->jumlahTerlambat }} Hari</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row mt-1">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence danger">
                                        <i class="fas fa-sad-tear"></i>
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="rekappresencetitle">Sakit</h4>
                                        <span class="rekappresencedetail">0 Hari</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence green">
                                        <i class="fas fa-info"></i>
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="rekappresencetitle">Izin</h4>
                                        <span class="rekappresencedetail">0 Hari</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="presencetab mt-2">
                <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                    <ul class="nav nav-tabs style1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                Bulan Ini
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                Leaderboard
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content mt-2" style="margin-bottom: 100px">
                    <div class="tab-pane fade show active" id="home" role="tabpanel">
                        <ul class="listview image-listview">
                            @foreach ($historyBulanIni as $data)
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
                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        <ul class="listview image-listview">
                            @foreach ( $leaderboard as $data)
                            <li>
                                <div class="item">
                                    <img src="assets/mobile/img/sample/avatar/avatar1.jpg" alt="image" class="image" />
                                    <div class="in">
                                        <div><b>{{ $data->nama }}</b><br>
                                            <small class="text-muted">{{ $data->jabatan }}</small>
                                        </div>
                                        <span class="text-muted">Jam Masuk : {{ $data->jam_masuk }}</span>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->
@endsection
@section('script')

@endsection