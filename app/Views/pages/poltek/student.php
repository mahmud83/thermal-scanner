<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-md-12">
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-neutral dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Tambah
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdown-add-button">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#add-modal">
                                Data Satuan
                            </a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#add-batch-modal">
                                Data Batch
                            </a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-neutral dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Impor
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdown-add-button">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#import-modal">
                                Semua Data
                            </a>
                            <a class="dropdown-item" href="#" disabled>
                                Data Spesifik
                            </a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-neutral dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Ekspor
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdown-add-button">
                            <a class="dropdown-item" href="#" onclick="exportExcel()">
                                Semua Data
                            </a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#export-specific-modal">
                                Data Spesifik
                            </a>
                        </div>
                    </div>
                    <button type="button" onclick="copyData()" class="btn btn-sm btn-neutral">Salin</button>
                    <button type="button" onclick="printData()" class="btn btn-sm btn-neutral">Cetak</button>
                </div>
                <div class="col-lg-6 col-md-12 mt-4 mt-lg-0 text-right">
                    <button type="button" data-toggle="modal" data-target="#truncate-modal"
                            class="btn btn-sm btn-danger">Hapus Semua
                    </button>
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
                    <h3 class="mb-0">Data Mahasiswa</h3>
                </div>
                <div class="table-responsive py-4">
                    <table class="table table-flush" id="datatable">
                        <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Kelas</th>
                            <th>Prodi</th>
                            <th>Semester</th>
                            <th>Tanggal Ditambahkan</th>
                            <th></th>
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

<!-- Modal -->
<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="add-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mahasiswa Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add-form" role="form">
                <div class="modal-body mt--4">
                    <div class="add-form-group form-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">person</i></span>
                            </div>
                            <input id="add-form-input-name" name="name" class="add-form-input form-control"
                                   placeholder="Nama Mahasiswa" type="text" autocomplete="off" role="presentation"
                                   required/>
                        </div>
                    </div>
                    <div class="add-form-group form-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">money</i></span>
                            </div>
                            <input id="add-form-input-nip" name="nim" class="add-form-input form-control"
                                   placeholder="NIM Mahasiswa" type="text" autocomplete="off" role="presentation"
                                   required/>
                        </div>
                    </div>
                    <div class="add-form-group form-group mb-3">
                        <div class="input-group">
                            <select id="add-form-input-class"
                                    class="add-form-input form-control" placeholder="Pilih Prodi & Kelas"
                                    data-toggle="select" data-live-search="true" required>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="add-form-group form-group mb-0">
                        <div class="input-group">
                            <select id="add-form-input-semester"
                                    class="add-form-input form-control" placeholder="Pilih Semester"
                                    data-toggle="select"
                                    data-live-search="true" required>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="add-form-error text-center mt-4 hidden">
                        <small class="text-danger">
                            Terjadi kesalahan saat menambah data, mohon coba lagi...
                        </small>
                    </div>
                </div>
                <div class="modal-footer mt--4">
                    <button id="cancel-add-button" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal">Batal
                    </button>
                    <button id="add-button" type="submit" class="btn btn-sm btn-primary">
                        <span class="btn-spinner mr-1 hidden"><i class="fa fa-spinner fa-spin"></i></i></span>
                        <span class="btn-text">Tambah</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="add-batch-modal" tabindex="-1" role="dialog" aria-labelledby="add-batch-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Batch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add-batch-form" role="form">
                <div class="modal-body mt--4">
                    <div class="mb-3">
                        <small>
                            Tambah data batch mahasiswa baru dengan file CSV atau Excel.<br/>
                            Pastikan data sudah sesuai template yang tersedia.
                        </small>
                    </div>
                    <div class="add-batch-form-group form-group mt-2 mb-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">folder</i></span>
                            </div>
                            <input id="add-batch-form-input-file" name="file" class="add-batch-form-input form-control"
                                   type="file" accept=".xls,.xlsx,.csv" required/>
                        </div>
                    </div>
                    <div class="add-batch-form-error text-center mt-4 hidden">
                        <small class="text-danger">
                            Terjadi kesalahan saat menambah data batch data, mohon coba lagi...
                        </small>
                    </div>
                </div>
                <div class="modal-footer mt--4">
                    <button id="cancel-add-batch-button" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal">Batal
                    </button>
                    <button id="template-add-batch-button" type="button" onclick="exportExcel()"
                            class="btn btn-sm btn-neutral" data-dismiss="modal">Unduh Template
                    </button>
                    <button id="add-batch-button" type="submit" class="btn btn-sm btn-primary">
                        <span class="btn-spinner mr-1 hidden"><i class="fa fa-spinner fa-spin"></i></i></span>
                        <span class="btn-text">Tambah Batch</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-form" role="form">
                <div class="modal-body mt--4">
                    <input id="edit-form-row" name="dataTableRow" type="number" hidden/>
                    <input id="edit-form-id" name="id" type="number" hidden/>
                    <div class="edit-form-group form-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">person</i></span>
                            </div>
                            <input id="edit-form-input-name" name="name" class="edit-form-input form-control"
                                   placeholder="Nama Mahasiswa" type="text" autocomplete="off" role="presentation"
                                   required/>
                        </div>
                    </div>
                    <div class="edit-form-group form-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">money</i></span>
                            </div>
                            <input id="edit-form-input-nim" name="nim" class="edit-form-input form-control"
                                   placeholder="NIM Mahasiswa" type="text" autocomplete="off" role="presentation"
                                   required/>
                        </div>
                    </div>
                    <div class="edit-form-group form-group mb-3">
                        <div class="input-group">
                            <select id="edit-form-input-class"
                                    class="edit-form-input form-control" placeholder="Pilih Prodi & Kelas"
                                    data-toggle="select"
                                    data-live-search="true" required>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="edit-form-group form-group mb-0">
                        <div class="input-group">
                            <select id="edit-form-input-semester"
                                    class="edit-form-input form-control" placeholder="Pilih Semester"
                                    data-toggle="select"
                                    data-live-search="true" required>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="edit-form-error text-center mt-4 hidden">
                        <small class="text-danger">
                            Terjadi kesalahan saat mengedit data, mohon coba lagi...
                        </small>
                    </div>
                </div>
                <div class="modal-footer mt--4">
                    <button id="cancel-edit-button" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal">Batal
                    </button>
                    <button id="edit-button" type="submit" class="btn btn-sm btn-primary">
                        <span class="btn-spinner mr-1 hidden"><i class="fa fa-spinner fa-spin"></i></i></span>
                        <span class="btn-text">Edit</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="delete-form" role="form">
                <div class="modal-body mt--4">
                    <input id="delete-form-row" name="dataTableRow" type="number" hidden/>
                    <input id="delete-form-id" name="id" type="number" hidden/>
                    <small><b>Anda yakin ingin menghapus dosen ini?<br/>Aksi ini tidak dapat
                            dibatalkan.</b></small>
                    <div class="delete-form-error text-center mt-4 hidden">
                        <small class="text-danger">
                            Terjadi kesalahan saat menghapus data, mohon coba lagi...
                        </small>
                    </div>
                </div>
                <div class="modal-footer mt--4">
                    <button id="cancel-delete-button" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal">Batal
                    </button>
                    <button id="delete-button" type="submit" class="btn btn-sm btn-danger">
                        <span class="btn-spinner mr-1 hidden"><i class="fa fa-spinner fa-spin"></i></i></span>
                        <span class="btn-text">Hapus</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="import-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Impor Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="import-form" role="form">
                <div class="modal-body mt--4">
                    <div class="mb-3">
                        <small>
                            Impor data mahasiswa dengan file CSV atau Excel.<br/>
                            Pastikan data sudah sesuai template yang tersedia.
                        </small>
                    </div>
                    <div class="import-form-group form-group mt-2 mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">folder</i></span>
                            </div>
                            <input id="import-form-input-file" name="file" class="import-form-input form-control"
                                   type="file" accept=".xls,.xlsx,.csv" required/>
                        </div>
                    </div>
                    <div class="mb-0">
                        <small><b>Proses impor data akan mengganti semua data lama dengan data baru yang akan
                                diimpor!</b></small>
                    </div>
                    <div class="import-form-error text-center mt-4 hidden">
                        <small class="text-danger">
                            Terjadi kesalahan saat mengimpor data, mohon coba lagi...
                        </small>
                    </div>
                </div>
                <div class="modal-footer mt--4">
                    <button id="cancel-import-button" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal">Batal
                    </button>
                    <button id="template-import-button" type="button" onclick="exportExcel()"
                            class="btn btn-sm btn-neutral" data-dismiss="modal">Unduh Template
                    </button>
                    <button id="import-button" type="submit" class="btn btn-sm btn-primary">
                        <span class="btn-spinner mr-1 hidden"><i class="fa fa-spinner fa-spin"></i></i></span>
                        <span class="btn-text">Impor</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="truncate-modal" tabindex="-1" role="dialog" aria-labelledby="truncate-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Semua Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="truncate-form" role="form">
                <div class="modal-body mt--4">
                    <small><b>Anda yakin ingin menghapus semua dosen?<br/>Aksi ini tidak dapat
                            dibatalkan.</b></small>
                    <div class="truncate-form-error text-center mt-4 hidden">
                        <small class="text-danger">
                            Terjadi kesalahan saat menghapus data, mohon coba lagi...
                        </small>
                    </div>
                </div>
                <div class="modal-footer mt--4">
                    <button id="cancel-truncate-button" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal">Batal
                    </button>
                    <button id="truncate-button" type="submit" class="btn btn-sm btn-danger">
                        <span class="btn-spinner mr-1 hidden"><i class="fa fa-spinner fa-spin"></i></i></span>
                        <span class="btn-text">Hapus</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="export-specific-modal" tabindex="-1" role="dialog" aria-labelledby="export-specific-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ekspor Data Spesifik</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="export-specific-form" role="form">
                <div class="modal-body mt--4">
                    <div class="mb-3">
                        <small>
                            Saring data mahasiswa spesifik yang ingin anda ekspor.
                            Kosongkan kolom untuk mengabaikan penyaringan data pada kolom tersebut.
                        </small>
                    </div>
                    <div class="mb-3">
                        <small><b>Prodi & Kelas</b></small>
                        <div class="export-specific-form-group form-group mt-2">
                            <div class="input-group">
                                <select id="export-specific-form-input-class"
                                        class="export-specific-form-input form-control"
                                        placeholder="Pilih Prodi & Kelas"
                                        data-toggle="select" data-live-search="true" multiple="multiple">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <small><b>Semester</b></small>
                        <div class="export-specific-form-group form-group mt-2">
                            <div class="input-group">
                                <select id="export-specific-form-input-semester"
                                        class="export-specific-form-input form-control" placeholder="Pilih Semester"
                                        data-toggle="select" data-live-search="true" multiple="multiple">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="export-specific-form-error text-center mt-4 hidden">
                        <small class="text-danger">
                            Terjadi kesalahan saat mengekspor data spesifik, mohon coba lagi...
                        </small>
                    </div>
                </div>
                <div class="modal-footer mt--4">
                    <button id="cancel-export-specific-button" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal">Batal
                    </button>
                    <button id="export-specific-button" type="submit" class="btn btn-sm btn-primary">
                        <span class="btn-spinner mr-1 hidden"><i class="fa fa-spinner fa-spin"></i></i></span>
                        <span class="btn-text">Ekspor</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    'use strict';

    const sid = '<?= password_hash(session_id(), PASSWORD_DEFAULT) ?>';
    let datatable;

    $(document).ready(function () {

        datatable = $('#datatable').on('init.dt', function () {
            $('div.dataTables_length select').removeClass('custom-select custom-select-sm');
        }).DataTable({
            processing: true,
            ajaxSource: `<?= base_url('student/list') ?>?sid=${sid}`,
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return `${meta.row + 1}.`;
                    }
                },
                {
                    render: function (data, type, row, _) {
                        return row['name'];
                    }
                },
                {
                    render: function (data, type, row, _) {
                        return row['nim'];
                    }
                },
                {
                    render: function (data, type, row, _) {
                        return row['class_name'];
                    }
                },
                {
                    render: function (data, type, row, _) {
                        return row['study_program_name'];
                    }
                },
                {
                    render: function (data, type, row, _) {
                        return row['semester_name'];
                    }
                },
                {
                    render: function (data, type, row, _) {
                        return moment(new Date(row['created_on'])).format('DD/MM/YYYY hh:mm A');
                    }
                },
                {
                    render: function (data, type, row, meta) {
                        return `
                        <div class="dropdown">
                            <button class="btn btn-sm btn-neutral btn-icon-only" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <button onclick="performEditData(${meta.row})" class="dropdown-item">Edit Mahasiswa</button>
                                <button onclick="performDeleteData(${meta.row})" class="dropdown-item">Hapus Mahasiswa</button>
                            </div>
                        </div>
                    `;
                    }
                }
            ],
            columnDefs: [{
                searchable: false,
                orderable: false,
                targets: 7
            }],
            order: [
                [0, 'asc']
            ],
            dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-3'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [{
                extend: 'excel',
                className: 'hidden',
                title: 'Data Mahasiswa',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6],
                    format: {
                        body: function (data, row, column, _) {
                            if (column === 0)
                                return `${data.replace('.', '')}`;
                            if (column === 6)
                                return `${moment(data, 'DD/MM/YYYY hh:mm A').format('DD/MM/YYYY HH:mm')}`;
                            return data;
                        }
                    }
                }
            },
                {
                    extend: 'copy',
                    className: 'hidden',
                    title: 'Data Mahasiswa',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'print',
                    className: 'hidden',
                    title: 'Data Mahasiswa',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
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

        $('#add-form').on('submit', function () {
            addData(this);
            return false;
        });

        $('#add-batch-form').on('submit', function () {
            addBatchData(this);
            return false;
        });

        $('#edit-form').on('submit', function () {
            editData(this);
            return false;
        });

        $('#delete-form').on('submit', function () {
            deleteData(this);
            return false;
        });

        $('#import-form').on('submit', function () {
            importExcel(this);
            return false;
        });

        $('#truncate-form').on('submit', function () {
            deleteAllData(this);
            return false;
        });

        $('#export-specific-form').on('submit', function () {
            exportSpecificExcel(this);
            return false;
        });

        $('#add-modal').on('hidden.bs.modal', function () {
            $("#add-form").trigger('reset');
            $('.add-form-group').removeClass('has-danger');
            $('.add-form-input').removeClass('is-invalid');
            $('.add-form-error').addClass('hidden');
        });

        $('#add-batch-modal').on('hidden.bs.modal', function () {
            $("#add-batch-form").trigger('reset');
            $('.add-batch-form-group').removeClass('has-danger');
            $('.add-batch-form-input').removeClass('is-invalid');
            $('.add-batch-form-error').addClass('hidden');
        });

        $('#edit-modal').on('hidden.bs.modal', function () {
            $('#edit-form').trigger('reset');
            $('.edit-form-group').removeClass('has-danger');
            $('.edit-form-input').removeClass('is-invalid');
            $('.edit-form-error').addClass('hidden');
        });

        $('#delete-modal').on('hidden.bs.modal', function () {
            $('#delete-form').trigger('reset');
            $('.delete-form-group').removeClass('has-danger');
            $('.delete-form-input').removeClass('is-invalid');
            $('.delete-form-error').addClass('hidden');
        });

        $('#import-modal').on('hidden.bs.modal', function () {
            $('#import-form').trigger('reset');
            $('.import-form-group').removeClass('has-danger');
            $('.import-form-input').removeClass('is-invalid');
            $('.import-form-error').addClass('hidden');
        });

        $('#truncate-modal').on('hidden.bs.modal', function () {
            $('#truncate-form').trigger('reset');
            $('.truncate-form-group').removeClass('has-danger');
            $('.truncate-form-input').removeClass('is-invalid');
            $('.truncate-form-error').addClass('hidden');
        });

        $('#export-specific-modal').on('hidden.bs.modal', function () {
            $('#export-specific-form').trigger('reset');
            $('.export-specific-form-error').addClass('hidden');
            $('#export-specific-form-input-class').val(null).trigger('change');
            $('#export-specific-form-input-semester').val(null).trigger('change');
            $('#export-specific-form-input-lecturer').val(null).trigger('change');
            $('#export-specific-form-input-date-start').datetimepicker('clear');
            $('#export-specific-form-input-date-end').datetimepicker('clear');
        });

        $('#add-form-input-class').select2({
            placeholder: 'Pilih Prodi & Kelas',
            language: {
                searching: function () {
                    return 'Memuat data...';
                }
            },
            allowClear: true,
            ajax: {
                url: `<?= base_url('class/list') ?>`,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        sid: sid,
                        search: params.term
                    };
                },
                processResults: function (datas) {
                    return {
                        results: datas['data'].map(function (data) {
                            return {
                                id: data['id'],
                                text: `${data['study_program_name']} - ${data['name']}`
                            };
                        })
                    };
                },
                cache: true
            }
        });

        $('#add-form-input-semester').select2({
            placeholder: 'Pilih Semester',
            language: {
                searching: function () {
                    return 'Memuat data...';
                }
            },
            allowClear: true,
            ajax: {
                url: `<?= base_url('semester/list') ?>`,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        sid: sid,
                        search: params.term
                    };
                },
                processResults: function (datas) {
                    return {
                        results: datas['data'].map(function (data) {
                            return {
                                id: data['id'],
                                text: data['name']
                            };
                        })
                    };
                },
                cache: true
            }
        });

        $('#edit-form-input-class').select2({
            placeholder: 'Pilih Prodi & Kelas',
            language: {
                searching: function () {
                    return 'Memuat data...';
                }
            },
            allowClear: true,
            ajax: {
                url: `<?= base_url('class/list') ?>`,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        sid: sid,
                        search: params.term
                    };
                },
                processResults: function (datas) {
                    return {
                        results: datas['data'].map(function (data) {
                            return {
                                id: data['id'],
                                text: `${data['study_program_name']} - ${data['name']}`
                            };
                        })
                    };
                },
                cache: true
            }
        });

        $('#edit-form-input-semester').select2({
            placeholder: 'Pilih Semester',
            language: {
                searching: function () {
                    return 'Memuat data...';
                }
            },
            allowClear: true,
            ajax: {
                url: `<?= base_url('semester/list') ?>`,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        sid: sid,
                        search: params.term
                    };
                },
                processResults: function (datas) {
                    return {
                        results: datas['data'].map(function (data) {
                            return {
                                id: data['id'],
                                text: data['name']
                            };
                        })
                    };
                },
                cache: true
            }
        });

        $('#export-specific-form-input-class').select2({
            placeholder: 'Pilih Prodi & Kelas',
            language: {
                searching: function () {
                    return 'Memuat data...';
                }
            },
            multiple: true,
            allowClear: true,
            ajax: {
                url: `<?= base_url('class/list') ?>`,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        sid: sid,
                        search: params.term
                    };
                },
                processResults: function (datas) {
                    return {
                        results: datas['data'].map(function (data) {
                            return {
                                id: data['id'],
                                text: `${data['study_program_name']} - ${data['name']}`
                            };
                        })
                    };
                },
                cache: true
            }
        });

        $('#export-specific-form-input-semester').select2({
            placeholder: 'Pilih Semester',
            language: {
                searching: function () {
                    return 'Memuat data...';
                }
            },
            multiple: true,
            allowClear: true,
            ajax: {
                url: `<?= base_url('semester/list') ?>`,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        sid: sid,
                        search: params.term
                    };
                },
                processResults: function (datas) {
                    return {
                        results: datas['data'].map(function (data) {
                            return {
                                id: data['id'],
                                text: data['name']
                            };
                        })
                    };
                },
                cache: true
            }
        });
    });

    function performEditData(targetRow) {
        const data = datatable.row(targetRow).data();
        $('#edit-form-row').val(targetRow);
        $('#edit-form-id').val(data['id']);
        $('#edit-form-input-name').val(data['name']);
        $('#edit-form-input-nim').val(data['nip']);
        $('#edit-form-input-class').append($("<option/>").val(data['class_id']).text(`${data['study_program_name']} - ${data['class_name']}`)).val(data['class_id']).trigger('change');
        $('#edit-form-input-semester').append($("<option/>").val(data['semester_id']).text(data['semester_name'])).val(data['semester_id']).trigger('change');
        $('#edit-modal').modal('show');
    }

    function performDeleteData(targetRow) {
        const data = datatable.row(targetRow).data();
        $('#delete-form-row').val(targetRow);
        $('#delete-form-id').val(data['id']);
        $('#delete-modal').modal('show');
    }

    function addData(form) {
        const formData = new FormData(form);
        formData.append('sid', sid);
        formData.append('class_id', $('#add-form-input-class').select2('data')[0]['id']);
        formData.append('semester_id', $('#add-form-input-semester').select2('data')[0]['id']);
        $('#cancel-add-button').attr('disabled', true);
        $('#add-button').attr('disabled', true);
        $('#add-button .btn-spinner').removeClass('hidden');
        $.ajax({
            type: 'POST',
            url: `<?= base_url('student/add') ?>`,
            data: formData,
            contentType: false,
            processData: false
        }).done(function (insertedRow) {
            $('.add-form-group').removeClass('has-danger');
            $('.add-form-input').removeClass('is-invalid');
            $('.add-form-error').addClass('hidden');
            $('#add-modal').modal('hide');
            datatable.row.add({
                'id': insertedRow['id'],
                'name': insertedRow['name'],
                'nim': insertedRow['nim'],
                'class_id': insertedRow['class_id'],
                'class_name': insertedRow['class_name'],
                'study_program_id': insertedRow['study_program_id'],
                'study_program_name': insertedRow['study_program_name'],
                'semester_id': insertedRow['semester_id'],
                'semester_name': insertedRow['semester_name'],
                'created_on': insertedRow['created_on']
            }).invalidate().draw();
        }).fail(function () {
            $('.add-form-group').addClass('has-danger');
            $('.add-form-input').addClass('is-invalid');
            $('.add-form-error').removeClass('hidden');
        }).always(function () {
            $('#cancel-add-button').attr('disabled', false);
            $('#add-button').attr('disabled', false);
            $('#add-button .btn-spinner').addClass('hidden');
        });
    }

    function addBatchData(form) {
        const formData = new FormData(form);
        formData.append('sid', sid);
        $('#cancel-add-batch-button').attr('disabled', true);
        $('#template-add-batch-button').attr('disabled', true);
        $('#add-batch-button').attr('disabled', true);
        $('#add-batch-button .btn-spinner').removeClass('hidden');
        $.ajax({
            type: 'POST',
            url: `<?= base_url('student/add-batch') ?>`,
            data: formData,
            contentType: false,
            processData: false
        }).done(function () {
            $('.add-batch-form-group').removeClass('has-danger');
            $('.add-batch-form-input').removeClass('is-invalid');
            $('.add-batch-form-error').addClass('hidden');
            $('#add-batch-modal').modal('hide');
            datatable.ajax.reload();
        }).fail(function () {
            $('.add-batch-form-group').addClass('has-danger');
            $('.add-batch-form-input').addClass('is-invalid');
            $('.add-batch-form-error').removeClass('hidden');
        }).always(function () {
            $('#cancel-add-batch-button').attr('disabled', false);
            $('#template-add-batch-button').attr('disabled', false);
            $('#add-batch-button').attr('disabled', false);
            $('#add-batch-button .btn-spinner').addClass('hidden');
        });
    }

    function editData(form) {
        const formData = new FormData(form);
        const targetRow = parseInt(formData.get('dataTableRow'));
        formData.append('sid', sid);
        formData.append('class_id', $('#add-form-input-class').select2('data')[0]['id']);
        formData.append('semester_id', $('#add-form-input-semester').select2('data')[0]['id']);
        $('#cancel-edit-button').attr('disabled', true);
        $('#edit-button').attr('disabled', true);
        $('#edit-button .btn-spinner').removeClass('hidden');
        $.ajax({
            type: 'POST',
            url: `<?= base_url('student/edit') ?>`,
            data: formData,
            contentType: false,
            processData: false
        }).done(function (updatedRow) {
            $('.edit-form-group').removeClass('has-danger');
            $('.edit-form-input').removeClass('is-invalid');
            $('.edit-form-error').addClass('hidden');
            $('#edit-modal').modal('hide');
            datatable.row(targetRow).data({
                'id': updatedRow['id'],
                'name': updatedRow['name'],
                'nim': updatedRow['nim'],
                'class_id': updatedRow['class_id'],
                'class_name': updatedRow['class_name'],
                'study_program_id': updatedRow['study_program_id'],
                'study_program_name': updatedRow['study_program_name'],
                'semester_id': updatedRow['semester_id'],
                'semester_name': updatedRow['semester_name'],
                'created_on': updatedRow['created_on']
            }).invalidate().draw();
        }).fail(function () {
            $('.edit-form-group').addClass('has-danger');
            $('.edit-form-input').addClass('is-invalid');
            $('.edit-form-error').removeClass('hidden');
        }).always(function () {
            $('#cancel-edit-button').attr('disabled', false);
            $('#edit-button').attr('disabled', false);
            $('#edit-button .btn-spinner').addClass('hidden');
        });
    }

    function deleteData(form) {
        const formData = new FormData(form);
        const targetRow = parseInt(formData.get('dataTableRow'));
        formData.append('sid', sid);
        $('#cancel-delete-button').attr('disabled', true);
        $('#delete-button').attr('disabled', true);
        $('#delete-button .btn-spinner').removeClass('hidden');
        $.ajax({
            type: 'POST',
            url: `<?= base_url('student/delete') ?>`,
            data: formData,
            contentType: false,
            processData: false
        }).done(function () {
            $('.delete-form-group').removeClass('has-danger');
            $('.delete-form-input').removeClass('is-invalid');
            $('.delete-form-error').addClass('hidden');
            $('#delete-modal').modal('hide');
            datatable.row(targetRow).remove().draw();
            datatable.rows().invalidate();
        }).fail(function () {
            $('.delete-form-group').addClass('has-danger');
            $('.delete-form-input').addClass('is-invalid');
            $('.delete-form-error').removeClass('hidden');
        }).always(function () {
            $('#cancel-delete-button').attr('disabled', false);
            $('#delete-button').attr('disabled', false);
            $('#delete-button .btn-spinner').addClass('hidden');
        });
    }

    function deleteAllData(form) {
        const formData = new FormData(form);
        formData.append('sid', sid);
        $('#cancel-truncate-button').attr('disabled', true);
        $('#truncate-button').attr('disabled', true);
        $('#truncate-button .btn-spinner').removeClass('hidden');
        $.ajax({
            type: 'POST',
            url: `<?= base_url('student/truncate') ?>`,
            data: formData,
            contentType: false,
            processData: false
        }).done(function () {
            $('.truncate-form-group').removeClass('has-danger');
            $('.truncate-form-input').removeClass('is-invalid');
            $('.truncate-form-error').addClass('hidden');
            $('#truncate-modal').modal('hide');
            datatable.ajax.reload();
        }).fail(function () {
            $('.truncate-form-group').addClass('has-danger');
            $('.truncate-form-input').addClass('is-invalid');
            $('.truncate-form-error').removeClass('hidden');
        }).always(function () {
            $('#cancel-truncate-button').attr('disabled', false);
            $('#truncate-button').attr('disabled', false);
            $('#truncate-button .btn-spinner').addClass('hidden');
        });
    }

    function importExcel(form) {
        const formData = new FormData(form);
        formData.append('sid', sid);
        $('#cancel-import-button').attr('disabled', true);
        $('#template-import-button').attr('disabled', true);
        $('#import-button').attr('disabled', true);
        $('#import-button .btn-spinner').removeClass('hidden');
        $.ajax({
            type: 'POST',
            url: `<?= base_url('student/import') ?>`,
            data: formData,
            contentType: false,
            processData: false
        }).done(function () {
            $('.import-form-group').removeClass('has-danger');
            $('.import-form-input').removeClass('is-invalid');
            $('.import-form-error').addClass('hidden');
            $('#import-modal').modal('hide');
            datatable.ajax.reload();
        }).fail(function () {
            $('.import-form-group').addClass('has-danger');
            $('.import-form-input').addClass('is-invalid');
            $('.import-form-error').removeClass('hidden');
        }).always(function () {
            $('#cancel-import-button').attr('disabled', false);
            $('#template-import-button').attr('disabled', false);
            $('#import-button').attr('disabled', false);
            $('#import-button .btn-spinner').addClass('hidden');
        });
    }

    function exportExcel() {
        $(".buttons-excel")[0].click();
    }

    function exportSpecificExcel(form) {
        const formData = new FormData(form);
        formData.append('sid', sid);
        formData.append('class_ids', JSON.stringify($('#export-specific-form-input-class').val()));
        formData.append('semester_ids', JSON.stringify($('#export-specific-form-input-semester').val()));
        $('#cancel-export-specific-button').attr('disabled', true);
        $('#export-specific-button').attr('disabled', true);
        $('#export-specific-button .btn-spinner').removeClass('hidden');
        $.ajax({
            type: 'POST',
            url: `<?= base_url('student/filtered-list') ?>`,
            data: formData,
            contentType: false,
            processData: false
        }).done(function (filteredDatas) {
            $('.export-specific-form-group').removeClass('has-danger');
            $('.export-specific-form-input').removeClass('is-invalid');
            $('.export-specific-form-error').addClass('hidden');
            $('#export-specific-modal').modal('hide');
            datatable.clear().draw();
            filteredDatas['data'].forEach(function (filteredData) {
                datatable.row.add({
                    'id': filteredData['id'],
                    'schedule_code': filteredData['schedule_code'],
                    'name': filteredData['name'],
                    'class_id': filteredData['class_id'],
                    'class_name': filteredData['class_name'],
                    'study_program_id': filteredData['study_program_id'],
                    'study_program_name': filteredData['study_program_name'],
                    'semester_id': filteredData['semester_id'],
                    'semester_name': filteredData['semester_name'],
                    'lecturer_id': filteredData['lecturer_id'],
                    'lecturer_name': filteredData['lecturer_name'],
                    'date_start': filteredData['date_start'],
                    'date_end': filteredData['date_end'],
                    'attendance_code': filteredData['attendance_code'],
                    'created_on': filteredData['created_on']
                }).invalidate().draw();
            });
            exportExcel();
            datatable.ajax.reload();
        }).fail(function () {
            $('.export-specific-form-group').addClass('has-danger');
            $('.export-specific-form-input').addClass('is-invalid');
            $('.export-specific-form-error').removeClass('hidden');
        }).always(function () {
            $('#cancel-export-specific-button').attr('disabled', false);
            $('#export-specific-button').attr('disabled', false);
            $('#export-specific-button .btn-spinner').addClass('hidden');
        });
    }

    function copyData() {
        $(".buttons-copy")[0].click();
    }

    function printData() {
        $(".buttons-print")[0].click();
    }
</script>