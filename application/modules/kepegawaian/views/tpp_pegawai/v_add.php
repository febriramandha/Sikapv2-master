<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Tambah Data Penerima TPP</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>
	<?php
	echo form_open('kepegawaian/tpp_pegawai/AjaxSave', 'class="form-horizontal" id="formAjax"'); ?>
	<div class="card-body">
		<div class="form-group row">
			<label class="col-form-label col-lg-2">Pegawai<span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<select class="form-control select-search" name="pegawai2">
						<option disabled="" selected>Pilih Pegawai</option>
						<?php foreach ($listpeg as $row) { ?>
							<option value="<?php echo encrypt_url($row->id, 'pegawai') ?>"><?php echo $row->nama . ' -- ' . $row->nip ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-2">Bulan/Tahun<span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<select class="form-control select-search" name="tpp_standar">
						<option disabled="" selected>Pilih TPP Standar</option>
						<?php foreach ($listtppstandar as $row) { ?>
							<option value="<?php echo encrypt_url($row->id, 'standar') ?>"><?php echo bulan($row->bulan) . ' - ' . $row->tahun ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<hr>
		<h5>Jenis Besaran TPP</h5>

		<div class="form-group row">
			<label class="col-form-label col-lg-2">Beban Kerja <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<input class="form-control " type="text" name="bebanKerja" id="rupiah" placeholder="Besaran Beban Kerja">
				</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-2">Kondisi Kerja <code></code></label>
			<div class="col-lg-10">
				<div class="form-group">
					<input class="form-control" type="text" id="rupiah_" name="kondisiKerja" placeholder="Besaran Kondisi Kerja" />

				</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-2">Kelangkaan Profesi <code></code> </label>

			<div class="col-lg-10">
				<div class="form-group">
					<input class="form-control" type="text" id="rupiah_kelangkaan" name="kelangkaanProfesi" placeholder="Besaaran Kelangkaan Profesi" />
				</div>
			</div>
		</div>

		<div class="text-left offset-lg-2">
			<div class="btn-group">
				<a href="<?php echo base_url('kepegawaian/tpp_pegawai/index') ?>" class="btn btn-sm bg-success-400">
					Cetak Data Penerima TPP <i class="icon-file-stats ml-2"></i>
				</a>
			</div>
			<button type="submit" class="btn btn-sm bg-blue result" id="tambahButton">
				Tambah Data Penerima T
				PP <i class="icon-pen-plus ml-2"></i> <i class="icon-spinner2 spinner" style="display: none;" id="spinner"></i>
			</button>
		</div>
	</div>
</div>

<?php echo form_close() ?>


<?php $today = date('Y-m-d') ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.readonlyjm').datepicker({
			format: "yyyy-mm-dd",
			autoclose: true,
			todayHighlight: true,
		});

		var rupiah = document.getElementById("rupiah");
		rupiah.addEventListener("keyup", function(e) {
			rupiah.value = formatRupiah(this.value, "Rp. ");
		});

		var rupiah_ = document.getElementById("rupiah_");
		rupiah_.addEventListener("keyup", function(e) {
			rupiah_.value = formatRupiah(this.value, "Rp. ");
		});

		var rupiah_kelangkaan = document.getElementById("rupiah_kelangkaan");
		rupiah_kelangkaan.addEventListener("keyup", function(e) {
			rupiah_kelangkaan.value = formatRupiah(this.value, "Rp. ");
		});


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
				[10, 25, 50, 100, 200, "All"]
			],
			ajax: {
				url: "<?php echo site_url('kepegawaian/tpp_pegawai/indexJson/') ?>",
				type: "post",
				"data": {
					csrf_sikap_token_name: csrf_value
				},
			},

			rowCallback: function(row, data, iDisplayIndex) {
				var info = this.fnPagingInfo();
				var page = info.iPage;
				var length = info.iLength;
				var index = page * length + (iDisplayIndex + 1);
				$('td:eq(0)', row).html(index);

			},


		});

		// Initialize
		dt_componen();
		// loadSettings();
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
					bx_alert_success(res.message, 'kepegawaian/tpp_pegawai');
				} else {
					bx_alert(res.message);
				}
				result.attr("disabled", false);
				spinner.hide();
			}
		});
		return false;
	});


	//nextcode
</script>