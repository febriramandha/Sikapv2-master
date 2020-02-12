<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Rekap LKH Manual Pegawai</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>

	<div class="card-body">
	 <?php echo form_open('mnglkh/rekap-lkhmanualpe/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
	    <div class="form-group row">
	        <label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span></label>
	        <div class="col-lg-10">
		        <div class="table-responsive">
					<table id="datatable" class="table table-sm table-hover table-bordered">
						<thead>
							<tr class="table-active">
								<th width="1%" >No</th>
								<th class="text-nowrap" >Nama (NIP)</th>
								<th class="text-nowrap" width="1%">Jumlah LKH</th>
								<th class="text-nowrap" width="1%">Total LKH</th>
							</tr>
						</thead>
						<tbody>
							<?php $no=1; foreach ($user_data->result() as $row) {
								$jumlah_laporan = '0';
								$total_laporan  = '0';
								$cek = '';

								if ($row->jumlah_laporan != null) {
										$jumlah_laporan = $row->jumlah_laporan;
										$cek =1;
								}

								if ($row->total_laporan != null) {
										$total_laporan  = $row->total_laporan;
										$cek =1;
								}

							 ?>
							<tr>
								<td><?php echo $no++ ?></td>
								<td><?php echo $row->nama ?></td>
								<td class="py-0"><input type="text" class="form-control" name="jumlah[<?php echo $row->id ?>]" value="<?php echo $jumlah_laporan ?>"></td>
								<td class="py-0"><input type="text" class="form-control" name="total[<?php echo $row->id ?>]" value="<?php echo $total_laporan ?>"></td>

								<input type="hidden" name="user[]" value="<?php echo encrypt_url($row->id,'user_id_rekap_lkh_manual') ?>" >
								<input type="hidden" name="rekapmanual_id[<?php echo $row->id ?>]" value="<?php echo encrypt_url($row->rekapmanual_id,'rekapmanual_id') ?>" >
								
								<input type="hidden" name="cek[<?php echo $row->id ?>]" value="<?php echo $cek ?>" >
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
	    </div>
	    <input type="hidden" name="sch" value="<?php echo $this->uri->segment(4) ?>">
	    <input type="hidden" name="mod" value="add">
          <div class="text-left offset-lg-2" >
             <a href="javascript:history.back()" class="btn btn-sm bg-success-300 result">Kembali <i class="icon-arrow-left5 ml-2"></i></a>                    
              <button type="submit" class="btn btn-sm btn-info result" id="result">Simpan</button>
              <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
         </div><br>
	</div>
	<?php echo form_close() ?>
</div>

<script type="text/javascript">
 $('#formAjax').submit(function() {
    var result  = $('.result');
    var spinner = $('#spinner');
    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data: $(this).serialize(),
      dataType : "JSON",
      error:function(){
       result.attr("disabled", false);
       spinner.hide();
       bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
     },
     beforeSend:function(){
      result.attr("disabled", true);
      spinner.show();
    },
    success: function(res) {
      if (res.status == true) {
        bx_alert_success(res.message, 'mnglkh/rekap-lkhmanualpe');
      }else {
        bx_alert(res.message);
      }
      result.attr("disabled", false);
      spinner.hide();
    }
  });
    return false;
  });
</script>