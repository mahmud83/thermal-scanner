<!-- Header -->
<div class="header bg-primary pb-6"></div>

<!-- Table -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Profil Anda</h3>
                </div>
                <div class="py-4 px-4 px-lg-9 mx-lg-9 px-xl-9 mx-xl-9 text-center">
                    <form action="<?= base_url('profile') ?>" role="form" method="POST">
                        <input type="hidden" name="type" value="1"/>
                        <div class="form-group mb-3 <?= $validation->hasError('name') ? 'has-danger' : '' ?>">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">person</i></span>
                                </div>
                                <input name="name"
                                       class="form-control <?= $validation->hasError('name') ? 'is-invalid' : '' ?>"
                                       placeholder="Nama Anda" type="text"
                                       value="<?= $session->user_name ?>"
                                       autocomplete="off" role="presentation" required/>
                                <div class="invalid-feedback"><?= $validation->getError('name') ?></div>
                            </div>
                        </div>
                        <div class="form-group mb-3 <?= $validation->hasError('email') ? 'has-danger' : '' ?>">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">email</i></span>
                                </div>
                                <input name="email"
                                       class="form-control <?= $validation->hasError('email') ? 'is-invalid' : '' ?>"
                                       placeholder="Email Anda" type="email"
                                       value="<?= $session->user_email ?>"
                                       autocomplete="off" role="presentation"/>
                                <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                            </div>
                        </div>
                        <div id="password-frame" class="hidden">
                            <div class="form-group mb-3 <?= $validation->hasError('current_password') ? 'has-danger' : '' ?>">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="material-icons">lock</i></span>
                                    </div>
                                    <input id="current-password-input" name="current_password"
                                           class="form-control <?= $validation->hasError('current_password') ? 'is-invalid' : '' ?>"
                                           placeholder="Password Anda saat ini" type="password"
                                           autocomplete="off" role="presentation"/>
                                    <div class="invalid-feedback"><?= $validation->getError('current_password') ?></div>
                                </div>
                            </div>
                            <div class="form-group mb-3 <?= $validation->hasError('new_password') ? 'has-danger' : '' ?>">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="material-icons">lock</i></span>
                                    </div>
                                    <input id="new-password-input" name="new_password"
                                           class="form-control <?= $validation->hasError('new_password') ? 'is-invalid' : '' ?>"
                                           placeholder="Masukkan Password Baru" type="password"
                                           autocomplete="off" role="presentation"/>
                                    <div class="invalid-feedback"><?= $validation->getError('new_password') ?></div>
                                </div>
                            </div>
                            <div class="form-group mb-3 <?= $validation->hasError('confirm_new_password') ? 'has-danger' : '' ?>">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="material-icons">lock</i></span>
                                    </div>
                                    <input id="confirm-new-password-input" name="confirm_new_password"
                                           class="form-control <?= $validation->hasError('confirm_new_password') ? 'is-invalid' : '' ?>"
                                           placeholder="Konfirmasi Password Baru" type="password"
                                           autocomplete="off" role="presentation"/>
                                    <div class="invalid-feedback"><?= $validation->getError('confirm_new_password') ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <small class="text-danger">
                                <?= $session->getFlashdata('error') ?>
                            </small>
                            <small class="text-success">
                                <?= $session->getFlashdata('success') ? 'Profil berhasil disimpan' : '' ?>
                            </small>
                        </div>
                        <div class="text-center mt-4">
                            <button id="button-change-password" type="button" class="btn btn-sm btn-secondary my-2"
                                    onclick="openPasswordFrame()">
                                Ubah Password
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary">Simpan Profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?= view('components/default/footer') ?>

</div>

<script type="text/javascript">
    'use strict';

    const FADE_DELAY = 500;

    function openPasswordFrame() {
        $("#button-change-password").prop('disabled', true);
        $("#current-password-input").prop('required', true);
        $("#new-password-input").prop('required', true);
        $("#confirm-new-password-input").prop('required', true);
        $("#password-frame").fadeIn(FADE_DELAY, function () {
            $(this).removeClass('hidden').addClass('visible');
        });
    }
</script>