<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">

        <!-- Brand -->
        <div class="sidenav-header align-items-center">
            <a class="navbar-brand" href="javascript:void(0)">
                <img src="assets/img/brand/logo.png" class="navbar-brand-img" alt="Aplikasi Absensi"/>
            </a>
        </div>

        <div class="navbar-inner">
            <!-- Collapse -->
            <?= $session->user_type == 1 ? view('components/default/poltek/sidenav') : view('components/default/prodi/sidenav') ?>
        </div>

    </div>
</nav>
