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
                                        <h5 class="card-title text-uppercase text-muted mb-0">Data Prodi</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $studyProgramCount ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                            <i class="material-icons">school</i>
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
                                        <h5 class="card-title text-uppercase text-muted mb-0">Data Admin Prodi</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $studyProgramAdminCount ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                            <i class="material-icons">person</i>
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
                                        <h5 class="card-title text-uppercase text-muted mb-0">Data Kelas</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $classCount ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                            <i class="material-icons">class</i>
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
                                        <h5 class="card-title text-uppercase text-muted mb-0">Data Dosen</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $lecturerCount ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                            <i class="material-icons">supervisor_account</i>
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
                                        <h5 class="card-title text-uppercase text-muted mb-0">Data Jadwal</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $scheduleCount ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                            <i class="material-icons">event</i>
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
                                        <h5 class="card-title text-uppercase text-muted mb-0">Data Kehadiran</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $attendanceCount ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                            <i class="material-icons">assignment</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                <h5 class="h3 mb-0">Grafik Kehadiran</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="chart-attendance" class="chart-canvas"></canvas>
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
                                <h5 class="h3 mb-0">Penambahan Jadwal</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="chart-schedule" class="chart-canvas"></canvas>
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
                                <h3 class="mb-0">Kehadiran Terbaru</h3>
                            </div>
                            <div class="col text-right">
                                <a href="<?= base_url('attendance') ?>" class="btn btn-sm btn-primary">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Nama Mahasiswa</th>
                                <th scope="col">Kelas</th>
                                <th scope="col">Jadwal</th>
                                <th scope="col">Waktu Hadir</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($newestAttendanceList as $attendance): ?>
                                <tr>
                                    <th scope="row"><?= $attendance['name'] ?></th>
                                    <td><?= $attendance['class_name'] ?></td>
                                    <td><?= $attendance['schedule_name'] ?></td>
                                    <td><?= date('Y-m-d H:i:s', strtotime($attendance['created_on'])) ?></td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                        <?php if (empty($newestAttendanceList)): ?>
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
                                <h3 class="mb-0">Jadwal Kelas</h3>
                            </div>
                            <div class="col text-right">
                                <small>5 jadwal terbanyak</small>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Kelas</th>
                                <th scope="col">Total Jadwal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($classScheduleList as $class): ?>
                                <tr>
                                    <th scope="row"><?= $class['class_name'] ?></th>
                                    <td><?= $class['schedule_count'] ?> jadwal</td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                        <?php if (empty($classScheduleList)): ?>
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

    const attendanceChartLabel = <?= json_encode($attendanceGraphicData['dayList']) ?>;
    const attendanceChartData = <?= json_encode($attendanceGraphicData['data']) ?>;
    const scheduleChartLabel = <?= json_encode($scheduleGraphicData['monthList']) ?>;
    const scheduleChartData = <?= json_encode($scheduleGraphicData['data']) ?>;

    $(document).ready(function () {
        const attendanceChart = $('#chart-attendance');
        attendanceChart.data('chart', new Chart(attendanceChart, {
            type: 'line',
            data: {
                labels: attendanceChartLabel,
                datasets: [{
                    label: 'Total Kehadiran',
                    borderColor: "#f47f1f",
                    backgroundColor: "#f47f1f",
                    data: attendanceChartData
                }]
            }
        }));

        const scheduleChart = $('#chart-schedule');
        scheduleChart.data('chart', new Chart(scheduleChart, {
            type: 'bar',
            data: {
                labels: scheduleChartLabel,
                datasets: [{
                    label: 'Total Jadwal',
                    borderColor: "#f47f1f",
                    backgroundColor: "#f47f1f",
                    data: scheduleChartData
                }]
            }
        }));
    });
</script>