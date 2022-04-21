<!-- Basic table -->
<div class="card">
    <div class="card-header bg-white header-elements-inline py-2">
        <h5 class="card-title">Synchronize Data</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <div class="text-left col-lg-12">
                <a href="<?php echo base_url('administrator/synchronize_data/synchronize_opd') ?>"
                    class="btn btn-sm btn-info"> Ambil Data</a>
                <button class="btn btn-sm btn-info" id="sync-opd"><i class="icon-spinner11"></i> Sinkron
                    Instansi</button>
                <button class="btn btn-sm btn-info" id="sync-user"><i class="icon-spinner11"></i> Sinkron
                    Pegawai</button>
            </div>
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
            url: uri_dasar + 'administrator/synchronize_data/indexJson',
            type: "post",
            "data": function(data) {
                data.csrf_sikap_token_name = csrf_value;
                if ($('[name="stag"]').val() == 1) {
                    data.instansi = $('[name="instansi"]').val();
                } else {
                    data.instansi = localStorage.index_instansi;
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

    // Initialize
    dt_componen();
    loadSettings();
});


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
    if (localStorage.index_instansi) {
        $('[name="instansi"]').val(localStorage.index_instansi).trigger('change');
        if (!$('[name="instansi"]').val()) {
            $('[name="instansi"]').val($('[name="instansi"] option:first').val()).trigger('change');
        }
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
    newWindow = window.open(uri_dasar + 'administrator/user/cetak/' + $('[name="instansi"]').val(), "open",
        'height=600,width=800');
    if (window.focus) {
        newWindow.focus()
    }
    return false;
})

$("#sync-opd").click(function() {
    $.ajax({
        type: 'get',
        url: uri_dasar + 'administrator/synchronize_data/SyncOpd',
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

$("#sync-user").click(function() {
    $.ajax({
        type: 'get',
        url: uri_dasar + 'administrator/synchronize_data/SyncPegawai',
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