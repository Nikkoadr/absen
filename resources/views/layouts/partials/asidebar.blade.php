<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
<!-- Brand Logo -->
<a href="/home" class="brand-link">
    <img src="{{ asset('assets/dist/img/logo.png') }}" alt="Logo Presensi" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Presensi SMK</span>
</a>
<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        @if(Auth::user()->pasfoto == null)
        <img src="{{ asset('assets/dist/img/defaultpp.jpg') }}" class="img-circle elevation-2" alt="foto pengguna">
        @else
        <img src="{{ asset('storage/absen_file/pasFotoAbsen/'. Auth::user()->pasfoto) }}" class="img-circle elevation-2" alt="foto pengguna">
        @endif
    </div>
    <div class="info">
        <a href="/profile" class="d-block">{{ Auth::user()->nama }}</a>
        <span class="badge badge-info">{{ ucfirst(Auth::user()->role) }}</span>
    </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">MENU UTAMA</li>
        <li class="nav-item">
        <a href="/home" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dasbor</p>
        </a>
        </li>
        <li class="nav-item">
        <a href="/absen" class="nav-link {{ request()->is('absen') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-camera"></i>
            <p>Ambil Presensi</p>
        </a>
        </li>
        @if(Auth::user()->role === 'guru')
        <li class="nav-item">
        <a href="/wali-kelas" class="nav-link {{ request()->is('wali-kelas') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-chalkboard-user"></i>
            <p>Kelas Saya</p>
        </a>
        </li>
        @endif
        @if(in_array(Auth::user()->role, ['admin', 'guru']))
        <li class="nav-item">
        <a href="/monitor" class="nav-link {{ request()->is('monitor') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-nfc-symbol"></i>
            <p>Monitor Gerbang</p>
        </a>
        </li>
        @endif
        @can('is_admin')
        <li class="nav-header">KELOLA DATA</li>
        <li class="nav-item">
        <a href="/data_user" class="nav-link {{ request()->is('data_user') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-users"></i>
            <p>Data Karyawan</p>
        </a>
        </li>
        <li class="nav-item">
        <a href="/siswa" class="nav-link {{ request()->is('siswa') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-graduation-cap"></i>
            <p>Data Siswa</p>
        </a>
        </li>
        <li class="nav-item">
        <a href="/kenaikan" class="nav-link {{ request()->is('kenaikan') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-arrow-up-right-dots"></i>
            <p>Kenaikan Kelas</p>
        </a>
        </li>
        <li class="nav-item">
        <a href="/kelas" class="nav-link {{ request()->is('kelas') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-school"></i>
            <p>Kelas</p>
        </a>
        </li>
        <li class="nav-item">
        <a href="/kompetensi" class="nav-link {{ request()->is('kompetensi') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-book-open"></i>
            <p>Kompetensi</p>
        </a>
        </li>
        <li class="nav-item">
        <a href="/attendance" class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-clipboard-user"></i>
            <p>Kehadiran</p>
        </a>
        </li>
        <li class="nav-item">
        <a href="/perizinan" class="nav-link {{ request()->is('perizinan') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-envelope-open-text"></i>
            <p>Persetujuan Izin</p>
        </a>
        </li>
        <li class="nav-item">
        <a href="/shift" class="nav-link {{ request()->is('shift') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-clock"></i>
            <p>Jadwal Shift</p>
        </a>
        </li>
        <li class="nav-header">LAPORAN</li>
        <li class="nav-item menu-close">
        <a href="#" class="nav-link {{ request()->is('laporan/*') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-database"></i>
            <p>
            Laporan
            <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
            <a href="/laporan/karyawan" class="nav-link {{ request()->is('laporan/karyawan') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Rekap Karyawan</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="/laporan/siswa" class="nav-link {{ request()->is('laporan/siswa') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Rekap Siswa</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="/analitik" class="nav-link {{ request()->is('analitik') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Analitik</p>
            </a>
            </li>
        </ul>
        </li>
        <li class="nav-header">SISTEM</li>
        <li class="nav-item">
        <a href="/cadangan" class="nav-link {{ request()->is('cadangan') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-box-archive"></i>
            <p>Cadangan</p>
        </a>
        </li>
        <li class="nav-item">
        <a href="/audit" class="nav-link {{ request()->is('audit') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-clock-rotate-left"></i>
            <p>Audit Log</p>
        </a>
        </li>
        <li class="nav-item">
        <a href="/perangkat" class="nav-link {{ request()->is('perangkat') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-tower-broadcast"></i>
            <p>Perangkat RFID</p>
        </a>
        </li>
        <li class="nav-item">
        <a href="/libur" class="nav-link {{ request()->is('libur') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-calendar-xmark"></i>
            <p>Hari Libur</p>
        </a>
        </li>
        <li class="nav-item">
        <a href="/setting" class="nav-link {{ request()->is('setting') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-gears"></i>
            <p>Pengaturan</p>
        </a>
        </li>
        @endcan
    </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
