<!-- Basic table -->


<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Edit Penerima TPP</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>
	<?php echo form_open('kepegawaian/tpp_pegawai/edit_data','class="form-horizontal" id="formAjax"'); ?>
	<div class="card-body">
		<div class="form-group row">
			<label class="col-form-label col-lg-2">Pegawai<span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<input type="text" class="form-control" value="<?= $data_tpp->nip.' . '.$data_tpp->nama ?>" readonly>
				</div>
			</div>
		</div>

		<input type="hidden" value="<?= $data_tpp->id_tpp; ?>" name="id_tpp">
		<hr>
		<h5>Jenis Besaran TPP</h5>

		<div class="form-group row">
			<label class="col-form-label col-lg-2">Beban Kerja <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<input class="form-control" type="number" id="bebanKerja" name="bebanKerja" placeholder="Besaran Beban Kerja" value="<?= $data_tpp->bbebankerja ?>" min="0"/>

				</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-2">Kondisi Kerja <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<input class="form-control" type="number" id="kondisiKerja" name="kondisiKerja" placeholder="Besaran Kondisi Kerja" value="<?= $data_tpp->bkondisikerja ?>" min="0"/>

				</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-2">Kelangkaan Profesi <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<input class="form-control" type="number" id="kelangkaanProfesi" name="kelangkaanProfesi" placeholder="Besaaran Kelangkaan Profesi" value="<?= $data_tpp->bkelangkaan ?>" min="0"/>

				</div>
			</div>
		</div>


<!--		<input class="form-control" type="number" id="jumlahTPP" name="jumlahTPP" readonly name="jumlahTPP" placeholder="Total" hidden="hidden"  />-->

		<div class="text-left offset-lg-2">
			<span class="btn btn-sm btn-info result" id="submit">Edit Data Penerima TPP<i class="icon-pen-plus ml-2"></i></span>
			<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>
		</div><br>
		<?php echo form_close() ?>


	</div>
</div>


<script>
	$('#submit').click(function() {
		// alert('ok')
		var url = $('#formAjax').attr('action');
		var data = $('#formAjax').serialize();
		// alert(data)
		var result = $('.result');
		var spinner = $('#spinner').show();
		$.ajax({
			type: 'POST',
			url: url,
			data: data,
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
					bx_alert_successUpadate(res.message, 'kepegawaian/tpp_pegawai');
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


