<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#0c4a6e" />
    <meta name="description" content="Layar monitor presensi siswa SMK Muhammadiyah Kandanghaur." />
    <title>Monitor Presensi | SMK Muhammadiyah Kandanghaur</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/dist/img/logoKotak.png') }}" />
    <link rel="manifest" href="/manifest.webmanifest" />
    <link rel="apple-touch-icon" href="/icons/apple-180.png" />
    <link rel="stylesheet" href="{{ asset('assets/css/presensi-tokens.css') }}" />
    <style>
        body { background: #0c4a6e; margin: 0; min-height: 100vh; display: flex; flex-direction: column; }
        .monitor-bar { display: flex; align-items: center; justify-content: space-between; gap: 24px; padding: 18px 48px; color: #fff; border-bottom: 1px solid rgba(255,255,255,.25); }
        .monitor-brand { display: flex; align-items: center; gap: 16px; font-size: 30px; font-weight: 800; }
        .monitor-brand img { width: 56px; height: 56px; object-fit: contain; background: #fff; border-radius: 14px; }
        .monitor-waktu { text-align: right; }
        .monitor-jam { font-size: 52px; font-weight: 800; line-height: 1; font-variant-numeric: tabular-nums; }
        .monitor-tanggal { font-size: 22px; color: rgba(255,255,255,.92); }
        .monitor-badan { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 28px; padding: 32px 48px; }
        .monitor-tap { width: 100%; max-width: 1020px; background: #fff; border-radius: 18px; padding: 14px 24px; display: flex; gap: 14px; align-items: center; }
        .monitor-tap label { font-weight: 700; color: var(--ink); white-space: nowrap; font-size: 20px; }
        .monitor-tap input { flex: 1; min-height: 54px; font-size: 20px; border: 1px solid var(--line); border-radius: 12px; padding: 0 16px; }
        .monitor-tap-galat { color: #ffd7d7; font-size: 20px; text-align: center; margin: 0; }
        .monitor-kartu { background: #fff; border-radius: 28px; padding: 56px 72px; display: flex; align-items: center; gap: 56px; max-width: 1020px; width: 100%; }
        .monitor-kartu.baru { animation: kartuMasuk .35s ease; }
        .monitor-foto { width: 250px; height: 250px; border-radius: 50%; object-fit: cover; flex: none; background: var(--sky-100); }
        .monitor-inisial { width: 250px; height: 250px; border-radius: 50%; flex: none; display: flex; align-items: center; justify-content: center; background: var(--sky-700); color: #fff; font-size: 110px; font-weight: 800; }
        .monitor-nama { font-size: 64px; font-weight: 800; color: var(--ink); line-height: 1.05; margin: 0; }
        .monitor-kelas { font-size: 34px; color: var(--muted); margin: 10px 0 0; }
        .monitor-gerbang { font-size: 24px; color: var(--muted); margin: 4px 0 0; }
        .monitor-jam-masuk { font-size: 46px; font-weight: 800; color: var(--sky-700); margin: 20px 0 0; font-variant-numeric: tabular-nums; }
        .monitor-status { display: inline-block; margin-top: 14px; background: #1e7e34; color: #fff; font-size: 26px; font-weight: 700; border-radius: 999px; padding: 10px 32px; }
        .monitor-ide { text-align: center; color: #fff; }
        .monitor-ide img { width: 150px; height: 150px; object-fit: contain; background: #fff; border-radius: 30px; padding: 14px; }
        .monitor-ide h1 { font-size: 54px; margin: 28px 0 0; }
        .monitor-ide p { font-size: 30px; color: rgba(255,255,255,.92); margin: 10px 0 0; }
        .monitor-kaki { display: flex; align-items: center; justify-content: center; gap: 36px; padding: 16px 48px 24px; color: #fff; font-size: 22px; }
        .monitor-jumlah { font-weight: 800; font-size: 26px; }
        .monitor-terbaru { display: flex; gap: 28px; }
        .monitor-terbaru span { color: rgba(255,255,255,.92); font-variant-numeric: tabular-nums; }
        .monitor-terbaru strong { color: #fff; }
        @keyframes kartuMasuk { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
    </style>
</head>
<body>
<header class="monitor-bar">
    <span class="monitor-brand">
        <img src="{{ asset('assets/dist/img/logo.png') }}" alt="Logo SMK Muhammadiyah Kandanghaur" />
        Presensi SMK
    </span>
    <span class="monitor-waktu">
        <span class="monitor-jam" id="jam">--:--:--</span>
        <span class="monitor-tanggal" id="tanggal" style="display: block;"></span>
    </span>
</header>

<main class="monitor-badan">
    @auth
    @if (in_array(Auth::user()->role, ['admin', 'guru']))
    <div class="monitor-tap">
        <label for="uid">Tap kartu:</label>
        <input type="text" id="uid" autocomplete="off" placeholder="Tempelkan kartu pada reader…" />
    </div>
    <p class="monitor-tap-galat" id="tapGalat" role="alert" style="display: none;"></p>
    @endif
    @endauth
    <div class="monitor-ide" id="tampilanIdle">
        <img src="{{ asset('assets/dist/img/logo.png') }}" alt="" />
        <h1>Tempelkan kartu</h1>
        <p>pada alat RFID di gerbang</p>
    </div>

    <div class="monitor-kartu" id="tampilanSiswa" style="display: none;" role="status" aria-live="polite">
        <img class="monitor-foto" id="fotoSiswa" src="" alt="" style="display: none;" />
        <span class="monitor-inisial" id="inisialSiswa" style="display: none;"></span>
        <div>
            <h1 class="monitor-nama" id="namaSiswa"></h1>
            <p class="monitor-kelas" id="kelasSiswa"></p>
            <p class="monitor-gerbang" id="gerbangSiswa"></p>
            <p class="monitor-jam-masuk">Masuk <span id="jamMasukSiswa"></span></p>
            <span class="monitor-status">HADIR</span>
        </div>
    </div>
</main>

<footer class="monitor-kaki">
    <span>Hadir hari ini: <strong class="monitor-jumlah" id="jumlahHadir">0</strong></span>
    <span class="monitor-terbaru" id="daftarTerbaru"></span>
</footer>

<script>
(function () {
    var terakhirTampil = '';

    function jam() {
        try {
            document.getElementById('jam').textContent =
                new Date().toLocaleTimeString('id-ID', { timeZone: 'Asia/Jakarta', hour12: false });
            document.getElementById('tanggal').textContent =
                new Date().toLocaleDateString('id-ID', { timeZone: 'Asia/Jakarta', weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        } catch (err) {}
    }
    jam();
    setInterval(jam, 1000);

    function tampilkan(d, segarkan) {
        document.getElementById('tampilanIdle').style.display = 'none';
        var kartu = document.getElementById('tampilanSiswa');
        kartu.style.display = 'flex';
        if (segarkan) {
            kartu.classList.remove('baru');
            void kartu.offsetWidth;
            kartu.classList.add('baru');
        }
        document.getElementById('namaSiswa').textContent = d.nama;
        document.getElementById('kelasSiswa').textContent = d.kelas || '';
        document.getElementById('gerbangSiswa').textContent = d.gerbang ? 'via ' + d.gerbang : '';
        document.getElementById('jamMasukSiswa').textContent = d.jam_masuk || d.jam || '-';
        var foto = document.getElementById('fotoSiswa');
        var inisial = document.getElementById('inisialSiswa');
        if (d.foto) {
            foto.src = d.foto;
            foto.alt = 'Pas foto ' + d.nama;
            foto.style.display = 'block';
            inisial.style.display = 'none';
        } else {
            foto.style.display = 'none';
            inisial.textContent = d.inisial || '?';
            inisial.style.display = 'flex';
        }
    }

    function kaki(d) {
        if (typeof d.jumlah === 'number') {
            document.getElementById('jumlahHadir').textContent = d.jumlah;
        }
        var wadah = document.getElementById('daftarTerbaru');
        wadah.innerHTML = '';
        (d.terbaru || []).slice(0, 4).forEach(function (t) {
            var s = document.createElement('span');
            var nama = document.createElement('strong');
            nama.textContent = t.nama.split(' ')[0];
            s.appendChild(nama);
            s.appendChild(document.createTextNode(' ' + t.jam));
            wadah.appendChild(s);
        });
    }

    function cek() {
        fetch('/api/monitor-terakhir', { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (!d.ada) return;
                kaki(d);
                var kunci = d.nama + '|' + d.jam_masuk + '|' + d.diperbarui;
                if (kunci !== terakhirTampil) {
                    terakhirTampil = kunci;
                    tampilkan(d, true);
                }
            })
            .catch(function () {});
    }
    cek();
    setInterval(cek, 3000);

    if ('serviceWorker' in navigator) { window.addEventListener('load', function () { navigator.serviceWorker.register('/sw.js').catch(function () {}); }); }

    var uidInput = document.getElementById('uid');
    if (uidInput) {
        uidInput.focus();
        document.addEventListener('click', function (e) {
            if (!e.target.closest('a') && !e.target.closest('button')) uidInput.focus({ preventScroll: true });
        });
        uidInput.addEventListener('keydown', function (e) {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            var uid = uidInput.value.trim();
            uidInput.value = '';
            uidInput.focus();
            if (!uid) return;
            var galat = document.getElementById('tapGalat');

            fetch("{{ route('tap.operator') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ uid: uid }),
            }).then(function (r) { return r.json().then(function (j) { return { kode: r.status, isi: j }; }); })
            .then(function (res) {
                if (res.isi.status === 'sukses') {
                    galat.style.display = 'none';
                    terakhirTampil = res.isi.nama + '|' + (res.isi.jam || '') + '|tap';
                    tampilkan(res.isi, true);
                    cek();
                } else {
                    galat.textContent = res.isi.message || 'Gagal mencatat.';
                    galat.style.display = 'block';
                }
            })
            .catch(function () {
                galat.textContent = 'Jaringan bermasalah. Coba lagi.';
                galat.style.display = 'block';
            });
        });
    }
})();
</script>
</body>
</html>
