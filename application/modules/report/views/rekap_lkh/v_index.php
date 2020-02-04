<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Rekapitulasi LKH</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>
	<?php echo form_open('report/rekap-kehadiran/cetak','class="form-horizontal" target="popup" id="formAjax"'); ?>
	<div class="card-body">
		<div class="form-group row">
			<label class="col-form-label col-lg-2">Instansi <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<select class="form-control select-search result" name="instansi"> 
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
	           <select class="form-control select-nosearch result" name="kategori" >  
	            <option value="0">Semua..</option> 
	            <option value="1">PNS</option>
	            <option value="2">NON PNS</option>
	          </select> 
	        </div>
	      </div>
	    </div>
	    <div class="form-group row" id="tpp">
	      <label class="col-form-label col-lg-2">Ketagori Lainnya </label>
	      <div class="col-lg-10">
	          <label class="pure-material-checkbox mt-2"> 
		          <input type="checkbox" class="result"  name="tpp" /> <span>Pernerima TPP</span>
		        </label>
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
			<span class="btn btn-sm btn-info result" id="kalkulasi">Kalkulasi <i class="icon-search4 ml-2"></i></span>
			<button type="submit" class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1 result" id="cetak">
				<span><i class="icon-printer mr-2"></i> Cetak</span>
			</button> 
			<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
		</div>
		<?php echo form_close() ?>
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<tr class="table-active">
						  <th width="1%">No</th>
						  <th >Nama/NIP</th>
						  <th width="1%" >Jumlah Hari kerja 1 Bulan</th>
                          <th width="1%">Jumlah Laporan Kerja Harian</th>	
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>