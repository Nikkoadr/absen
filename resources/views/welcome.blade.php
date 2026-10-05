<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0369a1">
    <meta name="description" content="Presensi SMK Muhammadiyah Kandanghaur: presensi wajah, shift blok 2 mingguan, dan rekap bulanan.">
    <title>Presensi SMK Muhammadiyah Kandanghaur</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/dist/img/logoKotak.png') }}">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="apple-touch-icon" href="/icons/apple-180.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/presensi-tokens.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        skybrand: { 50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd', 600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e' }
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] }
                }
            }
        };
    </script>
</head>
<body class="font-sans text-slate-800 bg-white antialiased">

<a href="#konten" class="skip-link">Lewati ke konten</a>
<header class="border-b border-skybrand-100 bg-white/95 sticky top-0 z-40">
    <nav class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between" aria-label="Navigasi utama">
        <a href="/" class="flex items-center gap-2 min-h-[44px]">
            <img src="{{ asset('assets/dist/img/logo.png') }}" alt="Logo SMK Muhammadiyah Kandanghaur" class="h-9 w-9 object-contain">
            <span class="font-bold text-skybrand-800 text-lg leading-tight">Presensi SMK</span>
        </a>
        <div class="hidden md:flex items-center gap-1">
            <a href="#cara" class="nav-link px-3 text-slate-700 hover:text-skybrand-700 font-medium">Cara Presensi</a>
            <a href="{{ route('kios') }}" class="nav-link px-3 text-slate-700 hover:text-skybrand-700 font-medium">Presensi Mandiri</a>
            @auth
                <a href="{{ url('/home') }}" class="ml-2 inline-flex items-center min-h-[44px] px-5 rounded-lg bg-skybrand-700 text-white font-semibold hover:bg-skybrand-800">Buka Dasbor</a>
            @else
                <a href="{{ route('login') }}" class="ml-2 inline-flex items-center min-h-[44px] px-5 rounded-lg bg-skybrand-700 text-white font-semibold hover:bg-skybrand-800">Masuk</a>
            @endauth
        </div>
        <button id="menuBtn" class="md:hidden inline-flex items-center justify-center w-11 h-11 rounded-lg text-skybrand-800 hover:bg-skybrand-50" aria-expanded="false" aria-controls="menuMobile" aria-label="Buka menu">
            <svg id="ikonBuka" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            <svg id="ikonTutup" class="w-6 h-6 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </nav>
    <div id="menuMobile" class="hidden md:hidden border-t border-skybrand-100 bg-white px-4 py-2">
        <a href="#cara" class="block py-3 font-medium text-slate-700 border-b border-slate-100">Cara Presensi</a>
        <a href="#fitur" class="block py-3 font-medium text-slate-700 border-b border-slate-100">Fitur</a>
        <a href="{{ route('kios') }}" class="block py-3 font-medium text-slate-700 border-b border-slate-100">Presensi Mandiri</a>
        @auth
            <a href="{{ url('/home') }}" class="block my-3 text-center py-3 rounded-lg bg-skybrand-700 text-white font-semibold">Buka Dasbor</a>
        @else
            <a href="{{ route('login') }}" class="block my-3 text-center py-3 rounded-lg bg-skybrand-700 text-white font-semibold">Masuk</a>
        @endauth
    </div>
</header>

<main id="konten">
    <section class="bg-skybrand-50">
        <div class="max-w-5xl mx-auto px-4 pt-12 pb-10 md:pt-16 md:pb-14 grid gap-8 md:grid-cols-5 md:items-center">
            <div class="md:col-span-3">
                <h1 class="text-3xl md:text-4xl font-extrabold text-skybrand-900 leading-tight">Presensi sekolah tanpa antre, tanpa kertas.</h1>
                <p class="mt-4 text-lg text-slate-700">Guru dan karyawan SMK Muhammadiyah Kandanghaur mencatat kehadiran lewat kamera dan lokasi. Jadwal blok 2 mingguan dan rekap bulanan ikut menyesuaikan otomatis.</p>
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    @auth
                        <a href="{{ url('/home') }}" class="inline-flex justify-center items-center min-h-[48px] px-6 rounded-lg bg-skybrand-700 text-white font-semibold hover:bg-skybrand-800">Buka Dasbor</a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex justify-center items-center min-h-[48px] px-6 rounded-lg bg-skybrand-700 text-white font-semibold hover:bg-skybrand-800">Masuk untuk Presensi</a>
                    @endauth
                    <a href="{{ route('kios') }}" class="inline-flex justify-center items-center min-h-[48px] px-6 rounded-lg border-2 border-skybrand-700 text-skybrand-700 font-semibold hover:bg-skybrand-100">Presensi Tanpa Login</a>
                </div>
            </div>
            <div class="md:col-span-2">
                <div class="bg-skybrand-700 text-white rounded-2xl p-6" role="status" aria-live="off" aria-label="Jam sekolah saat ini">
                    <p class="text-sm text-skybrand-100">Jam sekarang (WIB)</p>
                    <p id="jamSekolah" class="text-4xl font-extrabold tabular-nums mt-1">--:--:--</p>
                    <p id="tanggalSekolah" class="mt-1 text-skybrand-100"></p>
                </div>
            </div>
        </div>
    </section>

    <section id="cara" class="max-w-5xl mx-auto px-4 py-12 scroll-mt-20">
        <h2 class="text-2xl font-bold text-skybrand-900">Cara mencatat presensi</h2>
        <ol class="mt-6 space-y-0 border-t border-slate-200">
            <li class="flex gap-4 py-5 border-b border-slate-200">
                <span class="flex-none w-9 h-9 rounded-full bg-skybrand-700 text-white font-bold flex items-center justify-center" aria-hidden="true">1</span>
                <div>
                    <h3 class="font-semibold text-lg">Daftarkan wajah sekali saja</h3>
                    <p class="text-slate-700 mt-1">Buka Profil, aktifkan kamera, lalu simpan data wajah. Setelah itu kios mengenali Anda tanpa login.</p>
                </div>
            </li>
            <li class="flex gap-4 py-5 border-b border-slate-200">
                <span class="flex-none w-9 h-9 rounded-full bg-skybrand-700 text-white font-bold flex items-center justify-center" aria-hidden="true">2</span>
                <div>
                    <h3 class="font-semibold text-lg">Datang ke area sekolah dan buka presensi</h3>
                    <p class="text-slate-700 mt-1">Pilih Ambil Presensi di aplikasi atau Presensi Tanpa Login di halaman ini. Pastikan lokasi HP aktif karena radius maksimal {{ $radius ?? 70 }} meter dari titik sekolah.</p>
                </div>
            </li>
            <li class="flex gap-4 py-5 border-b border-slate-200">
                <span class="flex-none w-9 h-9 rounded-full bg-skybrand-700 text-white font-bold flex items-center justify-center" aria-hidden="true">3</span>
                <div>
                    <h3 class="font-semibold text-lg">Arahkan wajah dan konfirmasi nama</h3>
                    <p class="text-slate-700 mt-1">Sistem menampilkan nama yang cocok. Periksa namanya benar, lalu catat. Presensi pulang dibuka setelah presensi masuk.</p>
                </div>
            </li>
        </ol>
    </section>
</main>

<footer class="border-t border-slate-200">
    <div class="max-w-5xl mx-auto px-4 py-8 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <p class="text-slate-700"><strong>SMK Muhammadiyah Kandanghaur</strong><br><span class="text-sm">Jl. Raya Karanganyar No. 28/A, Kandanghaur, Indramayu</span></p>
        <a href="https://www.instagram.com/smkmuhkandanghaur/" class="inline-flex items-center min-h-[44px] font-semibold text-skybrand-700">@smkmuhkandanghaur</a>
    </div>
</footer>

<script>
(function () {
    var btn = document.getElementById('menuBtn');
    var menu = document.getElementById('menuMobile');
    var buka = document.getElementById('ikonBuka');
    var tutup = document.getElementById('ikonTutup');
    function setBuka(terbuka) {
        btn.setAttribute('aria-expanded', terbuka ? 'true' : 'false');
        btn.setAttribute('aria-label', terbuka ? 'Tutup menu' : 'Buka menu');
        menu.classList.toggle('hidden', !terbuka);
        buka.classList.toggle('hidden', terbuka);
        tutup.classList.toggle('hidden', !terbuka);
    }
    btn.addEventListener('click', function () { setBuka(menu.classList.contains('hidden')); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setBuka(false); });
    menu.addEventListener('click', function (e) { if (e.target.closest('a')) setBuka(false); });
    function jam() {
        try {
            var sekarang = new Date().toLocaleTimeString('id-ID', { timeZone: 'Asia/Jakarta', hour12: false });
            var tanggal = new Date().toLocaleDateString('id-ID', { timeZone: 'Asia/Jakarta', weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            document.getElementById('jamSekolah').textContent = sekarang;
            document.getElementById('tanggalSekolah').textContent = tanggal;
        } catch (err) { /* jam tetap tanda strip bila gagal */ }
    }
    jam();
    setInterval(jam, 1000);
})();
</script>
<script>
if ('serviceWorker' in navigator) { window.addEventListener('load', function () { navigator.serviceWorker.register('/sw.js').catch(function () {}); }); }
</script>
</body>
</html>
