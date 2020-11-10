<!-- Head -->
<?= view('components/default/head') ?>

<!-- Sidenav -->
<?= view('components/default/sidenav') ?>

<!-- Main content -->
<div class="main-content" id="panel">

    <!-- Topnav -->
    <?= view('components/default/topnav') ?>

    <!-- JS -->
    <?= view('components/default/js') ?>

    <?= $session->user_type == 1 ? view('pages/poltek/edit_profile') : view('pages/prodi/edit_profile'); ?>

</div>
