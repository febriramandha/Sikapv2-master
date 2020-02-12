<div class="card card-body" >
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group row">
				<label class="col-form-label col-lg-2">Unit Kerja <span class="text-danger">*</span></label>
				<div class="col-lg-10">
					<div class="form-group">
						<select class="form-control select-search" name="instansi"> 
							<?php foreach ($instansi as $row) { ?>
								<option value="<?php echo encrypt_url($row->id,'instansi') ?>"><?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?></option>
							<?php } ?>
						</select> 
					</div>
				</div>
			</div>
		</div>
		<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
		<div id="grafik" class="col-lg-12">

		</div>
		
	</div>
</div>


<script type="text/javascript">
$(document).ready(function(){
	LoadGrafik();
});

$('[name="instansi"]').change(function() {
	LoadGrafik();
})

function LoadGrafik() {
	var instansi = $('[name="instansi"]').val();
	var result  = $('.result');
	var spinner = $('#spinner');
	$.ajax({
		type: 'get',
		url: uri_dasar+'reportgk/grafik-pegawai/AjaxGet',
		data: {mod:'Grafik',instansi:instansi},
		dataType : "html",
		error:function(){
			result.attr("disabled", false);
       		spinner.hide();
			bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
			$('#grafik').unblock();
		},
		beforeSend:function(){
			result.attr("disabled", true);
      		spinner.show();
      		load_dt('#grafik');
		},
		success: function(res) {
			$('#grafik').html(res);
			result.attr("disabled", false);
      		spinner.hide();
      		$('#grafik').unblock();
		}
	});
}

</script>