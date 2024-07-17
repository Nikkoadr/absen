<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Absen | Smkmuhkandanghaur</title>
<link rel="icon" type="image/x-icon" href="{{ asset('assets/dist/img/logoKotak.png') }}">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href=" {{ asset('assets/plugins/fontawesome-free-6.4.2/css/all.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@yield('link')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
@include('layouts.partials.navbar')
@include('layouts.partials.asidebar')
@yield('content')
<!-- Main Footer -->
<footer class="main-footer">
<!-- To the right -->
<div class="float-right d-none d-sm-inline">
Application : v2.4.10 | Laravel : v{{ Illuminate\Foundation\Application::VERSION }} | Php : v{{ PHP_VERSION }}
</div>
<!-- Default to the left -->
<strong>Copyright &copy; 2024-2025 <a href="https://github.com/Nikkoadr">Nikko Adrian</a>.</strong> All rights reserved.
</footer>
</div>
<!-- ./wrapper -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@yield('script')
</body>
</html>