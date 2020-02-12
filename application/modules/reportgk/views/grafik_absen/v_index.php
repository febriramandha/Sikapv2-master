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
			<div class="form-group row">
				<label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span> 
					<i class="icon-spinner2 spinner" style="display: none" id="spinner_pegawai"></i>
				</label>
				<div class="col-lg-10">
					<div class="form-group">
						<div id="pegawai">
							<select class="form-control multiselect-clickable-groups" name="pegawai[]" multiple="multiple" data-fouc>
							</select>						
						</div>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-lg-2"> Rentang Waktu <span class="text-danger">*</span></label>
				<div class="col-lg-4">
					<div class="form-group-feedback form-group-feedback-left">
						<div class="form-group">
				           <select class="form-control select-nosearch result" name="tahun" >  
				            <option disabled="">Pilih Tahun..</option> 
				             <?php foreach ($laporan_tahun as $row) {  ?> 
				            	<option value="<?php echo $row->tahun ?>" <?php if ($row->tahun == date('Y')) { echo "selected";} ?>><?php echo $row->tahun ?></option> 
				          	<?php } ?>
				          </select> 
				        </div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="form-group-feedback form-group-feedback-left">
						<div class="form-group">
				           <select class="form-control select-nosearch result" name="bulan" >  
				            <option disabled="">Pilih Bulan..</option> 
				            <?php for ($i=1; $i < 13; $i++) { ?>
				            	<option value="<?php echo $i ?>" <?php if ($i == date('m')) { echo "selected";} ?>><?php echo _bulan($i) ?></option>
				        	<?php } ?>
				          </select> 
				        </div>
					</div>
				</div>
			</div>
			<div class="text-left offset-lg-2">                
				<button class="btn btn-sm btn-info result" id="kalkulasi">Kalkulasi <i class="icon-search4 ml-2"></i></button>
				<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
			</div>
		</div>
		<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
		<div id="grafik" class="col-lg-12">

		</div>

	</div>
</div>


<script type="text/javascript">
$(document).ready(function(){
	DataPegawai();
	LoadGrafik();
});

$('[name="instansi"]').change(function() {
	DataPegawai();
})

$('#kalkulasi').click(function() {
	LoadGrafik();
})

function DataPegawai() {
	var dept_id = $('[name="instansi"]').val();
	var result  = $('.result');
	var spinner = $('#spinner_pegawai');
	$.ajax({
		type: 'get',
		url: uri_dasar+'report/rekap-kehadiran/AjaxGet',
		data: {mod:'DataPegawai',id:dept_id},
		dataType : "html",
		error:function(){
			//result.attr("disabled", false);
       		spinner.hide();
			bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
		},
		beforeSend:function(){
			//result.attr("disabled", true);
      		spinner.show();
		},
		success: function(res) {
			$('#pegawai').html(res);
			//result.attr("disabled", false);
      		spinner.hide();
		}
	});
	
}

function LoadGrafik() {
	var instansi = $('[name="instansi"]').val();
	var pegawai= $('[name="pegawai[]"]').val();
	var tahun  = $('[name="tahun"]').val();
	var bulan  = $('[name="bulan"]').val();
	var result  = $('.result');
	var spinner = $('#spinner');
	$.ajax({
		type: 'get',
		url: uri_dasar+'reportgk/grafik-absen/AjaxGet',
		data: {mod:'Grafik',instansi:instansi, pegawai:pegawai, tahun:tahun, bulan:bulan},
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