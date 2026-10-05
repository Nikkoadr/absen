<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#0ea5e9">
    <title>Presensi SMK</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/dist/img/logoKotak.png') }}">
    <meta name="description" content="Absensi SMK Muhammadiyah Kandanghaur">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="apple-touch-icon" sizes="180x180" href="assets/mobile/img/icon/192x192.png" />
    <link rel="manifest" href="/manifest.webmanifest" />
    <link rel="apple-touch-icon" href="/icons/apple-180.png" />
    <link rel="stylesheet" href="{{ asset('assets/mobile/css/inc/bootstrap/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:400,500,700&display=swap" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free-6.4.2/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/mobile/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/presensi-tokens.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/mobile/css/sky.css') }}?v=9" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <meta name="mobile-web-app-capable" content="yes">

@yield('link')
</head>
<body style="background-color: #e9ecef">
    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"><span class="sr-only">Memuat halaman…</span></div>
    </div>
    <!-- * loader -->
<a href="#konten-utama" class="skip-link">Lewati ke konten</a>
@include('layouts.partials.header_mobile')
<main id="konten-utama">
@yield('content')
</main>
@include('layouts.partials.navbar_mobile')
    <!-- Jquery -->
    <script src="{{ asset('assets/mobile/js/lib/jquery-3.4.1.min.js') }}"></script>
    <!-- Bootstrap-->
    <script src="{{ asset('assets/mobile/js/lib/popper.min.js') }}"></script>
    <script src="{{ asset('assets/mobile/js/lib/bootstrap.min.js') }}"></script>
    <!-- jQuery Circle Progress -->
    <script src="{{ asset('assets/mobile/js/plugins/jquery-circle-progress/circle-progress.min.js') }}"></script>
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <!-- Base Js File -->
    <script src="{{ asset('assets/mobile/js/base.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/presensi-notif.js') }}"></script>
    <script>
    if ('serviceWorker' in navigator) { window.addEventListener('load', function () { navigator.serviceWorker.register('/sw.js').catch(function () {}); }); }
    </script>
@yield('script')
</body>
</html>