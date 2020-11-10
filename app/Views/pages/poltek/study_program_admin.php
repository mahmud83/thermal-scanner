<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-md-12">
                    <button type="button" data-toggle="modal" data-target="#add-modal"
                            class="btn btn-sm btn-neutral">Tambah
                    </button>
                    <button type="button" onclick="exportExcel()" class="btn btn-sm btn-neutral">Ekspor</button>
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
                    <h3 class="mb-0">Data Administator Prodi</h3>
                </div>
                <div class="table-responsive py-4">
                    <table class="table table-flush" id="datatable">
                        <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama Administrator</th>
                            <th>Email Administrator</th>
                            <th>Prodi</th>
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
                <h5 class="modal-title">Tambah Administrator Prodi Baru</h5>
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
                                   placeholder="Nama Administrator"
                                   type="text" required/>
                        </div>
                    </div>
                    <div class="add-form-group form-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">mail</i></span>
                            </div>
                            <input id="add-form-input-email" name="email" class="add-form-input form-control"
                                   placeholder="Email Administrator"
                                   type="email" required/>
                        </div>
                    </div>
                    <div id="add-form-group-password" class="add-form-group form-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">lock</i></span>
                            </div>
                            <input id="add-form-input-password" name="password" class="add-form-input form-control"
                                   placeholder="Password Akun"
                                   type="password" required/>
                        </div>
                    </div>
                    <div id="add-form-group-confirm-password" class="add-form-group form-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">lock</i></span>
                            </div>
                            <input id="add-form-input-confirm-password" name="confirm_password"
                                   class="add-form-input form-control" placeholder="Konfirmasi Password Akun"
                                   type="password" required/>
                        </div>
                    </div>
                    <div class="add-form-group form-group mb-0">
                        <div class="input-group">
                            <select id="add-form-input-study-program" name="class_name"
                                    class="add-form-input form-control"
                                    placeholder="Pilih Prodi" data-toggle="select" data-live-search="true" required>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="add-form-error text-center mt-4 hidden">
                        <small id="add-form-error-text" class="text-danger">
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

<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Administrator Prodi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-form" role="form">
                <div class="modal-body mt--4">
                    <input id="edit-form-row" name="dataTableRow" type="number" hidden/>
                    <input id="edit-form-id" name="id" type="number" hidden/>
                    <input id="edit-form-input-old-study-program" name="old_study_program" type="hidden" required/>
                    <div class="edit-form-group form-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">person</i></span>
                            </div>
                            <input id="edit-form-input-name" name="name" class="edit-form-input form-control"
                                   placeholder="Nama Administrator"
                                   type="text" required/>
                        </div>
                    </div>
                    <div class="edit-form-group form-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">mail</i></span>
                            </div>
                            <input id="edit-form-input-email" name="email" class="edit-form-input form-control"
                                   placeholder="Email Administrator"
                                   type="email" required/>
                        </div>
                    </div>
                    <div class="edit-form-group form-group mb-0">
                        <div class="input-group">
                            <select id="edit-form-input-study-program" class="edit-form-input form-control"
                                    placeholder="Pilih Prodi" data-toggle="select" data-live-search="true" required>
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
                    <button id="cancel-reset-password" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal" onclick="performResetPassword($('#edit-form-row').val())">Ubah Password
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

<div class="modal fade" id="reset-password-modal" tabindex="-1" role="dialog" aria-labelledby="reset-password-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Password Administrator Prodi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="reset-password-form" role="form">
                <div class="modal-body mt--4">
                    <input id="reset-password-form-row" name="dataTableRow" type="number" hidden/>
                    <input id="reset-password-form-id" name="id" type="number" hidden/>
                    <div id="reset-password-form-group-password" class="reset-password-form-group form-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">lock</i></span>
                            </div>
                            <input id="reset-password-form-input-password" name="password"
                                   class="reset-password-form-input form-control" placeholder="Password Baru"
                                   type="password" required/>
                        </div>
                    </div>
                    <div id="reset-password-form-group-confirm-password"
                         class="reset-password-form-group form-group mb-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">lock</i></span>
                            </div>
                            <input id="reset-password-form-input-confirm-password" name="confirm_password"
                                   class="reset-password-form-input form-control" placeholder="Konfirmasi Password Baru"
                                   type="password" required/>
                        </div>
                    </div>
                    <div class="reset-password-form-error text-center mt-4 hidden">
                        <small id="reset-password-form-error-text" class="text-danger">
                            Terjadi kesalahan saat mengubah password, mohon coba lagi...
                        </small>
                    </div>
                </div>
                <div class="modal-footer mt--4">
                    <button id="cancel-reset-password-button" type="button" class="btn btn-sm btn-neutral"
                            data-dismiss="modal">Batal
                    </button>
                    <button id="reset-password-button" type="submit" class="btn btn-sm btn-primary">
                        <span class="btn-spinner mr-1 hidden"><i class="fa fa-spinner fa-spin"></i></i></span>
                        <span class="btn-text">Ubah</span>
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
                <h5 class="modal-title">Hapus Administrator Prodi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="delete-form" role="form">
                <div class="modal-body mt--4">
                    <input id="delete-form-row" name="dataTableRow" type="number" hidden/>
                    <input id="delete-form-id" name="id" type="number" hidden/>
                    <small><b>Anda yakin ingin menghapus administrator prodi ini?<br/>Aksi ini tidak dapat
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
                    <small><b>Anda yakin ingin menghapus semua administrator prodi?<br/>Aksi ini tidak dapat
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

<script type="text/javascript">
    'use strict';

    const sid = '<?= password_hash(session_id(), PASSWORD_DEFAULT) ?>';
    let datatable;

    $(document).ready(function () {

        datatable = $('#datatable').on('init.dt', function () {
            $('div.dataTables_length select').removeClass('custom-select custom-select-sm');
        }).DataTable({
            processing: true,
            ajaxSource: `<?= base_url('studyprogramadmin/list') ?>?sid=${sid}`,
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return `${meta.row + 1}.`;
                    }
                },
                {
                    render: function (data, type, row, _) {
                        return row['user_name'];
                    }
                },
                {
                    render: function (data, type, row, _) {
                        return row['user_email'];
                    }
                },
                {
                    render: function (data, type, row, _) {
                        return row['study_program_name'];
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
                                <button onclick="performEditData(${meta.row})" class="dropdown-item">Edit Administrator</button>
                                <button onclick="performDeleteData(${meta.row})" class="dropdown-item">Hapus Administrator</button>
                            </div>
                        </div>
                    `;
                    }
                }
            ],
            columnDefs: [{
                searchable: false,
                orderable: false,
                targets: 5
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
                title: 'Data Administrator Prodi',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                    format: {
                        body: function (data, row, column, _) {
                            if (column === 0)
                                return `${data.replace('.', '')}`;
                            if (column === 4)
                                return `${moment(new Date(data)).format('DD/MM/YYYY HH:mm')}`;
                            return data;
                        }
                    }
                }
            },
                {
                    extend: 'copy',
                    className: 'hidden',
                    title: 'Data Administrator Prodi',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'print',
                    className: 'hidden',
                    title: 'Data Administrator Prodi',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
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

        $('#edit-form').on('submit', function () {
            editData(this);
            return false;
        });

        $('#reset-password-form').on('submit', function () {
            resetPassword(this);
            return false;
        });

        $('#delete-form').on('submit', function () {
            deleteData(this);
            return false;
        });

        $('#truncate-form').on('submit', function () {
            deleteAllData(this);
            return false;
        });

        $('#add-modal').on('hidden.bs.modal', function () {
            $("#add-form").trigger('reset');
            $('.add-form-group').removeClass('has-danger');
            $('.add-form-input').removeClass('is-invalid');
            $('.add-form-error').addClass('hidden');
            $('#add-form-input-study-program').removeClass('is-invalid').val(null).trigger('change');
        });

        $('#edit-modal').on('hidden.bs.modal', function () {
            $('#edit-form').trigger('reset');
            $('.edit-form-group').removeClass('has-danger');
            $('.edit-form-input').removeClass('is-invalid');
            $('.edit-form-error').addClass('hidden');
            $('#edit-form-input-study-program').removeClass('is-invalid').val(null).trigger('change');
            $('#edit-form-input-old-study-program').val(null);
        });

        $('#reset-password-modal').on('hidden.bs.modal', function () {
            $('#reset-password-form').trigger('reset');
            $('.reset-password-form-group').removeClass('has-danger');
            $('.reset-password-form-input').removeClass('is-invalid');
            $('.reset-password-form-error').addClass('hidden');
        });

        $('#delete-modal').on('hidden.bs.modal', function () {
            $('#delete-form').trigger('reset');
            $('.delete-form-group').removeClass('has-danger');
            $('.delete-form-input').removeClass('is-invalid');
            $('.delete-form-error').addClass('hidden');
        });

        $('#truncate-modal').on('hidden.bs.modal', function () {
            $('#truncate-form').trigger('reset');
            $('.truncate-form-group').removeClass('has-danger');
            $('.truncate-form-input').removeClass('is-invalid');
            $('.truncate-form-error').addClass('hidden');
        });

        $('#add-form-input-study-program').select2({
            placeholder: 'Pilih Prodi',
            language: {
                searching: function () {
                    return 'Memuat data...';
                }
            },
            allowClear: true,
            ajax: {
                url: `<?= base_url('studyprogram/list') ?>`,
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

        $('#edit-form-input-study-program').select2({
            placeholder: 'Pilih Prodi',
            language: {
                searching: function () {
                    return 'Memuat data...';
                }
            },
            allowClear: true,
            ajax: {
                url: `<?= base_url('studyprogram/list') ?>`,
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
        $('#edit-form-input-name').val(data['user_name']);
        $('#edit-form-input-email').val(data['user_email']);
        $('#edit-form-input-old-study-program').val(data['study_program_id']);
        $('#edit-form-input-study-program').append($("<option/>").val(data['study_program_id']).text(data['study_program_name'])).val(data[
            'study_program_id']).trigger('change');
        $('#edit-modal').modal('show');
    }

    function performResetPassword(targetRow) {
        const data = datatable.row(targetRow).data();
        $('#reset-password-form-row').val(targetRow);
        $('#reset-password-form-id').val(data['id']);
        $('#reset-password-modal').modal('show');
    }

    function performDeleteData(targetRow) {
        const data = datatable.row(targetRow).data();
        $('#delete-form-row').val(targetRow);
        $('#delete-form-id').val(data['id']);
        $('#delete-modal').modal('show');
    }

    function addData(form) {
        $('#add-form-input-password').removeClass('is-invalid');
        $('#add-form-group-password').removeClass('has-danger');
        $('#add-form-input-confirm-password').removeClass('is-invalid');
        $('#add-form-group-confirm-password').removeClass('has-danger');
        $('.add-form-error').addClass('hidden');
        if ($('#add-form-input-password').val() !== $('#add-form-input-confirm-password').val()) {
            $('#add-form-error-text').text("Password tidak sama, mohon cek kembali");
            $('#add-form-input-password').addClass('is-invalid');
            $('#add-form-group-password').addClass('has-danger');
            $('#add-form-input-confirm-password').addClass('is-invalid');
            $('#add-form-group-confirm-password').addClass('has-danger');
            $('.add-form-error').removeClass('hidden');
            return;
        }
        const formData = new FormData(form);
        formData.append('sid', sid);
        formData.append('study_program', $('#add-form-input-study-program').select2('data')[0]['id']);
        $('#cancel-add-button').attr('disabled', true);
        $('#add-button').attr('disabled', true);
        $('#add-button .btn-spinner').removeClass('hidden');
        $.ajax({
            type: 'POST',
            url: `<?= base_url('studyprogramadmin/add') ?>`,
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
                'user_id': insertedRow['user_id'],
                'user_name': insertedRow['user_name'],
                'user_email': insertedRow['user_email'],
                'study_program_id': insertedRow['study_program_id'],
                'study_program_name': insertedRow['study_program_name'],
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

    function editData(form) {
        const formData = new FormData(form);
        const targetRow = parseInt(formData.get('dataTableRow'));
        formData.append('sid', sid);
        formData.append('study_program', $('#edit-form-input-study-program').select2('data')[0]['id']);
        $('#cancel-edit-button').attr('disabled', true);
        $('#edit-button').attr('disabled', true);
        $('#edit-button .btn-spinner').removeClass('hidden');
        $.ajax({
            type: 'POST',
            url: `<?= base_url('studyprogramadmin/edit') ?>`,
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
                'user_id': updatedRow['user_id'],
                'user_name': updatedRow['user_name'],
                'user_email': updatedRow['user_email'],
                'study_program_id': updatedRow['study_program_id'],
                'study_program_name': updatedRow['study_program_name'],
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

    function resetPassword(form) {
        $('.reset-password-form-input').removeClass('is-invalid');
        $('.reset-password-form-group').removeClass('has-danger');
        $('.reset-password-form-error').addClass('hidden');
        if ($('#reset-password-form-input-password').val() !== $('#reset-password-form-input-confirm-password').val()) {
            $('#reset-password-form-error-text').text("Password tidak sama, mohon cek kembali");
            $('.reset-password-form-input').addClass('is-invalid');
            $('.reset-password-form-group').addClass('has-danger');
            $('.reset-password-form-error').removeClass('hidden');
            return;
        }
        const formData = new FormData(form);
        const targetRow = parseInt(formData.get('dataTableRow'));
        formData.append('sid', sid);
        $('#cancel-reset-password-button').attr('disabled', true);
        $('#reset-password-button').attr('disabled', true);
        $('#reset-password-button .btn-spinner').removeClass('hidden');
        $.ajax({
            type: 'POST',
            url: `<?= base_url('studyprogramadmin/resetpassword') ?>`,
            data: formData,
            contentType: false,
            processData: false
        }).done(function (updatedRow) {
            $('.reset-password-form-group').removeClass('has-danger');
            $('.reset-password-form-input').removeClass('is-invalid');
            $('.reset-password-form-error').addClass('hidden');
            $('#reset-password-modal').modal('hide');
            datatable.row(targetRow).data({
                'id': updatedRow['id'],
                'user_id': updatedRow['user_id'],
                'user_name': updatedRow['user_name'],
                'user_email': updatedRow['user_email'],
                'study_program_id': updatedRow['study_program_id'],
                'study_program_name': updatedRow['study_program_name'],
                'created_on': updatedRow['created_on']
            }).invalidate().draw();
        }).fail(function () {
            $('.reset-password-form-group').addClass('has-danger');
            $('.reset-password-form-input').addClass('is-invalid');
            $('.reset-password-form-error').removeClass('hidden');
        }).always(function () {
            $('#cancel-reset-password-button').attr('disabled', false);
            $('#reset-password-button').attr('disabled', false);
            $('#reset-password-button .btn-spinner').addClass('hidden');
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
            url: `<?= base_url('studyprogramadmin/delete') ?>`,
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
            url: `<?= base_url('studyprogramadmin/truncate') ?>`,
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