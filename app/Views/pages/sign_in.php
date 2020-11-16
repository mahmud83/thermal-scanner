<!-- Head -->
<?= view('components/authentication/head') ?>

<!-- Main content -->
<div class="main-content">

    <!-- Header -->
    <div class="header bg-primary py-7 py-lg-8 pt-lg-9">
    </div>

    <!-- Page content -->
    <div class="container mt--9">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary border-0">
                    <div class="card-header bg-transparent pb-4">
                        <div class="text-center">
                            <img class="mt-2 auth-card-logo" src="assets/img/brand/logo.png" alt="logo"/>
                        </div>
                    </div>
                    <div class="card-body px-lg-5 py-lg-4">
                        <div id="form-signin-menu" class="hidden text-center">
                            <button class="btn btn-primary my-2" onclick="openSignInPoltek()">
                                Masuk sebagai Administrator Politeknik
                            </button>
                            <div class="text-center text-muted my-2">
                                <small>atau</small>
                            </div>
                            <button class="btn btn-primary my-2" onclick="openSignInProdi()" disabled>
                                Masuk sebagai Administrator Prodi
                            </button>
                        </div>
                        <div id="form-signin-poltek" class="hidden">
                            <div class="text-center text-muted mb-4">
                                <small>Masuk sebagai Administrator Politeknik</small>
                            </div>
                            <form action="<?= base_url('signin') ?>" role="form" method="POST">
                                <input type="hidden" name="type" value="1"/>
                                <div class="form-group mb-3 <?= ($validation->hasError('email') || $validation->hasError('credential')) && $session->getFlashdata('type') == 1 ? 'has-danger' : '' ?>">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="material-icons">email</i></span>
                                        </div>
                                        <input name="email"
                                               class="form-control <?= ($validation->hasError('email') || $validation->hasError('credential')) && $session->getFlashdata('type') == 1 ? 'is-invalid' : '' ?>"
                                               placeholder="Email" type="email"
                                               value="<?= $session->getFlashdata('email') && $session->getFlashdata('type') == 1 ? $session->getFlashdata('email') : '' ?>"
                                               required/>
                                        <div class="invalid-feedback"><?= $session->getFlashdata('type') == 1 ? $validation->getError('email') : '' ?></div>
                                    </div>
                                </div>
                                <div class="form-group <?= ($validation->hasError('password') || $validation->hasError('credential')) && $session->getFlashdata('type') == 1 ? 'has-danger' : '' ?>">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="material-icons">lock</i></span>
                                        </div>
                                        <input name="password"
                                               class="form-control <?= ($validation->hasError('password') || $validation->hasError('credential')) && $session->getFlashdata('type') == 1 ? 'is-invalid' : '' ?>"
                                               placeholder="Password" type="password"
                                               value="<?= $session->getFlashdata('password') && $session->getFlashdata('type') == 1 ? $session->getFlashdata('password') : '' ?>"
                                               required/>
                                        <div class="invalid-feedback"><?= $session->getFlashdata('type') == 1 ? $validation->getError('password') : '' ?></div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <small class="text-danger">
                                        <?= $validation->hasError('credential') && $session->getFlashdata('type') == 1
                                            ? 'Kami tidak dapat menemukan akun anda,<br/>mohon cek kembali...' : '' ?>
                                    </small>
                                </div>
                                <div class="text-center my-2">
                                    <button type="button" class="btn btn-secondary my-2" onclick="openSignInMenu(1)">
                                        Kembali
                                    </button>
                                    <button type="submit" class="btn btn-primary">Masuk</button>
                                </div>
                            </form>
                        </div>
                        <div id="form-signin-prodi" class="hidden">
                            <div class="text-center text-muted mb-4">
                                <small>Masuk sebagai Administrator Prodi</small>
                            </div>
                            <form action="<?= base_url('signin') ?>" role="form" method="POST">
                                <input type="hidden" name="type" value="2"/>
                                <div class="form-group mb-3 <?= ($validation->hasError('email') || $validation->hasError('credential') && $session->getFlashdata('type') == 2) ? 'has-danger' : '' ?>">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="material-icons">email</i></span>
                                        </div>
                                        <input name="email"
                                               class="form-control <?= ($validation->hasError('email') || $validation->hasError('credential') && $session->getFlashdata('type') == 2) ? 'is-invalid' : '' ?>"
                                               placeholder="Email" type="email"
                                               value="<?= $session->getFlashdata('email') && $session->getFlashdata('type') == 2 ? $session->getFlashdata('email') : '' ?>"
                                               required/>
                                        <div class="invalid-feedback"><?= $session->getFlashdata('type') == 2 ? $validation->getError('email') : '' ?></div>
                                    </div>
                                </div>
                                <div class="form-group mb-0 pb-2 <?= ($validation->hasError('password') || $validation->hasError('credential')) && $session->getFlashdata('type') == 2 ? 'has-danger' : '' ?>">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="material-icons">lock</i></span>
                                        </div>
                                        <input name="password"
                                               class="form-control <?= ($validation->hasError('password') || $validation->hasError('credential')) && $session->getFlashdata('type') == 2 ? 'is-invalid' : '' ?>"
                                               placeholder="Password" type="password"
                                               value="<?= $session->getFlashdata('password') && $session->getFlashdata('type') == 2 ? $session->getFlashdata('password') : '' ?>"
                                               required/>
                                        <div class="invalid-feedback"><?= $session->getFlashdata('type') == 2 ? $validation->getError('password') : '' ?></div>
                                    </div>
                                </div>
                                <div class="text-center pb-2">
                                    <small class="text-danger">
                                        <?= $validation->hasError('credential') && $session->getFlashdata('type') == 2
                                            ? 'Kami tidak dapat menemukan akun anda,<br/>mohon cek kembali...' : '' ?>
                                    </small>
                                </div>
                                <div class="text-center my-2">
                                    <button type="button" class="btn btn-secondary my-2" onclick="openSignInMenu(2)">
                                        Kembali
                                    </button>
                                    <button type="submit" class="btn btn-primary">Masuk</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col text-center">
                        Copyright &copy; <?= date('Y') ?>
                        <a href="http://www.poltekkesjakarta3.ac.id/" class="font-weight-bold ml-1" target="_blank"
                           rel="noreferrer noopener">
                            POLTEKKES Jakarta III
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    'use strict';
    
    const FADE_DELAY = 500;
    let SESSION_TYPE = <?= $session->getFlashdata('type'); ?>

    $(function () {
        if (SESSION_TYPE === 1) {
            openSignInPoltek();
        } else if (SESSION_TYPE === 2) {
            openSignInProdi();
        } else {
            openSignInMenu(0);
        }
    });

    function openSignInMenu(formType) {
        if (formType === 1) {
            $("#form-signin-poltek").fadeOut(FADE_DELAY, function () {
                $(this).addClass("hidden");
                $("#form-signin-menu").fadeIn(FADE_DELAY, function () {
                    $(this).addClass("visible");
                });
            });
        } else if (formType === 2) {
            $("#form-signin-prodi").fadeOut(FADE_DELAY, function () {
                $(this).addClass("hidden");
                $("#form-signin-menu").fadeIn(FADE_DELAY, function () {
                    $(this).addClass("visible");
                });
            });
        } else {
            $("#form-signin-menu").fadeIn(FADE_DELAY, function () {
                $(this).addClass("visible");
            });
        }
    }

    function openSignInPoltek() {
        $("#form-signin-menu").fadeOut(FADE_DELAY, function () {
            $(this).addClass("hidden");
            $("#form-signin-poltek").fadeIn(FADE_DELAY, function () {
                $(this).addClass("visible");
            });
        });
    }

    function openSignInProdi() {
        $("#form-signin-menu").fadeOut(FADE_DELAY, function () {
            $(this).addClass("hidden");
            $("#form-signin-prodi").fadeIn(FADE_DELAY, function () {
                $(this).addClass("visible");
            });
        });
    }
</script>

<!-- Footer -->
<?= view('components/authentication/footer') ?>
