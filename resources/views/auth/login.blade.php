<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk | Presensi SMK Muhammadiyah Kandanghaur</title>
<link rel="icon" type="image/x-icon" href="{{ asset('assets/dist/img/logoKotak.png') }}">
<link rel="manifest" href="/manifest.webmanifest">
<link rel="apple-touch-icon" href="/icons/apple-180.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/presensi-admin.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
<div class="login-logo">
    <img style="width: 15%" src="{{ asset('assets/dist/img/logo.png') }}" alt="Logo SMK Muhammadiyah Kandanghaur">
    <a href="/"><b>Presensi</b> SMK</a>
</div>
<div class="card">
<div class="card-body login-card-body">
    <p class="login-box-msg">Masuk untuk mencatat presensi</p>
    <form action="{{ route('login') }}" method="post">
    @csrf
    <div class="input-group mb-3">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Email" autocomplete="email" autofocus>
        <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-envelope"></span>
        </div>
        </div>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="input-group mb-3">
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Kata sandi" autocomplete="current-password">
        <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-lock"></span>
        </div>
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="row">
        <div class="col-8">
        <div class="icheck-primary">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">
            Ingatkan saya
            </label>
        </div>
        </div>
        <div class="col-4">
        <button type="submit" class="btn btn-primary btn-block">Masuk</button>
        </div>
    </div>
    </form>

    <div class="social-auth-links text-center mt-3 mb-0">
        <p>- atau -</p>
        <a href="{{ route('google.redirect') }}" class="btn btn-block btn-outline-danger">
            <i class="fab fa-google mr-2"></i> Masuk dengan Google
        </a>
        <a href="{{ route('kios') }}" class="btn btn-block btn-default">
            <i class="fas fa-camera mr-2"></i> Presensi tanpa login
        </a>
    </div>
</div>
</div>
</div>

<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
<script>
if ('serviceWorker' in navigator) { window.addEventListener('load', function () { navigator.serviceWorker.register('/sw.js').catch(function () {}); }); }
</script>
</body>
</html>
