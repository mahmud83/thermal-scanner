<!-- Head -->
<?= view('components/authentication/head') ?>

<!-- Main content -->
<div class="main-content">

    <!-- Header -->
    <div class="header bg-primary py-7 py-lg-8 pt-lg-9">
    </div>

    <!-- Page content -->
    <div class="container mt--8">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary border-0">
                    <div class="card-header bg-transparent pb-3">
                        <div class="text-center">
                            <h1><b class="text-primary">Thermal Scanner</b></h1>
                        </div>
                    </div>
                    <div class="card-body px-lg-5 py-lg-4">
                        <div id="form-signin">
                            <div class="text-center text-muted mb-4">
                                <small>Masuk sebagai Administrator</small>
                            </div>
                            <form action="<?= base_url('signin') ?>" role="form" method="POST">
                                <input type="hidden" name="type" value="1"/>
                                <div class="form-group mb-3 <?= ($validation->hasError('email') || $validation->hasError('credential')) && $session->getFlashdata('type') == 1 ? 'has-danger' : '' ?>">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="material-icons">email</i></span>
                                        </div>
                                        <input name="email"
                                               autocomplete="current-email"
                                               class="form-control <?= ($validation->hasError('email') || $validation->hasError('credential')) && $session->getFlashdata('type') == 1 ? 'is-invalid' : '' ?>"
                                               placeholder="Email" type="email"
                                               value="<?= $session->getFlashdata('email') && $session->getFlashdata('type') == 1 ? $session->getFlashdata('email') : '' ?>"
                                               required/>
                                        <div class="invalid-feedback"><?= $session->getFlashdata('type') == 1 ? $validation->getError('email') : '' ?></div>
                                    </div>
                                </div>
                                <div class="form-group mb-0 pb-2 <?= ($validation->hasError('password') || $validation->hasError('credential')) && $session->getFlashdata('type') == 1 ? 'has-danger' : '' ?>">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="material-icons">lock</i></span>
                                        </div>
                                        <input name="password"
                                               autocomplete="current-password"
                                               class="form-control <?= ($validation->hasError('password') || $validation->hasError('credential')) && $session->getFlashdata('type') == 1 ? 'is-invalid' : '' ?>"
                                               placeholder="Password" type="password"
                                               value="<?= $session->getFlashdata('password') && $session->getFlashdata('type') == 1 ? $session->getFlashdata('password') : '' ?>"
                                               required/>
                                        <div class="invalid-feedback"><?= $session->getFlashdata('type') == 1 ? $validation->getError('password') : '' ?></div>
                                    </div>
                                </div>
                                <div class="text-center pb-2">
                                    <small class="text-danger">
                                        <?= $validation->hasError('credential') && $session->getFlashdata('type') == 1
                                            ? 'Kami tidak dapat menemukan akun anda,<br/>mohon cek kembali...' : '' ?>
                                    </small>
                                </div>
                                <div class="text-center my-2">
                                    <button type="submit" class="btn btn-primary">Masuk</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col text-center">
                        Copyright &copy; <?= date('Y') ?> Museum Listrik dan Energi Baru
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?= view('components/authentication/footer') ?>
