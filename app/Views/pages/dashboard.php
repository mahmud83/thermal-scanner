<!-- Head -->
<?= view('components/default/head') ?>

<!-- Sidenav -->
<?= view('components/default/sidenav') ?>

<!-- Main content -->
<div class="main-content" id="panel">

    <!-- Topnav -->
    <?= view('components/default/topnav') ?>

    <!-- Header -->
    <div class="header bg-primary pb-5">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row py-4">

                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Data Deteksi</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $detectionCount ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                            <i class="material-icons">pan_tool</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Deteksi Suhu Normal</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $normalTempCount ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                            <i class="material-icons">done_outline</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card card-stats">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Deteksi Suhu Tinggi</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $highTempCount ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                            <i class="material-icons">warning</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6"></div>

                </div>
            </div>
        </div>
    </div>

    <!-- Page content -->
    <div class="container-fluid mt--6">

        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Ringkasan</h6>
                                <h5 class="h3 mb-0">Grafik Deteksi</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="chart-detection" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Performa</h6>
                                <h5 class="h3 mb-0">Deteksi Suhu Tinggi</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="chart-high-temp" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Deteksi Terbaru</h3>
                            </div>
                            <div class="col text-right">
                                <a href="<?= base_url('detection') ?>" class="btn btn-sm btn-primary">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <th scope="col">Suhu</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Waktu Deteksi</th>
                            </thead>
                            <tbody>
                            <?php foreach ($newestDetectionList as $newestDetection): ?>
                                <tr>
                                    <td scope="row"><?= $newestDetection['temperature'] ?> °C</td>
                                    <td><?= $newestDetection['temperature'] > 38 ?
                                            '<b class="text-danger">Suhu Tinggi</b>' :
                                            '<b class="text-success">Suhu Normal</b>' ?></td>
                                    <td><?= $newestDetection['created_on'] ?></td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                        <?php if (empty($newestDetectionList)): ?>
                            <div class="text-center mt-3 mb-3"><small>Belum ada data tersedia</small></div>
                        <?php endif ?>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Rata-Rata Suhu</h3>
                            </div>
                            <div class="col text-right">
                                <small>5 hari terakhir</small>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Tanggal Deteksi</th>
                                <th scope="col">Total Deteksi</th>
                                <th scope="col">Rata-Rata Suhu</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($highTempAvgList as $highTempAvg): ?>
                                <tr>
                                    <th scope="row"><?= $highTempAvg['created_on'] ?></th>
                                    <td><?= $highTempAvg['detection_count'] ?> Deteksi</td>
                                    <td><?= $highTempAvg['average_temperature'] ?></td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                        <?php if (empty($highTempAvgList)): ?>
                            <div class="text-center mt-3 mb-3"><small>Belum ada data tersedia</small></div>
                        <?php endif ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <?= view('components/default/footer') ?>

    </div>

</div>

<!-- JS -->
<?= view('components/default/js') ?>

<script type="text/javascript">
    'use strict';

    const detectionChartLabel = <?= json_encode($detectionGraphicData['dayList']) ?>;
    const detectionChartData = <?= json_encode($detectionGraphicData['data']) ?>;
    const highTempChartLabel = <?= json_encode($highTempGraphicData['monthList']) ?>;
    const highTempChartData = <?= json_encode($highTempGraphicData['data']) ?>;

    $(document).ready(function () {
        const detectionChart = $('#chart-detection');
        detectionChart.data('chart', new Chart(detectionChart, {
            type: 'line',
            data: {
                labels: detectionChartLabel,
                datasets: [{
                    label: 'Total Deteksi',
                    borderColor: "#f47f1f",
                    backgroundColor: "#f47f1f",
                    data: detectionChartData,
                    maxBarThickness: 10
                }]
            }
        }));

        const highTempChart = $('#chart-high-temp');
        highTempChart.data('chart', new Chart(highTempChart, {
            type: 'bar',
            data: {
                labels: highTempChartLabel,
                datasets: [{
                    label: 'Total Suhu Tinggi',
                    borderColor: "#f47f1f",
                    backgroundColor: "#f47f1f",
                    data: highTempChartData
                }]
            }
        }));
    });
</script>
