<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Absen Manual Pegawai</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>

	<div class="card-body">
		<div class="form-group row">
	      <label class="col-form-label col-lg-2">Unit Kerja</label>
	      <div class="col-lg-10">
	        <div class="form-group-feedback form-group-feedback-left">
	          <div class="form-control-feedback">
	            <i class="icon-price-tag3"></i>
	          </div>
	          <input type="text" class="form-control" value="<?php echo $instansi->dept_name ?>" readonly="">
	        </div>
	      </div>
	    </div>
		<div class="form-group row">
	      <label class="col-form-label col-lg-2">Nama Jadwal</label>
	      <div class="col-lg-10">
	        <div class="form-group-feedback form-group-feedback-left">
	          <div class="form-control-feedback">
	            <i class="icon-price-tag3"></i>
	          </div>
	          <input type="text"  class="form-control" value="<?php echo $user_data->row()->name ?>" readonly>
	        </div>
	      </div>
	    </div>
	    <div class="form-group row">
	      <label class="col-form-label col-lg-2">Priode</label>
	      <div class="col-lg-10">
	        <div class="form-group-feedback form-group-feedback-left">
	          <div class="form-control-feedback">
	            <i class="icon-price-tag3"></i>
	          </div>
	          <input type="text"  class="form-control" value="<?php echo format_tgl_ind($user_data->row()->start_date) ?> - <?php echo format_tgl_ind($user_data->row()->end_date) ?>" readonly>
	        </div>
	      </div>
	    </div>
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<?php   
						$jum_tanggal = jumlah_hari_rank($user_data->row()->start_date, $user_data->row()->end_date);
						 $pg_array_hari = pg_to_array($user_data->row()->hari_id);

						  $tanggal_data = array();
                           for ($i=0; $i < $jum_tanggal+1; $i++) { 
                                  $tanggal_ =  tgl_plus($user_data->row()->start_date, $i);
                                  $hari_n = tanggal_format(tgl_plus($user_data->row()->start_date, $i),'N');
                                  foreach ($pg_array_hari as $value) {
                                      if ($value == $hari_n) {
                                            $tanggal_data[] = tgl_plus($user_data->row()->start_date, $i);
                                      }
                                  }
                           }


				?>
				<thead>
					<tr class="table-active">
						<th width="1%" rowspan="3">No</th>
						<th class="text-nowrap" rowspan="3">Nama <hr class="m-0">NIP</th>
					</tr>
					<tr class="table-active">
			           <?php 
			              foreach ($tanggal_data as $k_value) { ?>
			                 <th width="1%"><?php echo tanggal_format($k_value,'d') ?></th> 
			           <?php  }
			           ?>
			        </tr>
			        <tr class="table-active">
			           <?php 
			               foreach ($tanggal_data as $k_value) { ?>
			                 <th width="1%"><?php echo substr(hari_tgl($k_value), 0,1); ?></th> 
			           <?php  }
			           ?>
			        </tr>
				</thead>
				<tbody>
					<?php $no=1; foreach ($user_data->result() as $row) { 
							  $id = encrypt_url($row->id,"user_id_absenmanual");
				              $schabsenmanual_id = encrypt_url($row->schabsmanual_id, "schabsmanual_id");

				              $json_data =  $row->json_data;
				              $status_in   = array();
           					  $status_out  = array();

				              if ($json_data) {

				              			$pgarray_ = json_decode($json_data, true);
				              			//$tanggal_row       = $pgarray_[0]['f1'];
						                // $status_in_row    = pg_to_array($row->status_in);
						                // $status_out_row   = pg_to_array($row->status_out);

						                $i = 0;
						                foreach ($pgarray_ as $r_val) {
						                      $n = $i++;
						                      $tgl_val = tanggal_format($pgarray_[$n]['f1'],'Ymd');
						                      $status_in[$tgl_val] = $pgarray_[$n]['f2'];
						                      $status_out[$tgl_val] = $pgarray_[$n]['f3'];

						                }
						                
				              }
						?>
							<tr>
								<td><?php echo $no++ ?></td>
								<td class="text-nowrap"><?php echo nama_icon_nip_link($row->nama,$row->gelar_dpn, $row->gelar_blk,$row->nip,'mngabsenmanual/absen-manualpe/absen/?sch='.$schabsenmanual_id.'&u='.$id) ?></td>
								<?php 
								   $ket ='';
					               foreach ($tanggal_data as $k_value) { 
					               		$tgl_f = tanggal_format($k_value,'Ymd');
										if ($json_data) {
											$status_in_c  = $status_in[$tgl_f];
											$status_out_c = $status_out[$tgl_f];
											$ket_in  = status_absnmanual($status_in_c,'in');
											$ket_out = status_absnmanual($status_out_c,'out');
											$ket = $ket_in.'<hr class="m-0">'.$ket_out;
										}

					               	?>
					                 <td width="1%" class="py-0"><?php echo $ket; ?></td> 
					           <?php  }
					           ?>
							</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		 <div class="text-left offset-lg-1 m-2" >
	        <a href="javascript:history.back()" class="btn btn-sm bg-success-300 result">Kembali <i class="icon-arrow-left5 ml-2"></i></a>                  
	    </div>
	</div>
</div>