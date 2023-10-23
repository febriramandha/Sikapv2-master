<style type="text/css">
.gradient_1 {
    background-image: linear-gradient(to right, #f6d365 0%, #fda085 51%, #f6d365 100%);
}

.gradient_2 {
    background-image: linear-gradient(to right, #fbc2eb 0%, #a6c1ee 51%, #fbc2eb 100%);
}

.gradient_3 {
    background-image: linear-gradient(to right, #84fab0 0%, #8fd3f4 51%, #84fab0 100%);
}

.gradient_4 {
    background-image: linear-gradient(to right, #a1c4fd 0%, #c2e9fb 51%, #a1c4fd 100%);
}

.gradient_5 {
    background-image: linear-gradient(to right, #ffecd2 0%, #fcb69f 51%, #ffecd2 100%);
}
</style>

<!-- Basic table -->
<div class="card">
    <div class="card-header bg-white header-elements-inline py-2">
        <h5 class="card-title">Rekapitulasi Penerimaan TPP</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <?php echo form_open('report/rekap-tpp/cetak','class="form-horizontal" target="popup" id="formAjax"'); ?>
    <div class="card-body">
    <div class="form-group row">
				<label class="col-form-label col-lg-2">Pilih Data<span class="text-danger">*</span></label>
				<div class="col-lg-10">
					<div class="form-group">
						<select class="form-control select-search" name="tpp_standar">
							<?php foreach ($listtppstandar as $row) { ?>
								<option value="<?php echo encrypt_url($row->id,'standar') ?>"><?php echo bulan($row->bulan).' - '.$row->tahun ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>

        <div class="text-left offset-lg-2">
            <span class="btn btn-sm btn-info result" id="kalkulasi">Tampilkan <i class="icon-search4 ml-2"></i></span>
            <!-- <button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
                <span><i class="icon-printer mr-2"></i> Cetak</span>
            </button> -->

        </div>
        <?php echo form_close() ?>
        <div class="table-responsive">
            <table id="datatable" class="table table-sm table-hover table-bordered">
                <thead>
                <tr class="table-active text-center">
					<th width="1%" rowspan="2">No</th>
					<th width="1%" rowspan="2">NIP/Nama</th>
					<th width="1%" rowspan="2">Berdasarkan Beban Kerja</th>
					<th width="1%" rowspan="2">Berdasarkan Kondisi Kerja</th>
					<th width="1%" rowspan="2">Berdasarkan Kelangkaan Profesi</th>
					<th  width="1%" rowspan="2">Total Besaran TPP Awal (3+4+5)</th>
					<th rowspan="1" colspan="2" class="p-1" width="1%">Besaran TPP Sesuai Standar</th>
					<th rowspan="1" colspan="2" class="p-1" width="1%">Besaran Pemotongan</th>
					<th width="1%" rowspan="2">Jumlah TPP (7-9) + (8-10)</th>
					<th width="1%" rowspan="2">Potongan PPH</th>
					<th width="1%" rowspan="2">Jml Setelah Pemotongan PPH</th>
					<th width="1%" rowspan="2">Potongan BPJS (1%)</th>
					<th width="1%" rowspan="2">Jml Setelah Pemotongan BPJS</th>
					<th width="1%" rowspan="2">Potongan Zakat</th>
					<th width="1%" rowspan="2">Jumlah Diterima</th>
				</tr>
				<tr class="table-active text-center">
					<th width="1%" class="p-1">Aspek Disiplin Kerja</th>
					<th width="1%" class="p-1">Aspek Produktivitas Kerja</th>
					<th width="1%" class="p-1">Aspek Disiplin Kerja</th>
					<th width="1%" class="p-1">Aspek Produktivitas Kerja</th>
				</tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
$('.multiselect-clickable-groups').multiselect({
    includeSelectAllOption: true,
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
    placeholder: 'Pilih Pegawai',
});

var result = $('.result');
var spinner = $('#spinner');
$(document).ready(function() {
    table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        "ordering": false,
        "searching": false,
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
            url: uri_dasar + 'kepegawaian/tpp_individu/AjaxGet',
            type: "post",
            "data": function(data) {
                data.csrf_sikap_token_name = csrf_value;
                data.bulan = $('[name="tpp_standar"]').val();
            },
            "dataSrc": function(json) {
                //Make your callback here.
                result.attr("disabled", false);
                spinner.hide();
                return json.data;
            }
        },
        "columns": [
				{"data": "id", searchable:false},
				{"data": "pegawai2", searchable:false},
				{"data": "bbebankerja", searchable:false},
				{"data": "bkondisikerja", searchable:false},
				{"data": "bkelangkaan", searchable:false},
				{"data": "totaltpp2", searchable:false},
				{"data": "disiplin_kerja", searchable:false},
				{"data": "produktivitas_kerja", searchable:false},
				{"data": "potongan_disiplin", searchable:false},
				{"data": "potongan_produktivitas", searchable:false},
				{"data": "hasiltpp", searchable:false},
				{"data": "potongan_pph", searchable:false},
				{"data": "setelah_potongpph", searchable:false},
				{"data": "potongan_bpjs", searchable:false},
				{"data": "setelah_potongbpjs", searchable:false},
				{"data": "potongan_zakat", searchable:false},
				{"data": "setelah_potongzakat", searchable:false},
			],

        rowsGroup: [1],
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $('td:eq(0)', row).html(index);
        },
        
        createdRow: function(row, data, index) {
            $('td', row).eq(1).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(2).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(3).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(4).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(5).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(6).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(7).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(8).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(9).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(10).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(11).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(12).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(13).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(14).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(15).addClass('text-nowrap p-1 text-center');
            $('td', row).eq(16).addClass('text-nowrap p-1 text-center');

        },

    });

    // Initialize
    dt_componen();

});

$('#kalkulasi').click(function() {
    result.attr("disabled", true);
    spinner.show();
    table.ajax.reload();
})



$('#cetak').click(function() {
    var idbulan = $('[name="tpp_standar"]').val();
    if (idbulan) {
        newWindow = window.open(uri_dasar + 'kepegawaian/tpp_individu/cetak/' + idbulan, "open",
            'height=600,width=1000');
        if (window.focus) {
            newWindow.focus()
        }
        return false;
    } else {
        bx_alert('Pilih Data Terlebih Dahulu');
    }

})

function confirmAksi(id) {
        $.ajax({
            url: "<?php echo site_url('kepegawaian/tpp_individu/AjaxDel') ?>",
            data: {id: id},
            dataType :"json",
            success: function(res){
                if (res.status == true) {
                    table.ajax.reload();
                    toastr["success"](res.msg);
                }else {
                    toastr["warning"](res.msg);
                }
                
            }
        });
    }

</script>