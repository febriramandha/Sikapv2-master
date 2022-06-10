<!-- Basic table -->
<div class="card">
    <div class="card-header bg-white header-elements-inline py-2">
        <h5 class="card-title">Data Tunjangan PNS</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="text-left">
            <a href="<?php echo base_url('master/allowance/add') ?>" class="btn btn-sm btn-info"><i
                    class="icon-pen-plus mr-1"></i> Tambah Baru</a>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Unit Kerja <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="form-control multiselect-clickable-groups" name="instansi[]" multiple="multiple"
                        id="filter_list_dropdwn" data-fouc>
                        <?php foreach ($instansi as $row) { ?>
                        <option class="tes" value="<?php echo $row->id ?>" parent_id="<?php echo $row->parent_id ?>">
                            <?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Tahun Aktif<span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="form-control select-search" name="tahun_aktif">
                        <?php 
                            for($i=date('Y'); $i>=date('Y')-5; $i-=1){
                            echo "<option value=$i> $i </option>";
                            }
                                                   ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="text-right mt-1">
            <button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
                <span><i class="icon-printer mr-2"></i> Cetak</span>
            </button>
        </div>

        <div class="table-responsive">
            <table id="datatable" class="table table-sm table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="1%">No</th>
                        <th class="text-nowrap">Jabatan</th>
                        <th width="1%">Kelas Jabatan</th>
                        <th width="1%">Besaran TPP Perbulan</th>
                        <th width="1%">No Urut</th>
                        <th width="1%">Status</th>
                        <th width="1%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<input type="hidden" name="stag" value="0">

<script type="text/javascript">
$(document).ready(function() {
    table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        "ordering": false,
        language: {
            search: '<span></span> _INPUT_',
            searchPlaceholder: 'Cari...',
            processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
        },
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        ajax: {
            url: "<?php echo site_url('master/allowance/json') ?>",
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
                "data": "name",
                searchable: false
            },
            {
                "data": "golongan",
                searchable: false
            },
            {
                "data": "tpp",
                searchable: false
            },
            {
                "data": "position",
                searchable: false
            },
            {
                "data": "status_tunjangan",
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
            $('td', row).eq(4).addClass('text-nowrap');
            $('td', row).eq(7).addClass('text-nowrap');
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
        url: "<?php echo site_url('master/allowance/AjaxDel') ?>",
        data: {
            id: id
        },
        dataType: "json",
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
    newWindow = window.open(uri_dasar + 'master/allowance/cetak', "open", 'height=600,width=800');
    if (window.focus) {
        newWindow.focus()
    }
    return false;
})
</script>