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
                    class="btn btn-sm btn-info"><i class="icon-spinner11"></i> Sinkron Semua</a>
            </div>
        </div>
        <?php echo form_open('administrator/synchronize_data/AjaxSaveOpd/','id="formAjax"'); ?>
        <div class="table-responsive">
            <table id="datatable" class="table table-sm table-hover table-bordered">
                <thead>
                    <tr>
                        <th width="1%">No</th>
                        <th class="text-center p-2" width="1%">
                            <label class="pure-material-checkbox ml-1">
                                <input class="" type="checkbox" id="checkAll" /> <span></span>
                            </label>
                        </th>
                        <th class="text-nowrap">Nama Instansi</th>
                        <th width="1%" style="font-size: 80%;">Type Instansi</th>
                        <th width="1%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tr>
                    <td colspan="9" align="left">
                        <input type="hidden" name="user_id_" value="">
                        <button type="submit" class="btn btn-sm bg-info legitRipple result"><i
                                class="icon-checkmark2"></i> Simpan instansi yang dipilih</button>
                    </td>
                </tr>
            </table>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<!-- /basic table -->
<input type="hidden" name="stag" value="0">
<script type="text/javascript">
$('#checkAll').click(function() {
    $('.checkbox').prop('checked', this.checked);
});

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
            bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        beforeSend: function() {
            result.attr("disabled", true);
            spinner.show();
        },
        success: function(res) {
            if (res.status == true) {
                table.ajax.reload();
                bx_alert_ok(res.message, 'success');
            } else {
                bx_alert(res.message);
            }
            result.attr("disabled", false);
            spinner.hide();
        }
    });
    return false;
});

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
            url: uri_dasar + 'administrator/synchronize_data/JsonOpd',
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
                "data": "cek",
                searchable: false
            },
            {
                "data": "nama_unor",
                searchable: false
            },
            {
                "data": "type_unor",
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
});

$(document).on('click', '.ver', function() {
    var id = $(this).attr('data_id');
    $.ajax({
        type: "get",
        url: uri_dasar + 'administrator/synchronize_data/AjaxSaveOpd/' + id,
        dataType: "JSON",
        cache: true,
        error: function() {
            bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        beforeSend: function() {
            $('.loading' + id).block({
                message: '<i class="icon-spinner spinner"></i>',
                overlayCSS: {
                    backgroundColor: '#fff',
                    opacity: 0.8,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'none'
                }
            });

        },
        success: function(res) {
            console.log(res.status);
            if (res.status == true) {
                toastr["success"](res.message);
                table.ajax.reload();
            }
        }
    });
});
</script>