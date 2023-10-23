<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Besaran TPP Sesuai Standar</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>
	<?php echo form_open('kepegawaian/tpp_standar/AjaxSave','class="form-horizontal" target="popup" id="formAjax"'); ?>
	<div class="card-body">

		<div class="form-group row">
			<label class="col-form-label col-lg-2">Displin Kerja (%) <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<input class="form-control" type="number" id="disiplin_kerja" name="disiplin_kerja" placeholder="Persentase Disiplin Kerja" value="0" min="0"/>

				</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-2">Produktivitas Kerja (%) <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<input class="form-control" type="number" id="produktivitas_kerja" name="produktivitas_kerja" placeholder="Persentase Produktivitas Kerja" value="0" min="0"/>

				</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-2">Bulan <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<select name="bulan" id="bulan" required class="form-control">
						<?php $no = 1 ?>
						<?php foreach($nama_bulan as $item) { ?>
						<option value="<?= $no++ ?>"><?= $item ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-2">Tahun <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<input class="form-control" type="number" id="tahun" name="tahun" placeholder="Input Tahun" value="0" min="0"/>

				</div>
			</div>
		</div>


		<div class="text-left offset-lg-2">
			<span class="btn btn-sm btn-info result" id="submit">Tambah Data Standar TPP<i class="icon-pen-plus ml-2"></i></span>
			<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>
<!--			<button type="submit" class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1 result" id="cetak">-->
<!--				<span><i class="icon-printer mr-2"></i>Cetak Laporan</span>-->
<!--			</button>-->
		</div><br>
		<?php echo form_close() ?>

		<div class="table-responsive">
			<!--Datatables-->
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
				<tr class="table-active text-center">
					<th width="1%">No</th>
					<th width="1%">Disiplin Kerja</th>
					<th width="1%" >Produktivitas Kerja</th>
					<th width="1%" >Waktu</th>
					<th width="1%" >Aksi</th>
				</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

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
			"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200, "All"]],
			ajax: {
				url : "<?php echo site_url('kepegawaian/tpp_standar/indexJson') ?>",
				type:"post",
				"data": {csrf_sikap_token_name: csrf_value},
			},
			"columns": [
				{"data": "id", searchable:false},
				{"data": "disiplin_kerja", searchable:false},
				{"data": "produktivitas_kerja", searchable:false},
				{"data": "tahun", searchable:false},
				{"data": "action", searchable:false},
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
				$('td', row).eq(2).addClass('normal_text p-0');
				$('td', row).eq(3).addClass('normal_text p-0');
				$('td', row).eq(4).addClass('text-nowrap p-1');
				$('td', row).eq(5).addClass('text-nowrap text-center');
			},

		});

		// Initialize
		dt_componen();
		// loadSettings();
	});



	$('#submit').click(function() {
		var url = $('#formAjax').attr('action');
		var data = $('#formAjax').serialize();

		var pegawai = $('[name="pegawai[]"]').val();
		$('#idpeg').val(pegawai);
		var bebanKerja = $('[name="bebanKerja"]').val();
		var kondisiKerja = $('[name="kondisiKerja"]').val();
		var kelangkaanProfesi = $('[name="kelangkaanProfesi"]').val();
		var jumlahTPP = $('[name="jumlahTPP"]').val();

		$.ajax({
			type : "POST",
			url  : url,
			data : data,
			success: function(res) {
				location.reload();
			}
		});




	})

	// Tampil data di tabel bawah
	$(document).ready(function() {
		$('#datatable2').DataTable({
			dom: 'Bfrtip',
			buttons: [
				'pdf',
				'excel'
			]
		});
	});

	//	akhir data di tabel bawah

	$(function () {
		$("#bebanKerja, #kondisiKerja, #kelangkaanProfesi").keyup(function () {
			$("#jumlahTPP").val(+$("#bebanKerja").val() + +$("#kondisiKerja").val() + +$("#kelangkaanProfesi").val());
		});
	});


	function confirmAksi(id) {
        $.ajax({
            url: "<?php echo site_url('kepegawaian/tpp_standar/AjaxDel') ?>",
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
