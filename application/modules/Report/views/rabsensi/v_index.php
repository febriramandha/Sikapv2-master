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
						<th width="1%" >No</th>
						<th class="text-nowrap text-center" width="1%">Tanggal</th>
						<th class="text-nowrap text-center p-1" width="1%">Jam <hr class="m-0">(mulai - selesai)</th>
						<th class="text-nowrap text-center pl-md-3 pl-5 pr-md-3 pr-5" >Uraian Kegiatan</th>
						<th class="text-nowrap text-center pl-md-3 pl-5 pr-md-3 pr-5" >Hasil</th>
						<th width="1%" class="p-1">Status</th>
						<th width="1%" style="font-size: 80%;" class="normal_text p-1">Pejabat <br>pemeriksa</th>
						<th width="1%" class="p-1">Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>