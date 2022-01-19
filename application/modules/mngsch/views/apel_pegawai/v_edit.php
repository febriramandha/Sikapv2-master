<!-- Basic table -->
<div class="card">
    <div class="card-header bg-white header-elements-inline py-2">
        <h5 class="card-title">Pegawai tidak ikut apel</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row ml-4">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-alarm-check text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>

            <div class="media-body text-md-left">
                <h6 class="media-title font-weight-semibold"><?= $sch_apel->name?></h6>
                Tanggal: <?= $sch_apel->tgl_apel ?> Jam: <?= $sch_apel->start_time ?>
            </div>
        </div>
        <div class="card-body">

            <?php echo form_open('mngsch/apel-pegawai/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
            <div class="col-lg-12">
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
                <div class="form-group row" id="akses_status" style="display:none">
                    <label class="col-form-label col-lg-2">Akses Status <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <span class="btn btn-sm bg-success-300 " id="aksesclick"><i class="icon-lock2"></i></span>
                        <span class="text-danger"><i>* jadwal untuk instansi ini telah ditutup (hub admin jika ingin
                                mengubah)</i></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <div class="form-group">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-sm table-hover table-bordered">
                                    <thead>
                                        <tr class="table-active">
                                            <th width="1%">No</th>
                                            <th width="1%" class="text-center"><label class="pure-material-checkbox">
                                                    <input type="checkbox" id="checkAll" /> <span></span></label>
                                            </th>
                                            <th class="text-nowrap">Nama(NIP)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?php echo encrypt_url($sch_apel->id,'schapel_id') ?>">
                <input type="hidden" name="mod" value="edit">
                <div class="text-left offset-lg-2">
                    <button type="reset" class="btn btn-sm bg-orange-300 result">Batal <i
                            class="icon-cross3 ml-2"></i></button>
                    <button type="submit" class="btn btn-sm btn-info result">Simpan <i
                            class="icon-checkmark4 ml-2"></i></button>
                    <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>
                </div>
            </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>
<input type="hidden" id="openakses" value="">
<input type="hidden" name="stag" value="0">
<script type="text/javascript">
$('#checkAll').click(function() {
    $('.checkbox').prop('checked', this.checked);
});
$(document).ready(function() {
    table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        "ordering": false,
        "paging": false,
        stateSave: true,
        "searching": false,
        language: {
            search: '<span></span> _INPUT_',
            searchPlaceholder: 'Cari...',
            processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
        },
        ajax: {
            url: uri_dasar + 'mngsch/apel-pegawai/PegawaiJson',
            type: "post",
            "data": function(data) {
                data.csrf_sikap_token_name = csrf_value;
                data.schapel_id = $('[name="id"]').val();
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
                "data": "cekbox",
                searchable: false
            },
            {
                "data": "nama_nip",
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
            // $('td', row).eq(5).addClass('text-center');
            $('td', row).eq(1).addClass('text-nowrap');
        },


    });

    // Initialize
    dt_componen();
    loadSettings();

    // if ($('[name="stag"]').val() == 1) {
    //    ceklocked($('[name="instansi"]').val());
    //  }else {
    //      ceklocked(localStorage.index_instansi);
    //  } 
});

$(window).on('unload', function() {
    saveSettings();
});


$('[name="instansi"]').change(function() {
    if ($('[name="stag"]').val() == 1) {
        table.ajax.reload();
        // ceklocked($('[name="instansi"]').val());
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


$('#formAjax').submit(function() {
    var result = $('.result');
    var spinner = $('#spinner');
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "JSON",
        error: function() {
            result.attr("disabled", false);
            spinner.hide();
            bx_alert('terjadi kesalahan sistem cobalah mengulang halaman ini kembali');
        },
        beforeSend: function() {
            result.attr("disabled", true);
            spinner.show();
        },
        success: function(res) {
            if (res.status == true) {
                bx_alert_success(res.message, 'mngsch/apel-pegawai');
            } else {
                bx_alert(res.message);
            }
            result.attr("disabled", false);
            spinner.hide();
        }
    });
    return false;
});
</script>