<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
<!-- Brand Logo -->
<a href="/home" class="brand-link">
    <img src="{{ asset('assets/dist/img/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Absensi SMK</span>
</a>
<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        <img src="{{ asset('assets/dist/img/defaultpp.jpg') }}" class="img-circle elevation-2" alt="garmbar user">
    </div>
    <div class="info">
        <a href="/profile" class="d-block">{{ Auth::user()->nama }}</a>
    </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
        <a href="/home" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
            Dashboard
            </p>
        </a>
        </li>
        <li class="nav-item">
        <a href="/absen" class="nav-link {{ request()->is('absen') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-camera"></i>
            <p>
            Absen
            </p>
        </a>
        </li>
        @can('is_admin')
        <li class="nav-item">
        <a href="/data_user" class="nav-link {{ request()->is('data_user') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-users"></i>
            <p>
            Data Users
            </p>
        </a>
        </li>
                <li class="nav-item menu-close">
        <a href="#" class="nav-link {{ request()->is('rekap') ? 'active' : '' }} {{ request()->is('laporan') ? 'active' : '' }}">
            <i class="nav-icon fa-solid fa-database"></i>
            <p>
            Administrasi
            <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
            <a href="rekap" class="nav-link {{ request()->is('rekap') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Rekapitulasi</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="laporan" class="nav-link {{ request()->is('laporan') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Laporan</p>
            </a>
            </li>
        </ul>
        </li>
        @endcan
    </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>