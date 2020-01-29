<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Laporan Kehadiran</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="form-group row">
			<label class="col-form-label col-lg-2">Instansi <span class="text-danger">*</span></label>
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
	        <label class="col-form-label col-lg-2">Ketegori Pengguna <span class="text-danger">*</span></label>
	        <div class="col-lg-10">
	          <div class="form-group">
	           <select class="form-control select-nosearch" name="ketegori" >  
	            <option value="0">Semua..</option> 
	            <option value="1">PNS/CPNS</option>
	            <option value="2">NON PNS</option>
	          </select> 
	        </div>
	      </div>
	    </div>
	    <div class="form-group row" id="tpp">
	      <label class="col-form-label col-lg-2">Ketagori Lainnya </label>
	      <div class="col-lg-10">
	          <label class="pure-material-checkbox mt-2"> 
		          <input type="checkbox"  name="tpp" /> <span>pernerima TPP</span>
		        </label>
	      </div>
	    </div>
	    <div class="form-group row">
	        <label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span></label>
	        <div class="col-lg-10">
	          <div class="form-group">
	           <select class="form-control select-nosearch" name="ketegori" >  
	            <option value="0">Semua..</option> 
	            <option value="1">PNS/CPNS</option>
	            <option value="2">NON PNS</option>
	          </select> 
	        </div>
	      </div>
	    </div>
		<div class="form-group row">
			<label class="col-form-label col-lg-2"> Rentang Waktu <span class="text-danger">*</span></label>
			<div class="col-lg-4">
				<div class="form-group-feedback form-group-feedback-left">
					<div class="form-control-feedback">
						<i class="icon-pencil3"></i>
					</div>
					<input type="text" name="rank1" class="form-control datepicker" placeholder="Tanggal awal" >
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
					<input type="text" name="rank2" class="form-control datepicker" placeholder="Tanggal akhir" >
				</div>
			</div>
		</div>
		<div class="text-left offset-lg-2">                
			<button class="btn btn-sm btn-info result" id="kalkulasi">Kalkulasi <i class="icon-search4 ml-2"></i></button>
			<button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
				<span><i class="icon-printer mr-2"></i> Cetak</span>
			</button> 
			<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
		</div>
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<tr class="table-active">
						<th width="1%" rowspan="2">No</th>
						<th class="text-nowrap" rowspan="2">Tanggal</th>
						<th class="text-nowrap" colspan="3">Masuk</th>
						<th class="text-nowrap" colspan="3">Pulang</th>
						<th rowspan="2">DL</th>
						<th rowspan="2">Cuti</th>
						<th rowspan="2">Ket</th>
					</tr>
					<tr class="table-active">
						<th class="px-1" >Jam Masuk</th>
						<th class="px-1">Masuk Kerja</th>
						<th class="px-1">Terlambat</th>

						<th class="px-1">Jam Pulang</th>
						<th class="px-1">Pulang Kerja</th>
						<th class="px-1">Pulang Cepat</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
 $(".datepicker").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
  });
 
var result  = $('.result');
var spinner = $('#spinner');
$('#cetak').click(function() {
		var rank1 		= $('[name="rank1"]').val();
		var rank2 		= $('[name="rank2"]').val();
		var instansi 	= $('[name="instansi"]').val();

		if (rank1 && rank2) {
			newWindow = window.open(uri_dasar + 'report/rabsensi/cetak/'+rank1+'/'+rank2+'/?in='+instansi,"open",'height=600,width=800');
			if (window.focus) {newWindow.focus()}
				return false;
		}else{
			bx_alert('rentang waktu hurus diisi');
		}
		
})


</script>

