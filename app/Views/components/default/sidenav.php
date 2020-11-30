<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">

        <!-- Brand -->
        <div class="sidenav-header align-items-center">
            <a class="navbar-brand" href="<?= base_url('dashboard') ?>">
                <h2><b class="text-primary">Thermal Scanner</b></h2>
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
                        <a class="nav-link <?= uri_string(current_url()) == 'detection' ? 'active' : '' ?>"
                           href="<?= base_url('detection') ?>">
                            <i class="material-icons text-primary">pan_tool</i>
                            <?= uri_string(current_url()) == 'detection' ?
                                '<b class="nav-link-text">Riwayat Deteksi</b>' :
                                '<span class="nav-link-text text-muted">Riwayat Deteksi</span>' ?>
                        </a>
                    </li>

                </ul>

            </div>
        </div>

    </div>
</nav>
