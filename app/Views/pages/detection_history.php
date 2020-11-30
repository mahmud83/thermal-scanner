<!-- Head -->
<?= view('components/default/head') ?>

<!-- Sidenav -->
<?= view('components/default/sidenav') ?>

<!-- Main content -->
<div class="main-content" id="panel">

    <!-- Topnav -->
    <?= view('components/default/topnav') ?>

    <!-- Header -->
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col">
                        <button type="button" onclick="exportExcel()" class="btn btn-sm btn-neutral">Ekspor</button>
                        <button type="button" onclick="copyData()" class="btn btn-sm btn-neutral">Salin</button>
                        <button type="button" onclick="printData()" class="btn btn-sm btn-neutral">Cetak</button>
                        <button type="button" onclick="refreshData()" class="btn btn-sm btn-neutral">Refresh</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Data Riwayat Deteksi Suhu</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable">
                            <thead class="thead-light">
                            <tr>
                                <th>No.</th>
                                <th>Temperatur</th>
                                <th>Waktu Deteksi</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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

    const AUTO_REFRESH_DELAY = 2500;
    const sid = '<?= password_hash(session_id(), PASSWORD_DEFAULT) ?>';
    let datatable;

    $(document).ready(function () {
        datatable = $('#datatable').on('init.dt', function () {
            $('div.dataTables_length select').removeClass('custom-select custom-select-sm');
        }).DataTable({
            processing: true,
            ajaxSource: `<?= base_url('detection/list') ?>?sid=${sid}`,
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return `${meta.row + 1}.`;
                    }
                },
                {
                    render: function (data, type, row, _) {
                        if (parseInt(row['temperature']) > 38) return `<b class="text-danger">${row['temperature']}</b> °C`;
                        return `<b class="text-success">${row['temperature']}</b> °C`;
                    }
                },
                {
                    render: function (data, type, row, _) {
                        return moment(new Date(row['created_on'])).format('DD/MM/YYYY hh:mm A');
                    }
                }
            ],
            order: [
                [0, 'asc']
            ],
            dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-3'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    extend: 'excel',
                    className: 'hidden',
                    title: 'Data Riwayat Deteksi Suhu',
                    exportOptions: {
                        columns: [0, 1, 2],
                        format: {
                            body: function (data, row, column, _) {
                                if (column === 0)
                                    return `${data.replace('.', '')}`;
                                if (column === 2)
                                    return `${moment(data, 'DD/MM/YYYY hh:mm A').format('DD/MM/YYYY HH:mm')}`;
                                return data;
                            }
                        }
                    }
                },
                {
                    extend: 'copy',
                    className: 'hidden',
                    title: 'Data Riwayat Deteksi Suhu',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'print',
                    className: 'hidden',
                    title: 'Data Riwayat Deteksi Suhu',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ baris per halaman",
                loadingRecords: "Memuat data...",
                processing: "Memuat data...",
                zeroRecords: "Tidak ada data yang tersedia",
                info: "Menampilkan entri _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data yang tersedia",
                infoFiltered: "(Disaring dari _MAX_ total data)",
                paginate: {
                    previous: "<i class='fas fa-angle-left'>",
                    next: "<i class='fas fa-angle-right'>"
                },
                buttons: {
                    copyTitle: 'Salin Data',
                    copySuccess: {
                        _: '%d baris disalin'
                    }
                }
            },
        });
        setInterval(function () {
            datatable.ajax.reload();
        }, AUTO_REFRESH_DELAY);
    });

    function exportExcel() {
        $(".buttons-excel")[0].click();
    }

    function copyData() {
        $(".buttons-copy")[0].click();
    }

    function printData() {
        $(".buttons-print")[0].click();
    }

    function refreshData() {
        datatable.ajax.reload();
    }
</script>
