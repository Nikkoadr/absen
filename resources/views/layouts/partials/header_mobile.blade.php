    <!-- App Header -->
    <div class="appHeader position-fixed" style="background: linear-gradient(135deg, #0284c7, #38bdf8);">
        <div class="left">
            <a href="/home" class="headerButton" title="Kembali ke Dasbor" aria-label="Kembali ke Dasbor">
                <i class="fas fa-arrow-left" style="color: #fff;"></i>
            </a>
        </div>
        <div class="pageTitle" style="color: #fff;">Presensi SMK</div>
        <div class="right">
            <a href="{{ route('logout') }}" class="headerButton" title="Keluar" aria-label="Keluar"
            onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                <i class="fa-solid fa-right-from-bracket" style="color: #fff;"></i>
            </a>
            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
            </form>
        </div>
    </div>
    <!-- * App Header -->
