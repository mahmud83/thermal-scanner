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

    <?= $session->user_type == 1 ? view('pages/poltek/student_attendance') : view('pages/prodi/student_attendance'); ?>

</div>
