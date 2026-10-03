<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0ea5e9">
    <title>Presensi SMK Muhammadiyah Kandanghaur</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/dist/img/logoKotak.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:400,500,700,800&display=swap">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Inter, system-ui, sans-serif; background: linear-gradient(180deg, #e0f2fe 0%, #f8fafc 320px); color: #0c4a6e; min-height: 100vh; }
        .wrap { max-width: 960px; margin: 0 auto; padding: 32px 20px 64px; }
        .hero { background: linear-gradient(135deg, #0284c7, #38bdf8); color: #fff; border-radius: 24px; padding: 40px 32px; box-shadow: 0 16px 40px rgba(2,132,199,.35); display: flex; gap: 24px; align-items: center; flex-wrap: wrap; }
        .hero img { width: 84px; height: 84px; border-radius: 20px; background: #fff; padding: 8px; }
        .hero h1 { font-size: 28px; font-weight: 800; }
        .hero p { opacity: .9; margin-top: 8px; }
        .cta { display: flex; gap: 12px; margin-top: 20px; flex-wrap: wrap; }
        .btn { display: inline-block; padding: 12px 22px; border-radius: 14px; font-weight: 700; text-decoration: none; }
        .btn-putih { background: #fff; color: #0369a1; }
        .btn-garis { border: 2px solid rgba(255,255,255,.8); color: #fff; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-top: 24px; }
        .kartu { background: #fff; border: 1px solid #e0f2fe; border-radius: 18px; padding: 20px; box-shadow: 0 6px 18px rgba(2,132,199,.10); }
        .kartu h3 { font-size: 16px; margin-bottom: 6px; }
        .kartu p { font-size: 14px; color: #475569; }
        .kaki { text-align: center; color: #64748b; font-size: 13px; margin-top: 32px; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="hero">
        <img src="{{ asset('assets/dist/img/logo.png') }}" alt="Logo SMK">
        <div>
            <h1>Presensi SMK Muhammadiyah Kandanghaur</h1>
            <p>Presensi wajah, lokasi, dan foto — cepat untuk karyawan, rapi untuk admin.</p>
            <div class="cta">
                @auth
                    <a class="btn btn-putih" href="{{ url('/home') }}">Buka Dasbor</a>
                @else
                    <a class="btn btn-putih" href="{{ route('login') }}">Masuk</a>
                @endauth
                <a class="btn btn-garis" href="{{ route('kios') }}">Presensi Mandiri Tanpa Login</a>
            </div>
        </div>
    </div>
    <div class="grid">
        <div class="kartu"><h3>Presensi Masuk &amp; Pulang</h3><p>Foto wajah terdeteksi AI, lokasi dalam radius sekolah, sekali sehari.</p></div>
        <div class="kartu"><h3>Pengajuan Izin</h3><p>Izin, sakit, cuti, dan dinas luar langsung dari HP dengan persetujuan admin.</p></div>
        <div class="kartu"><h3>Rekap Bulanan</h3><p>Cetak dan unduh rekap kehadiran plus total keterlambatan.</p></div>
    </div>
    <div class="kaki">SMK Muhammadiyah Kandanghaur &middot; <a href="https://www.smkmuhkandanghaur.sch.id">Situs sekolah</a></div>
</div>
</body>
</html>
