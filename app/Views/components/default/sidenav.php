<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">

        <!-- Brand -->
        <div class="sidenav-header align-items-center">
            <a class="navbar-brand" href="javascript:void(0)">
                <img src="assets/img/brand/logo.png" class="navbar-brand-img" alt="Aplikasi Absensi" />
            </a>
        </div>

        <div class="navbar-inner">

            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">

                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string(current_url()) == 'dashboard' ? 'active' : '' ?>"
                            href="<?= base_url('dashboard') ?>">
                            <i class="material-icons text-primary">dashboard</i>
                            <?= uri_string(current_url()) == 'dashboard' ?
                                '<b class="nav-link-text">Dashboard</b>' :
                                '<span class="nav-link-text text-muted">Dashboard</span>' ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string(current_url()) == 'class' ? 'active' : '' ?>"
                            href="<?= base_url('class') ?>">
                            <i class="material-icons text-primary">class</i>
                            <?= uri_string(current_url()) == 'class' ?
                                '<b class="nav-link-text">Manajemen Kelas</b>' :
                                '<span class="nav-link-text text-muted">Manajemen Kelas</span>' ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string(current_url()) == 'lecturer' ? 'active' : '' ?>"
                            href="<?= base_url('lecturer') ?>">
                            <i class="material-icons text-primary">supervisor_account</i>
                            <?= uri_string(current_url()) == 'lecturer' ?
                                '<b class="nav-link-text">Manajemen Dosen</b>' :
                                '<span class="nav-link-text text-muted">Manajemen Dosen</span>' ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string(current_url()) == 'schedule' ? 'active' : '' ?>"
                            href="<?= base_url('schedule') ?>">
                            <i class="material-icons text-primary">event</i>
                            <?= uri_string(current_url()) == 'schedule' ?
                                '<b class="nav-link-text">Manajemen Jadwal</b>' :
                                '<span class="nav-link-text text-muted">Manajemen Jadwal</span>' ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string(current_url()) == 'attendance' ? 'active' : '' ?>"
                            href="<?= base_url('attendance') ?>">
                            <i class="material-icons text-primary">assignment</i>
                            <?= uri_string(current_url()) == 'attendance' ?
                                '<b class="nav-link-text">Riwayat Kehadiran</b>' :
                                '<span class="nav-link-text text-muted">Riwayat Kehadiran</span>' ?>
                        </a>
                    </li>
                </ul>

            </div>

        </div>
        
    </div>
</nav>
