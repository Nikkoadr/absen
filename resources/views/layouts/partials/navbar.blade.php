<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
<!-- Left navbar links -->
<ul class="navbar-nav">
    <li class="nav-item">
    <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Buka tutup menu samping"><i class="fas fa-bars" aria-hidden="true"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
    <a href="/" class="nav-link">Website</a>
    </li>
</ul>

<!-- Right navbar links -->
<ul class="navbar-nav ml-auto">
    <li class="nav-item">
    <a class="nav-link" data-widget="fullscreen" href="#" role="button" aria-label="Layar penuh">
        <i class="fas fa-expand-arrows-alt" aria-hidden="true"></i>
    </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}"
        onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
    <i class="fas fa-sign-out-alt"></i>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
    </form>
        </li>
</ul>
</nav>
<!-- /.navbar -->