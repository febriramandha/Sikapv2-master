<!-- Basic table -->
<div class="card">
    <div class="card-header bg-white header-elements-inline py-2">
        <h5 class="card-title">Data Pengguna</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <div class="text-left col-lg-12">
                <a href="<?php echo base_url('administrator/user/add') ?>" class="btn btn-sm btn-info"><i
                        class="icon-pen-plus mr-1"></i> Tambah Pengguna</a>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Unit Kerja <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="form-control select-search" name="instansi">
                        <?php foreach ($instansi as $row) { ?>
                        <option value="<?php echo encrypt_url($row->id,'instansi') ?>">
                            <?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="text-right mt-1">
            <button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
                <span><i class="icon-printer mr-2"></i> Cetak</span>
            </button>
            <a class="btn btn-sm bg-info-400 legitRipple pt-1 pb-1 sync-pegawai" id="sync-pegawai">
                <span><i class="icon-spinner11"></i> Sinkron</span>
            </a>
        </div>


        <div class="table-responsive">
            <table id="datatable" class="table table-sm table-hover table-bordered">
                <thead>
                    <tr>
                        <th width="1%">No</th>
                        <th width="1%">ID</th>
                        <th class="text-nowrap">Nama(NIP)</th>
                        <th class="text-nowrap">Unit Kerja</th>
                        <th width="1%" style="font-size: 80%;">Status Pegawai</th>
                        <th width="1%" style="font-size: 80%;">Status Pengguna</th>
                        <th width="1%" style="font-size: 80%;">Status Akun</th>
                        <th width="1%" style="font-size: 60%;" class="p-1">Kewanangan</th>
                        <th width="1%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /basic table -->
<input type="hidden" name="stag" value="0">
<script type="text/javascript">
$(document).ready(function() {

    // Initialize
    dt_componen();
    loadSettings();
    load_datatables();
});

function load_datatables() {
    table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        "ordering": false,
        stateSave: true,
        language: {
            search: '<span></span> _INPUT_',
            searchPlaceholder: 'Cari...',
            processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
        },
        "lengthMenu": [
            [10, 25, 50, 100, 200],
            [10, 25, 50, 100, 200]
        ],
        ajax: {
            url: uri_dasar + 'administrator/user/indexJson',
            type: "post",
            "data": function(data) {
                data.csrf_sikap_token_name = csrf_value;
                if ($('[name="stag"]').val() == 1) {
                    data.instansi = $('[name="instansi"]').val();
                } else {
                    data.instansi = localStorage.index_instansi;
                }
                data.all = function() {
                    return $('#all_data').val();
                }
            },
        },
        "columns": [{
                "data": "id",
                searchable: false
            },
            {
                "data": "key",
                searchable: false
            },
            {
                "data": "nama_nip",
                searchable: false
            },
            {
                "data": "dept_alias",
                searchable: false
            },
            {
                "data": "pegawai_status",
                searchable: false
            },
            {
                "data": "status_att",
                searchable: false
            },
            {
                "data": "status_user",
                searchable: false
            },
            {
                "data": "level",
                searchable: false
            },
            {
                "data": "action",
                searchable: false
            },
        ],
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $('td:eq(0)', row).html(index);
        },
        createdRow: function(row, data, index) {
            $('td', row).eq(2).addClass('text-nowrap p-1');
            $('td', row).eq(8).addClass('text-nowrap');
        },


    });
}

$(window).on('unload', function() {
    saveSettings();
});

$('[name="instansi"]').change(function() {
    if ($('[name="stag"]').val() == 1) {
        table.ajax.reload();
    } else {
        $('[name="stag"]').val(1);
    }
})

function loadSettings() {
    localStorage.all = 0;
    if (localStorage.index_instansi) {
        $('[name="instansi"]').val(localStorage.index_instansi).trigger('change');
        if (!$('[name="instansi"]').val()) {
            $('[name="instansi"]').val($('[name="instansi"] option:first').val()).trigger('change');
        }
    }
    $('[name="all_data"]').change(function() {
        if ($(this).is(":checked")) {
            localStorage.all = 1;
            $('#all_data').val(localStorage.all);
            table.ajax.reload();
        } else {
            localStorage.all = 0;
            $('#all_data').val(localStorage.all);
            table.ajax.reload();
        }
    });
    if (localStorage.all == "1") {
        $("#all_data").prop('checked');
    }
}

function saveSettings() {
    var instansi = $('[name="instansi"]').val();
    if (instansi) {
        localStorage.index_instansi = instansi;
    }
}

function confirmAksi(id) {
    $.ajax({
        type: 'get',
        url: uri_dasar + 'administrator/user/AjaxDel',
        data: {
            id: id
        },
        dataType: "JSON",
        error: function() {
            $('.table').unblock();
            bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        beforeSend: function() {
            load_dt('.table');
        },
        success: function(res) {
            if (res.status == true) {
                bx_alert_ok(res.message, 'success');
                table.ajax.reload();
            } else {
                bx_alert(res.message);
            }
            $('.table').unblock();
        }
    });

}

$('#cetak').click(function() {
    newWindow = window.open(uri_dasar + 'administrator/user/cetak/' + $('[name="instansi"]').val() + '/' +
        localStorage.all, "open",
        'height=600,width=800');
    if (window.focus) {
        newWindow.focus()
    }
    return false;
})


$("#sync-pegawai").click(function() {
    var dept_id = $('[name="instansi"]').val();
    $.ajax({
        type: 'get',
        url: uri_dasar + 'administrator/user/SyncPegawai/' + dept_id,
        dataType: "JSON",
        error: function() {
            $('.table').unblock();
            bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        beforeSend: function() {
            load_dt('.table');
        },
        success: function(res) {
            if (res.status == true) {
                bx_alert_ok(res.message, 'success');
                table.ajax.reload();
            } else {
                bx_alert(res.message);
            }
            $('.table').unblock();
        }
    });
});
</script>