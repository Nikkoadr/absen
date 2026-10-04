    <!-- App Bottom Menu -->
    <div class="appBottomMenu">
        <a href="/home" class="item {{ request()->is('home') ? 'active' : '' }}"{{ request()->is('home') ? ' aria-current="page"' : '' }}>
            <div class="col">
                <i class="fas fa-home" aria-hidden="true"></i>
                <strong>Dasbor</strong>
            </div>
        </a>
        <a href="/profile" class="item {{ request()->is('profile') ? 'active' : '' }}"{{ request()->is('profile') ? ' aria-current="page"' : '' }}>
            <div class="col">
                <i class="fas fa-user-tie" aria-hidden="true"></i>
                <strong>Profil</strong>
            </div>
        </a>
        <a href="/absen" class="item {{ request()->is('absen') ? 'active' : '' }}" title="Ambil Presensi" aria-label="Ambil Presensi"{{ request()->is('absen') ? ' aria-current="page"' : '' }}>
            <div class="col">
                <div class="action-button large">
                    <i class="fas fa-camera" aria-hidden="true"></i>
                </div>
            </div>
        </a>
        <a href="/history" class="item {{ request()->is('history') ? 'active' : '' }}"{{ request()->is('history') ? ' aria-current="page"' : '' }}>
            <div class="col">
                <i class="fas fa-file-alt" aria-hidden="true"></i>
                <strong>Riwayat</strong>
            </div>
        </a>
        <a href="{{ route('logout') }}" class="item"
        onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
            <div class="col">
                <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                <strong>Keluar</strong>
            </div>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
        </form>
    </div>
    <!-- * App Bottom Menu -->
