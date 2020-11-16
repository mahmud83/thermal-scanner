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
            <a class="nav-link <?= uri_string(current_url()) == 'student' ? 'active' : '' ?>"
               href="<?= base_url('student') ?>">
                <i class="material-icons text-primary">face</i>
                <?= uri_string(current_url()) == 'student' ?
                    '<b class="nav-link-text">Manajemen Siswa</b>' :
                    '<span class="nav-link-text text-muted">Manajemen Siswa</span>' ?>
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
            <a class="nav-link <?= uri_string(current_url()) == 'student-attendance' ? 'active' : '' ?>"
               href="<?= base_url('student-attendance') ?>">
                <i class="material-icons text-primary">assignment</i>
                <?= uri_string(current_url()) == 'student-attendance' ?
                    '<b class="nav-link-text">Riwayat Kehadiran Siswa</b>' :
                    '<span class="nav-link-text text-muted">Riwayat Kehadiran Siswa</span>' ?>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= uri_string(current_url()) == 'lecturer-attendance' ? 'active' : '' ?>"
               href="<?= base_url('lecturer-attendance') ?>">
                <i class="material-icons text-primary">assignment</i>
                <?= uri_string(current_url()) == 'lecturer-attendance' ?
                    '<b class="nav-link-text">Riwayat Kehadiran Anda</b>' :
                    '<span class="nav-link-text text-muted">Riwayat Kehadiran Anda</span>' ?>
            </a>
        </li>

    </ul>

</div>