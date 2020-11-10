<!-- Head -->
<?= view('components/default/head') ?>

<!-- Sidenav -->
<?= view('components/default/sidenav') ?>

<!-- Main content -->
<?= $session->user_type == 1 ? view('pages/poltek/dashboard') : view('pages/prodi/dashboard'); ?>
