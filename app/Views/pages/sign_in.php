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
                          <img class="mt-2 auth-card-logo" src="assets/img/brand/logo.png" />
                        </div>
                    </div>
                    <div class="card-body px-lg-5 py-lg-4">
                        <div class="text-center text-muted mb-4">
                            <small>Masuk sebagai Administrator</small>
                        </div>
                        <form action="<?= base_url('signin') ?>" role="form" method="POST">
                            <div class="form-group mb-3 <?= $validation->hasError('email') || $session->getFlashdata('credential_error') ? 'has-danger' : '' ?>">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="material-icons">email</i></span>
                                    </div>
                                    <input name="email" class="form-control <?= $validation->hasError('email') || $session->getFlashdata('credential_error') ? 'is-invalid' : '' ?>" placeholder="Email" type="email" value="<?= $session->getFlashdata('email') ? $session->getFlashdata('email') : '' ?>" required />
                                    <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                                </div>
                            </div>
                            <div class="form-group <?= $validation->hasError('password') || $session->getFlashdata('credential_error') ? 'has-danger' : '' ?>">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="material-icons">lock</i></span>
                                    </div>
                                    <input name="password" class="form-control <?= $validation->hasError('password') || $session->getFlashdata('credential_error') ? 'is-invalid' : '' ?>" placeholder="Password" type="password" value="<?= $session->getFlashdata('password') ? $session->getFlashdata('password') : '' ?>" required />
                                    <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                                </div>
                            </div>
                            <div class="text-center">
                                <small class="text-danger">
                                    <?= $session->getFlashdata('credential_error') ? '
                                        Alamat surel belum terdaftar, mohon cek kembali...
                                    ' : '' ?>
                                </small>
                            </div>
                            <div class="text-center">
                                <button type="button submit" class="btn btn-primary my-4">Masuk</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        Copyright &copy; <?= date('Y') ?> <a href="https://www.poltekkesjakarta3.ac.id/" class="font-weight-bold ml-1" target="_blank">POLTEKKES Jakarta III
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Footer -->
<?= view('components/authentication/footer') ?>
