<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary">
    <div class="container-fluid">
        <div class="collapse navbar-collapse text-left" id="navbarSupportedContent">

            <div class="navbar-search navbar-search-light form-inline mr-sm-3 d-none d-xl-block" id="navbar-search-main">
                <div><h6 class="h2 text-white mb-0"><?= $title ?></h6></div>
            </div>

            <ul class="navbar-nav align-items-center ml-md-auto">
                <li class="nav-item d-block d-xl-none ml-1">
                    <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </li>
                <li class="nav-item d-block d-xl-none">
                    <div><h6 class="h2 text-white ml-2 mb-0"><?= $title ?></h6></div>
                </li>
            </ul>

            <ul class="navbar-nav align-items-center ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                            <span class="avatar avatar-sm rounded-circle">
                                <img alt="Image placeholder" src="assets/img/theme/person-placeholder.png" />
                            </span>
                            <div class="media-body ml-2 d-none d-lg-block">
                                <span class="mb-0 text-sm font-weight-bold">Administrator</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="<?= base_url('profile') ?>" class="dropdown-item">
                            <i class="material-icons">account_circle</i>
                            <span>Profile</span>
                        </a>
                        <a href="<?= base_url('signout') ?>" class="dropdown-item">
                            <i class="material-icons">exit_to_app</i>
                            <span>Sign out</span>
                        </a>
                    </div>
                </li>
            </ul>

        </div>
    </div>
</nav>