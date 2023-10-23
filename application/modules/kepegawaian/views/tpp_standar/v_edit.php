<!-- Basic table -->


<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Edit Besaran Standar TPP</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>
	<?php echo form_open('kepegawaian/tpp_standar/edit_data','class="form-horizontal" id="formAjax"'); ?>
	<div class="card-body">



		<div class="form-group row">
			<label class="col-form-label col-lg-2">Displin Kerja (%) <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<input class="form-control" type="number" id="disiplin_kerja" name="disiplin_kerja" placeholder="Persentase Disiplin Kerja" value="<?= $data_tpp->disiplin_kerja ?>" min="0"/>

				</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-2">Produktivitas Kerja (%) <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<input class="form-control" type="number" id="produktivitas_kerja" name="produktivitas_kerja" placeholder="Persentase Produktivitas Kerja" value="<?= $data_tpp->produktivitas_kerja ?>" min="0"/>

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
							<option value="<?= $no++ ?>" <?php if($no == $data_tpp->bulan+1): ?> selected <?php endif ?>><?= $item ?></option>
						<?php } ?>
					</select>


				</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-2">Tahun <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<input class="form-control" type="number" id="tahun" name="tahun" placeholder="Input Tahun" value="<?= $data_tpp->tahun ?>" min="0"/>

				</div>
			</div>
		</div>

		<input type="hidden" name="id_standar" value="<?= $data_tpp->id ?>">


		<div class="text-left offset-lg-2">
			<span class="btn btn-sm btn-info result" id="submit">Edit Data Standar TPP<i class="icon-pen-plus ml-2"></i></span>
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
					bx_alert_successUpadate(res.message, 'kepegawaian/tpp_standar');
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


