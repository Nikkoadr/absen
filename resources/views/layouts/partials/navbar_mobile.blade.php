    <!-- App Bottom Menu -->
    <div class="appBottomMenu">
        <a href="/home" class="item">
            <div class="col">
                <i class="fas fa-home fa-3x {{ request()->is('home') ? '' : 'text-dark' }}"></i>
                <strong>Dashboard</strong>
            </div>
        </a>
        <a href="#" class="item">
            <div class="col">
                <i class="fas fa-calendar-alt fa-3x text-dark"></i>
                <strong>Calendar</strong>
            </div>
        </a>
        <a href="/absen" class="item">
            <div class="col">
                <div class="action-button large">
                    <i class="fas fa-camera fa-3x {{ request()->is('absen') ? 'text-white' : 'text-dark' }}"></i>
                </div>
            </div>
        </a>
        <a href="#" class="item">
            <div class="col">
                <i class="fas fa-file-alt fa-3x text-dark"></i>
                <strong>Docs</strong>
            </div>
        </a>
        <a href="javascript:;" class="item">
            <div class="col">
                <i class="fas fa-user-tie fa-3x text-dark"></i>
                <strong>Profile</strong>
            </div>
        </a>
    </div>
    <!-- * App Bottom Menu -->