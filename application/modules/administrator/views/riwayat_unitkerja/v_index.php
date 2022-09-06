<!-- Basic table -->
<div class="card">
    <div class="card-header bg-white header-elements-inline py-2">
        <h5 class="card-title">Riwayat Unit Kerja</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-form-label col-lg-2"> Rentang Waktu <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                    </div>
                    <input type="text" name="rank1" class="form-control datepicker readonlyjm"
                        placeholder="dari tanggal">
                </div>
            </div>
            <div class="col-lg-1">
                <div class="form-group">
                    <span>s/d</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                    </div>
                    <input type="text" name="rank2" class="form-control datepicker readonlyjm"
                        placeholder="sampai tanggal">
                </div>
            </div>
        </div>
        <div class="text-left offset-lg-2">
            <button type="submit" class="btn btn-sm btn-info result" id="kalkulasi">Tampilkan <i
                    class="icon-search4 ml-2"></i></button>
            <button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
                <span><i class="icon-printer mr-2"></i> Cetak</span>
            </button>
            <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>
        </div>
        <div class="table-responsive">
            <table id="datatable" class="table table-sm table-hover table-bordered"">
                <thead>
                    <tr>
                    <th width=" 1%">No</th>
                <th class="text-nowrap">Nama Pegawai</th>
                <th class="text-nowrap" width="1%">Asal Unit Kerja</th>
                <th class="text-nowrap" width="1%">Unit Kerja Tujuan</th>
                <th class="text-nowrap" width="1%">Tanggal Pindah</th>
                <th width="1%">Aksi</th>
                </tr>
                </thead>
                <tbody id="load_dt">
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /basic table -->

<script type="text/javascript">
var result = $('.result');
var spinner = $('#spinner');


$(".datepicker").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
});

$('#kalkulasi').click(function() {
    result.attr("disabled", true);
    spinner.show();
    table.ajax.reload();
})
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
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        ajax: {
            url: "<?php echo site_url('administrator/riwayat_unitkerja/indexJson') ?>",
            type: "post",
            "data": function(data) {
                data.csrf_sikap_token_name = csrf_value;
                data.rank1 = $('[name="rank1"]').val();
                data.rank2 = $('[name="rank2"]').val();
            },
            "dataSrc": function(json) {
                //Make your callback here.
                result.attr("disabled", false);
                spinner.hide();
                return json.data;
            }
        },
        "columns": [{
                "data": "id",
                searchable: false
            },
            {
                "data": "nama_nip",
                searchable: true
            },
            {
                "data": "dept_from",
                searchable: false
            },
            {
                "data": "dept_to",
                searchable: false
            }, {
                "data": "riwayat_pindah",
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
            $('td', row).eq(1).addClass('text-nowrap p-1');
            $('td', row).eq(2).addClass('text-nowrap p-1');
            $('td', row).eq(3).addClass('text-nowrap p-1');
            $('td', row).eq(4).addClass('text-nowrap p-1');
            $('td', row).eq(5).addClass('text-nowrap p-1');
            $('td', row).eq(6).addClass('text-nowrap p-1');
        },


    });
    // Initialize
    dt_componen();
});



function confirmAksi(id) {
    $.ajax({
        url: "<?php echo site_url('administrator/riwayat_unitkerja/AjaxDel') ?>",
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
    newWindow = window.open(uri_dasar + 'master/instansi/cetak', "open", 'height=600,width=800');
    if (window.focus) {
        newWindow.focus()
    }
    return false;
})
</script>