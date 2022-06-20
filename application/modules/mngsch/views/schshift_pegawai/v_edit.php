<!-- Basic table -->
<div class="card">
    <div class="card-header bg-white header-elements-inline py-2">
        <h5 class="card-title">Jadwal shift Pegawai</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-alarm-check text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>

            <div class="media-body text-md-left">
                <h6 class="media-title font-weight-semibold"><?php echo $sch_run->row()->name ?></h6>
                Priode: <?php echo format_tgl_ind($sch_run->row()->start_date) ?> -
                <?php echo format_tgl_ind($sch_run->row()->end_date) ?>
            </div>

        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered">
                <thead>
                    <tr class="table-active">
                        <th width="1%">Kode</th>
                        <th width="1%">Keterangan</th>
                        <th class="text-nowrap">Jam Masuk
                            <hr class="my-0">(Mulai C/in - Akhir C/in)
                        </th>
                        <th class="text-nowrap">Jam Pulang
                            <hr class="my-0">(Mulai C/Out - Akhir C/Out)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schrun_shift->result() as $row) { 
                $libur ='';
                if (!$row->start_time) {
                      $libur = "Libur";
                }
              ?>
                    <tr>
                        <td><?php echo $row->kd_shift ?></td>
                        <td class="text-nowrap"><?php echo $row->ket ?></td>
                        <td><?php echo $libur ?>
                            <?php echo jm($row->start_time) ?>
                            (<?php echo jm($row->check_in_time1) ?> -
                            <?php echo jm($row->check_in_time2) ?>)
                        </td>
                        <td><?php echo $libur ?>
                            <?php echo jm($row->end_time) ?>
                            (<?php echo jm($row->check_out_time1) ?> -
                            <?php echo jm($row->check_out_time2) ?>)
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <hr>
        <?php echo form_open('mngsch/schshift-pegawai/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
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
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-sm table-hover table-bordered">
                            <thead>
                                <tr class="table-active">
                                    <th width="1%">No</th>
                                    <th width="1%" class="text-center"><label class="pure-material-checkbox"> <input
                                                type="checkbox" id="checkAll" /> <span></span></label>
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
        <input type="hidden" name="id" value="<?php echo encrypt_url($sch_run->row()->id,'schrun_id_shift') ?>">
        <input type="hidden" name="mod" value="edit">
        <div class="text-left offset-lg-2">
            <a href="javascript:history.back()" class="btn btn-sm bg-success-300 result">Kembali <i
                    class="icon-arrow-left5 ml-2"></i></a>
            <span id="hapus" class="btn btn-sm bg-danger-300 result">Hapus</span>

            <button type="submit" class="btn btn-sm btn-info result">Atur Jadwal Pegawai<i
                    class="icon-next2 ml-2"></i></button>
            <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>
        </div>
    </div>
</div>
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
            url: uri_dasar + 'mngsch/schshift-pegawai/PegawaiJson',
            type: "post",
            "data": function(data) {
                data.csrf_sikap_token_name = csrf_value;
                data.schrun_id = $('[name="id"]').val();
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
                window.location.href = uri_dasar + 'mngsch/schshift-pegawai/shift/' + res.result.id;
            } else {
                bx_alert(res.message);
            }
            result.attr("disabled", false);
            spinner.hide();
        }
    });
    return false;
});

$('#hapus').click(function() {
    data = {
        id: $('[name="id"]').val(),
        instansi: $('[name="instansi"]').val()
    }
    confirmAksi(data);
})

function confirmAksi(option) {
    $.ajax({
        url: uri_dasar + 'mngsch/schshift-pegawai/AjaxDel',
        data: option,
        dataType: "json",
        error: function() {
            bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        success: function(res) {
            if (res.status == true) {
                table.ajax.reload();
                bx_alert_ok(res.message, 'success');
            } else {
                table.ajax.reload();
                bx_alert(res.message);
            }

        }
    });
}
</script>