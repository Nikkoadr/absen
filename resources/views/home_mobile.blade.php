@extends('layouts.main_mobile')
@section('link')

@endsection
@section('content')
    <!-- App Capsule -->
    <div id="appCapsule">
        <div class="sky-header" id="user-section">
            <div id="user-detail">
                <div class="avatar">
                    @if(Auth::user()->pasfoto==null)
                    <img src="{{ asset('assets/dist/img/defaultpp.jpg') }}" alt="avatar" class="imaged w64 rounded" />
                    @else
                    <img src="{{ asset('storage/absen_file/pasFotoAbsen/'. Auth::user()->pasfoto) }}" alt="avatar" class="imaged w64 rounded" />
                    @endif
                </div>
                <div id="user-info">
                    <p class="mb-0">Halo,</p>
                    <h2 id="user-name">{{ Auth::user()->nama }}</h2>
                    <span class="chip-sky">{{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('dddd, D MMMM Y') }}</span>
                    <div class="mt-1">
                        @if ($absenHariIni)
                            <span class="chip-sky">Sudah presensi {{ $absenHariIni->jam_masuk }}</span>
                        @else
                            <span class="chip-sky">Belum presensi hari ini</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="section mt-2">
            <a href="/absen" class="btn btn-sky btn-block" style="padding: 14px; font-size: 17px;">
                <i class="fas fa-camera"></i> Ambil Presensi
            </a>
        </div>

        <div class="section mt-2">
            <div class="row">
                <div class="col-4">
                    <div class="sky-card text-center p-2">
                        <div style="font-size: 26px; font-weight: 800; color: #0284c7;">{{ $rekapAbsensi->jumlahHadir }}</div>
                        <div class="text-muted" style="font-size: 12px;">Hadir</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="sky-card text-center p-2">
                        <div style="font-size: 26px; font-weight: 800; color: #0284c7;">{{ $rekapAbsensi->jumlahIzin ?? 0 }}</div>
                        <div class="text-muted" style="font-size: 12px;">Izin</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="sky-card text-center p-2">
                        <div style="font-size: 26px; font-weight: 800; color: #dc3545;">{{ $rekapAbsensi->jumlahTidakHadir }}</div>
                        <div class="text-muted" style="font-size: 12px;">Alfa</div>
                    </div>
                </div>
            </div>
            <p class="text-muted text-center mt-1" style="font-size: 12px;">Rekap {{ $namaBulan }} {{ $tahunIni }} (hari kerja)</p>
        </div>

        <div class="section" id="menu-section">
            <div class="sky-card">
                <div class="card-body">
                    <div class="list-menu">
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="/absen" class="primary" style="font-size: 40px"><i class="fas fa-camera"></i></a>
                            </div>
                            <div class="menu-name"><span class="text-center">Presensi</span></div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="/history" class="warning" style="font-size: 40px"><i class="fas fa-file-alt"></i></a>
                            </div>
                            <div class="menu-name"><span class="text-center">Riwayat</span></div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="/izin" class="primary" style="font-size: 40px"><i class="fa-solid fa-comment-dots"></i></a>
                            </div>
                            <div class="menu-name"><span class="text-center">Ajukan Izin</span></div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="/profile" class="green" style="font-size: 40px"><i class="fas fa-user"></i></a>
                            </div>
                            <div class="menu-name"><span class="text-center">Profil</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section mt-2" id="presence-section">
            <div class="todaypresence">
                <div class="row">
                    <div class="col-6">
                        <div class="sky-card">
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
                                        <h4 class="presencetitle"><a href="/absen">Masuk</a></h4>
                                        <span>{{ $absenHariIni != null ? $absenHariIni->jam_masuk : 'Belum Absen' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="sky-card">
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
                                        <h4 class="presencetitle"><a href="/absen">Pulang</a></h4>
                                        <span>{{ $absenHariIni != null && $absenHariIni->jam_keluar != null ? $absenHariIni->jam_keluar : 'Belum Absen' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                Peringkat
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content mt-2" style="margin-bottom: 100px">
                    <div class="tab-pane fade show active" id="home" role="tabpanel">
                        <ul class="listview image-listview">
                            @forelse ($historyBulanIni as $data)
                                <li>
                                    <div class="item">
                                        <div class="icon-box bg-primary">
                                            <i class="fas fa-fingerprint"></i>
                                        </div>
                                        <div class="in">
                                            <div>{{ Illuminate\Support\Carbon::parse($data->tanggal_absen)->format('d-M-Y'); }}</div>
                                            <span class="badge
                                            @if($set_jam_kerja && $data->jam_masuk > $set_jam_kerja)
                                                badge-warning
                                                @else
                                                badge-success
                                            @endif
                                            ">{{ $data->jam_masuk }}</span>
                                            <span class="badge badge-danger">{{ $data->jam_keluar ?? '00:00:00' }}</span>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li><div class="item"><div class="in"><div class="text-muted">Belum ada presensi bulan ini.</div></div></div></li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        <ul class="listview image-listview">
                            @forelse ( $leaderboard_mobile as $data)
                            <li>
                                <div class="item">
                                    @if(empty($data->pasfoto))
                                    <img src="assets/mobile/img/sample/avatar/avatar1.jpg" alt="image" class="image" />
                                    @else
                                    <img src="{{ asset('storage/absen_file/pasFotoAbsen/'. $data->pasfoto) }}" alt="image" class="image" />
                                    @endif
                                    <div class="in">
                                        <div><b>{{ $data->nama }}</b><br>
                                            <small class="text-muted">{{ $data->jabatan ?? '-' }}</small>
                                        </div>
                                        <span class="text-muted">Jam Masuk : {{ $data->jam_masuk }}</span>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li><div class="item"><div class="in"><div class="text-muted">Belum ada yang presensi hari ini.</div></div></div></li>
                            @endforelse
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
