<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">

        <!-- Brand -->
        <div class="sidenav-header align-items-center">
            <a class="navbar-brand" href="javascript:void(0)">
                <img src="assets/img/brand/blue.png" class="navbar-brand-img" alt="..." />
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
                            <i class="fas fa-home text-primary"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string(current_url()) == 'class' ? 'active' : '' ?>"
                            href="<?= base_url('class') ?>">
                            <i class="ni ni-building text-primary"></i>
                            <span class="nav-link-text">Manajemen Kelas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string(current_url()) == 'lecturer' ? 'active' : '' ?>"
                            href="<?= base_url('lecturer') ?>">
                            <i class="ni ni-single-02 text-primary"></i>
                            <span class="nav-link-text">Manajemen Dosen</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string(current_url()) == 'schedule' ? 'active' : '' ?>"
                            href="<?= base_url('schedule') ?>">
                            <i class="ni ni-calendar-grid-58 text-primary"></i>
                            <span class="nav-link-text">Manajemen Jadwal</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string(current_url()) == 'attendance' ? 'active' : '' ?>"
                            href="<?= base_url('attendance') ?>">
                            <i class="ni ni-badge text-primary"></i>
                            <span class="nav-link-text">Riwayat Kehadiran</span>
                        </a>
                    </li>
                </ul>

            </div>

        </div>
    </div>
</nav>
