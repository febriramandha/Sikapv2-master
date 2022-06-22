<!-- Basic table -->
<div class="card">
    <div class="card-header bg-white header-elements-inline py-2">
        <h5 class="card-title">Jam Kerja Pegawai</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="card-body">

            <?php echo form_open('mngsch/sch-pegawai/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
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
                <input type="hidden" name="id" value="">
                <input type="hidden" name="mod" value="edit">
                <div class="text-left offset-lg-2">
                    <button type="reset" class="btn btn-sm bg-orange-300 result">Batal <i
                            class="icon-cross3 ml-2"></i></button>
                    <span id="hapus" class="btn btn-sm bg-danger-300 result">Hapus</span>
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
            url: uri_dasar + 'mngsch/sch-pegawai/PegawaiJson',
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
                bx_alert_success(res.message, 'mngsch/sch-pegawai');
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
        url: uri_dasar + 'mngsch/sch-pegawai/AjaxDel',
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
                bx_alert(res.message);
            }

        }
    });
}


// function ceklocked(instansi) {
//       var id = $('[name="id"]').val();
//       var instansi = instansi;
//       $.ajax({
//           type: 'get',
//           url: uri_dasar+'mngsch/sch-pegawai/AjaxGet',
//           data: {mod:'cekloked',id:id, instansi:instansi},
//           dataType : "JSON",
//           error:function(){
//              bx_alert('terjadi kesalahan sistem cobalah mengulang halaman ini kembali');
//           },
//           success: function(res) {
//               if (res.status == true) {
//                    $('#akses_status').show();
//                    $('#openakses').val(res.results.id)
//               }else {
//                    $('#akses_status').hide();
//                    $('#openakses').val('')
//               }
//           }
//       });
// }

// $('#aksesclick').click(function() {
//     var id = $('#openakses').val();
//     $.ajax({
//           type: 'get',
//           url: uri_dasar+'mngsch/sch-pegawai/AjaxGet',
//           data: {mod:'bukaakses',id:id},
//           dataType : "JSON",
//           error:function(){
//              bx_alert('terjadi kesalahan sistem cobalah mengulang halaman ini kembali');
//           },
//           success: function(res) {
//               if (res.status == true) {
//                    bx_alert_success(res.message, 'mngsch/sch-pegawai');
//               }else {
//                   bx_alert(res.message);
//               }
//           }
//       });
// })
</script>