    <!-- App Bottom Menu -->
    <div class="appBottomMenu">
        <a href="/home" class="item">
            <div class="col">
                <i class="fas fa-home fa-3x {{ request()->is('home') ? '' : 'text-dark' }}"></i>
                <strong>Dasbor</strong>
            </div>
        </a>
        <a href="/profile" class="item">
            <div class="col">
                <i class="fas fa-user-tie fa-3x {{ request()->is('profile') ? '' : 'text-dark' }}"></i>
                <strong>Profil</strong>
            </div>
        </a>
        <a href="/absen" class="item" title="Ambil Presensi" aria-label="Ambil Presensi">
            <div class="col">
                <div class="action-button large">
                    <i class="fas fa-camera fa-3x {{ request()->is('absen') ? 'text-white' : 'text-dark' }}"></i>
                </div>
            </div>
        </a>
        <a href="/history" class="item">
            <div class="col">
                <i class="fas fa-file-alt fa-3x {{ request()->is('history') ? '' : 'text-dark' }}"></i>
                <strong>Riwayat</strong>
            </div>
        </a>
        <a href="{{ route('logout') }}" class="item" 
        onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
            <div class="col">
                <i class="fa-solid fa-right-from-bracket fa-3x text-dark"></i>
                <strong>Keluar</strong>
            </div>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
        </form>
    </div>
    <!-- * App Bottom Menu -->