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
                        <h3 class="mb-0">Data Kehadiran</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Kelas</th>
                                    <th>Jadwal</th>
                                    <th>Waktu Hadir</th>
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

const sid = '<?= password_hash(session_id(), PASSWORD_DEFAULT) ?>';
var datatable;

$(document).ready(function() {

    datatable = $('#datatable').on('init.dt', function() {
        $('div.dataTables_length select').removeClass('custom-select custom-select-sm');
    }).DataTable({
        processing: true,
        ajaxSource: `<?= base_url('attendance/list') ?>?sid=${sid}`,
        columns: [{
                render: function(data, type, row, meta) {
                    return `${meta.row + 1}.`;
                }
            },
            {
                render: function(data, type, row, meta) {
                    return row['name'];
                }
            },
            {
                render: function(data, type, row, meta) {
                    return row['nim'];
                }
            },
            {
                render: function(data, type, row, meta) {
                    return row['class_name'];
                }
            },
            {
                render: function(data, type, row, meta) {
                    return row['schedule_name'];
                }
            },
            {
                render: function(data, type, row, meta) {
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
        buttons: [{
                extend: 'excel',
                className: 'hidden',
                title: 'Data Dosen',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },
            {
                extend: 'copy',
                className: 'hidden',
                title: 'Data Dosen',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },
            {
                extend: 'print',
                className: 'hidden',
                title: 'Data Dosen',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
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
</script>