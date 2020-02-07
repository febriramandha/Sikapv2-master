<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Piket Manual Pegawai</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>

	<div class="card-body">
		<h6><?php echo $user_data->row()->name ?></h6><hr>
	    <div class="form-group row">
	        <label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span></label>
	        <div class="col-lg-10">
	          <div class="form-group">
	           <select class="form-control select-search" name="instansi"> 
	            <?php foreach ($user_data->result() as $row) { ?>
	              		<option><?php echo $row->nama ?>(<?php echo $row->nip ?>)</option>
	            <?php } ?>
	          </select> 
	        </div>
	      </div>
	    </div>
	    <div class="form-group row">
	        <label class="col-form-label col-lg-2">Tanggal Piket <span class="text-danger">*</span></label>
	        <div class="col-lg-2">
	          	<div class="form-group">
		           <select class="form-control select-nosearch" name="instansi"> 
		           		<?php 
		           				$jumlah_hari = jumlah_hari_rank($user_data->row()->start_date, $user_data->row()->end_date);

		           				if ($jumlah_hari > 31) {
		           						$jumlah_hari = 31;
		           				}

		           		 ?>
		           		 <?php for ($i=1; $i < $jumlah_hari+1; $i++) { ?>
			           		<option><?php echo $i ?></option>
			           	 <?php } ?>
		          </select> 
	        	</div>
	      	</div>
	      	<div class="col-lg-2">
	          	<div class="form-group">
		           <select class="form-control select-nosearch" name="instansi"> 
		           		<?php 
		           				$jumlah_bulan = jumlah_bulan_rank($user_data->row()->start_date, $user_data->row()->end_date);

		           		 ?>
		           		 <?php 
		           		 		for ($i=0; $i < $jumlah_bulan; $i++) { 
		           		 			$bulan = tanggal_format(bulan_plus($user_data->row()->start_date, $i),'m');

		           		  ?>
			           		<option><?php echo _bulan((int) $bulan); ?></option>
			           	<?php } ?>
		          </select> 
	        	</div>
	      	</div>
	      	<div class="col-lg-2">
	          	<div class="form-group">
		           <select class="form-control select-nosearch" name="instansi"> 
		           		<?php 
		           			 $tahun_start = tanggal_format($user_data->row()->start_date,'Y');
		           			 $tahun_end   = tanggal_format($user_data->row()->end_date,'Y');
		           			 $total_tahun = $tahun_end-$tahun_start;
		           		 ?>
		           		 <?php for ($i=0; $i < $total_tahun+1; $i++) { ?>
			           		<option><?php echo $tahun_start+$i ?></option>
			       		<?php } ?>
		          </select> 
	        	</div>
	      	</div>
	    </div>
	    <?php 
	    	$cek =  Cek_tanggalValid('2019-03-29');
	    	if ($cek) {
	    		echo "ya";
	    	}

	     ?>
	    <input type="hidden" name="id">
          <input type="hidden" name="mod" value="add">
          <div class="text-left offset-lg-2" >
             <a href="javascript:history.back()" class="btn btn-sm bg-success-300 result">Kembali <i class="icon-arrow-left5 ml-2"></i></a>                    
              <button type="submit" class="btn btn-sm btn-info result" id="result">Tambah Piket <i class="icon-pen-plus ml-2"></i></button>
              <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
         </div><br>
	    <div class="form-group row">
	        <label class="col-form-label col-lg-2"></label>
	        <div class="col-lg-10">
		        <div class="table-responsive">
					<table id="datatable" class="table table-sm table-hover table-bordered">
						<thead>
							<tr class="table-active">
								<th width="1%" >No</th>
								<th class="text-nowrap" >Tanggal</th>
								<th class="text-nowrap">Nama (NIP)</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
	    </div>
	</div>
</div>