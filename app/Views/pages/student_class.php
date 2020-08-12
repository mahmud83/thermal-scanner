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
                    <div class="col-lg-6 col-md-12">
                        <button type="button" data-toggle="modal" data-target="#add-modal"
                            class="btn btn-sm btn-neutral">Tambah</button>
                        <button type="button" data-toggle="modal" data-target="#import-modal"
                            class="btn btn-sm btn-neutral">Impor</button>
                        <button type="button" onclick="exportExcel()" class="btn btn-sm btn-neutral">Ekspor</button>
                        <button type="button" onclick="copyData()" class="btn btn-sm btn-neutral">Salin</button>
                        <button type="button" onclick="printData()" class="btn btn-sm btn-neutral">Cetak</button>
                    </div>
                    <div class="col-lg-6 col-md-12 mt-4 mt-lg-0 text-right">
                        <button type="button" data-toggle="modal" data-target="#truncate-modal"
                            class="btn btn-sm btn-danger">Hapus Semua</button>
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
                        <h3 class="mb-0">Data Kelas</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Kelas</th>
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
    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="import-modal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="add-form" role="form">
                    <div class="modal-body mt--4">
                        <div id="add-form-group" class="form-group mb-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">class</i></span>
                                </div>
                                <input id="add-form-input" name="name" class="form-control" placeholder="Nama Kelas"
                                    type="text" required />
                            </div>
                        </div>
                        <div id="add-form-error" class="text-center mt-4 hidden">
                            <small class="text-danger">
                                Terjadi kesalahan saat menambah data, mohon coba lagi...
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer mt--4">
                        <button id="cancel-add-button" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal">Batal</button>
                        <button id="add-button" type="submit" class="btn btn-sm btn-primary">
                            <span class="btn-spinner mr-1 hidden"><i class="fa fa-spinner fa-spin"></i></i></span>
                            <span class="btn-text">Tambah</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="import-modal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="edit-form" role="form">
                    <div class="modal-body mt--4">
                        <input id="edit-form-row" name="dataTableRow" type="number" hidden />
                        <input id="edit-form-id" name="id" type="number" hidden />
                        <div id="edit-form-group" class="form-group mb-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">class</i></span>
                                </div>
                                <input id="edit-form-input" name="name" class="form-control" placeholder="Nama Kelas"
                                    type="text" required />
                            </div>
                        </div>
                        <div id="edit-form-error" class="text-center mt-4 hidden">
                            <small class="text-danger">
                                Terjadi kesalahan saat mengedit data, mohon coba lagi...
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer mt--4">
                        <button id="cancel-edit-button" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal">Batal</button>
                        <button id="edit-button" type="submit" class="btn btn-sm btn-primary">
                            <span class="btn-spinner mr-1 hidden"><i class="fa fa-spinner fa-spin"></i></i></span>
                            <span class="btn-text">Edit</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="import-modal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="delete-form" role="form">
                    <div class="modal-body mt--4">
                        <small><b>Anda yakin ingin menghapus kelas ini?<br />Aksi ini tidak dapat
                                dibatalkan.</b></small>
                        <input id="delete-form-row" name="dataTableRow" type="number" hidden />
                        <input id="delete-form-id" name="id" type="number" hidden />
                        <div id="delete-form-error" class="text-center mt-4 hidden">
                            <small class="text-danger">
                                Terjadi kesalahan saat menghapus data, mohon coba lagi...
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer mt--4">
                        <button id="cancel-delete-button" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal">Batal</button>
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
                        <small>Impor data dengan file CSV atau Excel.<br />Pastikan data sudah sesuai template yang
                            tersedia.</small>
                        <div id="import-form-group" class="form-group mt-2 mb-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">folder</i></span>
                                </div>
                                <input id="import-form-input" name="file" class="form-control" type="file"
                                    accept=".xls,.xlsx,.csv" required />
                            </div>
                        </div>
                        <div id="import-form-error" class="text-center mt-4 hidden">
                            <small class="text-danger">
                                Terjadi kesalahan saat mengimpor data, mohon coba lagi...
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer mt--4">
                        <button id="cancel-import-button" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal">Batal</button>
                        <button id="template-import-button" type="button" onclick="exportExcel()"
                            class="btn btn-sm btn-neutral" data-dismiss="modal">Unduh Template</button>
                        <button id="import-button" type="button submit" class="btn btn-sm btn-primary">
                            <span class="btn-spinner mr-1 hidden"><i class="fa fa-spinner fa-spin"></i></i></span>
                            <span class="btn-text">Impor</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="truncate-modal" tabindex="-1" role="dialog" aria-labelledby="import-modal"
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
                        <small><b>Anda yakin ingin menghapus semua kelas?<br />Aksi ini tidak dapat
                                dibatalkan.</b></small>
                        <div id="truncate-form-error" class="text-center mt-4 hidden">
                            <small class="text-danger">
                                Terjadi kesalahan saat menghapus data, mohon coba lagi...
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer mt--4">
                        <button id="cancel-truncate-button" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal">Batal</button>
                        <button id="truncate-button" type="submit" class="btn btn-sm btn-danger">
                            <span class="btn-spinner mr-1 hidden"><i class="fa fa-spinner fa-spin"></i></i></span>
                            <span class="btn-text">Hapus</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
        ajaxSource: `<?= base_url('class/list') ?>?sid=${sid}`,
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
                    return moment(new Date(row['created_on'])).format('DD/MM/YYYY hh:mm A');
                }
            },
            {
                render: function(data, type, row, meta) {
                    return `
                        <div class="dropdown">
                            <button class="btn btn-sm btn-neutral btn-icon-only" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <button onclick="performEditData(${meta.row})" class="dropdown-item">Edit Kelas</button>
                                <button onclick="performDeleteData(${meta.row})" class="dropdown-item">Hapus Kelas</button>
                            </div>
                        </div>
                    `;
                }
            }
        ],
        columnDefs: [{
            searchable: false,
            orderable: false,
            targets: 3
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
                title: 'Data Kelas',
                exportOptions: {
                    columns: [0, 1, 2],
                    format: {
                        body: function(data, row, column, node) {
                            return column === 2 ? ` ${data}` : data;
                        }
                    }
                }
            },
            {
                extend: 'copy',
                className: 'hidden',
                title: 'Data Kelas',
                exportOptions: {
                    columns: [0, 1, 2]
                }
            },
            {
                extend: 'print',
                className: 'hidden',
                title: 'Data Kelas',
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

    $('#add-form').on('submit', function() {
        addData(this);
        return false;
    });

    $('#edit-form').on('submit', function() {
        editData(this);
        return false;
    });

    $('#delete-form').on('submit', function() {
        deleteData(this);
        return false;
    });

    $('#import-form').on('submit', function() {
        importExcel(this);
        return false;
    });

    $('#truncate-form').on('submit', function() {
        deleteAllData(this);
        return false;
    });

    $('#add-modal').on('hidden.bs.modal', function() {
        $("#add-form").trigger('reset');
        $('#add-form-group').removeClass('has-danger');
        $('#add-form-input').removeClass('is-invalid');
        $('#add-form-error').addClass('hidden');
    });

    $('#edit-modal').on('hidden.bs.modal', function() {
        $('#edit-form').trigger('reset');
        $('#edit-form-group').removeClass('has-danger');
        $('#edit-form-input').removeClass('is-invalid');
        $('#edit-form-error').addClass('hidden');
    });

    $('#delete-modal').on('hidden.bs.modal', function() {
        $('#delete-form').trigger('reset');
        $('#delete-form-group').removeClass('has-danger');
        $('#delete-form-input').removeClass('is-invalid');
        $('#delete-form-error').addClass('hidden');
    });

    $('#import-modal').on('hidden.bs.modal', function() {
        $('#import-form').trigger('reset');
        $('#import-form-group').removeClass('has-danger');
        $('#import-form-input').removeClass('is-invalid');
        $('#import-form-error').addClass('hidden');
    });

    $('#truncate-modal').on('hidden.bs.modal', function() {
        $('#truncate-form').trigger('reset');
        $('#import-form-group').removeClass('has-danger');
        $('#import-form-input').removeClass('is-invalid');
        $('#import-form-error').addClass('hidden');
    });
});

function performEditData(targetRow) {
    const data = datatable.row(targetRow).data();
    $('#edit-form-row').val(targetRow);
    $('#edit-form-id').val(data['id']);
    $('#edit-form-input').val(data['name']);
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
    $('#cancel-add-button').attr('disabled', true);
    $('#add-button').attr('disabled', true);
    $('#add-button .btn-spinner').removeClass('hidden');
    $.ajax({
        type: 'POST',
        url: `<?= base_url('class/add') ?>`,
        data: formData,
        contentType: false,
        processData: false
    }).done(function(insertedRow) {
        $('#add-form-group').removeClass('has-danger');
        $('#add-form-input').removeClass('is-invalid');
        $('#add-form-error').addClass('hidden');
        $('#add-modal').modal('hide');
        datatable.row.add({
            'id': insertedRow['id'],
            'name': insertedRow['name'],
            'created_on': insertedRow['created_on']
        }).invalidate().draw();
    }).fail(function() {
        $('#add-form-group').addClass('has-danger');
        $('#add-form-input').addClass('is-invalid');
        $('#add-form-error').removeClass('hidden');
    }).always(function() {
        $('#cancel-add-button').attr('disabled', false);
        $('#add-button').attr('disabled', false);
        $('#add-button .btn-spinner').addClass('hidden');
    });
}

function editData(form) {
    const formData = new FormData(form);
    const targetRow = parseInt(formData.get('dataTableRow'));
    formData.append('sid', sid);
    $('#cancel-edit-button').attr('disabled', true);
    $('#edit-button').attr('disabled', true);
    $('#edit-button .btn-spinner').removeClass('hidden');
    $.ajax({
        type: 'POST',
        url: `<?= base_url('class/edit') ?>`,
        data: formData,
        contentType: false,
        processData: false
    }).done(function(updatedRow) {
        $('#edit-form-group').removeClass('has-danger');
        $('#edit-form-input').removeClass('is-invalid');
        $('#edit-form-error').addClass('hidden');
        $('#edit-modal').modal('hide');
        datatable.row(targetRow).data({
            'id': updatedRow['id'],
            'name': updatedRow['name'],
            'created_on': updatedRow['created_on']
        }).invalidate().draw();
    }).fail(function() {
        $('#edit-form-group').addClass('has-danger');
        $('#edit-form-input').addClass('is-invalid');
        $('#edit-form-error').removeClass('hidden');
    }).always(function() {
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
        url: `<?= base_url('class/delete') ?>`,
        data: formData,
        contentType: false,
        processData: false
    }).done(function(updatedRow) {
        $('#delete-form-group').removeClass('has-danger');
        $('#delete-form-input').removeClass('is-invalid');
        $('#delete-form-error').addClass('hidden');
        $('#delete-modal').modal('hide');
        datatable.row(targetRow).remove().draw();
        datatable.rows().invalidate();
    }).fail(function() {
        $('#delete-form-group').addClass('has-danger');
        $('#delete-form-input').addClass('is-invalid');
        $('#delete-form-error').removeClass('hidden');
    }).always(function() {
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
        url: `<?= base_url('class/truncate') ?>`,
        data: formData,
        contentType: false,
        processData: false
    }).done(function(insertedRow) {
        $('#truncate-form-group').removeClass('has-danger');
        $('#truncate-form-input').removeClass('is-invalid');
        $('#truncate-form-error').addClass('hidden');
        $('#truncate-modal').modal('hide');
        datatable.ajax.reload();
    }).fail(function() {
        $('#truncate-form-group').addClass('has-danger');
        $('#truncate-form-input').addClass('is-invalid');
        $('#truncate-form-error').removeClass('hidden');
    }).always(function() {
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
        url: `<?= base_url('class/import') ?>`,
        data: formData,
        contentType: false,
        processData: false
    }).done(function(insertedRow) {
        $('#import-form-group').removeClass('has-danger');
        $('#import-form-input').removeClass('is-invalid');
        $('#import-form-error').addClass('hidden');
        $('#import-modal').modal('hide');
        datatable.ajax.reload();
    }).fail(function() {
        $('#import-form-group').addClass('has-danger');
        $('#import-form-input').addClass('is-invalid');
        $('#import-form-error').removeClass('hidden');
    }).always(function() {
        $('#cancel-import-button').attr('disabled', false);
        $('#template-import-button').attr('disabled', false);
        $('#import-button').attr('disabled', false);
        $('#import-button .btn-spinner').addClass('hidden');
    });
}

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