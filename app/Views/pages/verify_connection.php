<!-- Head -->
<?= view('components/authentication/head') ?>

<!-- Main content -->
<div class="main-content">

    <!-- Header -->
    <div class="header bg-primary py-7 py-lg-8 pt-lg-9"></div>

    <!-- Page content -->
    <div class="container mt--7">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary border-0">
                    <div class="card-header bg-transparent pb-3">
                        <div class="text-center">
                            <h1><b class="text-primary">Thermal Scanner</b></h1>
                        </div>
                    </div>
                    <div class="card-body px-lg-5 py-lg-4">
                        <div id="label-loading" class="text-center mt-1">
                            <i class="fas fa-sync fa-spin" style="margin-right: .5rem;"></i>
                            <b>Menghubungkan ke perangkat...</b>
                        </div>
                        <div id="label-success" class="hidden text-center mt-1 mb-1">
                            <i class="text-success fas fa-check-circle" style="margin-right: .5rem;"></i>
                            <b class="text-success">Berhasil terhubung ke perangkat</b>
                        </div>
                        <div id="label-error" class="hidden text-center mt-1 mb-1">
                            <i class="text-danger fas fa-times-circle" style="margin-right: .5rem;"></i>
                            <b class="text-danger">Kesalahan telah terjadi!</b>
                        </div>
                        <div id="label-loading-hint" class="mt-4">
                            <small>
                                Mohon hubungkan perangkat pendeteksi suhu ke jaringan komputer ini.
                            </small>
                        </div>
                        <div id="label-error-hint" class="hidden mt-4">
                            <small>
                                Mohon refresh halaman untuk memulai ulang pemindaian perangkat.
                            </small>
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

<script>
    const SCAN_DELAY = 2 * 1000;        // 2 second
    const FADE_DELAY = 'slow';          // 0.5 second

    $(function () {
        setTimeout(initializeAckScan(), SCAN_DELAY);
    });

    function initializeAckScan() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url('api/ack/reset') ?>',
            data: {
                key: '<?= $device_key ?>'
            },
            success: scanDevice,
            error: notifyError
        });
    }

    function scanDevice() {
        let scanJob = setInterval(function () {
            $.ajax({
                url: '<?= base_url('api/ack') ?>?key=<?= $device_key ?>',
                success: function (response) {
                    if (response['success']) {
                        notifySuccess();
                        clearInterval(scanJob);
                    }
                }
            });
        }, SCAN_DELAY);
    }

    function notifySuccess() {
        $("#label-loading").fadeOut(FADE_DELAY, function () {
            $(this).addClass("hidden");
        });
        $("#label-loading-hint").fadeOut(FADE_DELAY, function () {
            $(this).addClass("hidden");
            $("#label-success").fadeIn(FADE_DELAY, function () {
                $(this).removeClass("hidden").addClass("visible");
                setTimeout(function () {
                    window.location.replace('<?= base_url('dashboard') ?>');
                }, 1000);
            });
        });
    }

    function notifyError() {
        $("#label-loading").fadeOut(FADE_DELAY, function () {
            $(this).addClass("hidden");
        });
        $("#label-loading-hint").fadeOut(FADE_DELAY, function () {
            $(this).addClass("hidden");
            $("#label-error").fadeIn(FADE_DELAY, function () {
                $(this).removeClass("hidden").addClass("visible");
            });
            $("#label-error-hint").fadeIn(FADE_DELAY, function () {
                $(this).removeClass("hidden").addClass("visible");
            });
        });
    }
</script>

<!-- Footer -->
<?= view('components/authentication/footer') ?>
