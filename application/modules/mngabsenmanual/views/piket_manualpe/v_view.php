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
	 <?php echo form_open('mngabsenmanual/piket-manualpe/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
		<h6><?php echo $user_data->row()->name ?></h6><hr>
	    <div class="form-group row">
	        <label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span></label>
	        <div class="col-lg-10">
	          <div class="form-group">
	           <select class="form-control select-search" name="u"> 
	            <?php foreach ($user_data->result() as $row) { ?>
	              		<option value="<?php echo encrypt_url($row->id,'user_id_absenmanual_piket') ?>"><?php echo $row->nama ?>(<?php echo $row->nip ?>)</option>
	            <?php } ?>
	          </select> 
	        </div>
	      </div>
	    </div>
	    <div class="form-group row">
	        <label class="col-form-label col-lg-2">Tanggal Piket <span class="text-danger">*</span></label>
	        <div class="col-lg-2">
	          	<div class="form-group">
		           <select class="form-control select-nosearch" name="tahun"> 
		           		<?php 
		           			 $tahun_start = tanggal_format($user_data->row()->start_date,'Y');
		           			 $tahun_end   = tanggal_format($user_data->row()->end_date,'Y');
		           			 $total_tahun = $tahun_end-$tahun_start;
		           		 ?>
		           		 <?php for ($i=0; $i < $total_tahun+1; $i++) { 
		           		 			$tahun_n = $tahun_start+$i;
		           		 	?>
			           		<option value="<?php echo encrypt_url($tahun_n,'tahun_piket') ?>"><?php echo $tahun_n ?></option>
			       		<?php } ?>
		          </select> 
	        	</div>
	      	</div>
	      	<div class="col-lg-2">
	          	<div class="form-group">
		           <select class="form-control select-nosearch" name="bulan"> 
		           		<?php 
		           				$jumlah_bulan = jumlah_bulan_rank($user_data->row()->start_date, $user_data->row()->end_date);

		           		 ?>
		           		 <?php 
		           		 		for ($i=0; $i < $jumlah_bulan; $i++) { 
		           		 			$bulan = tanggal_format(bulan_plus($user_data->row()->start_date, $i),'m');
		           		 			$bulan_in = (int) $bulan;
		           		  ?>
			           		<option value="<?php echo encrypt_url($bulan_in,'bulan_piket') ?>"><?php echo _bulan($bulan_in); ?></option>
			           	<?php } ?>
		          </select> 
	        	</div>
	      	</div>
	      	 <div class="col-lg-2">
	          	<div class="form-group">
		           <select class="form-control select-nosearch" name="tanggal"> 
		           		<?php 
		           				$jumlah_hari = jumlah_hari_rank($user_data->row()->start_date, $user_data->row()->end_date);

		           				if ($jumlah_hari > 31) {
		           						$jumlah_hari = 31;
		           				}
		           		 ?>
		           		 <?php for ($i=1; $i < $jumlah_hari+1; $i++) { ?>
			           		<option value="<?php echo encrypt_url($i,'tanggal_piket') ?>"><?php echo $i ?></option>
			           	 <?php } ?>
		          </select> 
	        	</div>
	      	</div>
	    </div>
	    <div class="form-group row">
	    	<label class="col-form-label col-lg-2">Jenis Piket <span class="text-danger">*</span></label>
	    	<?php if ($user_data->row()->absen_in) { ?>
	    	<div class="col-lg-2">
	    		<label class="pure-material-checkbox"> 
	    			<input type="checkbox"  name="in"  /> <span>Piket Masuk</span>
	    		</label>
	    	</div>
	    	<?php } ?>
	    	<?php if ($user_data->row()->absen_out) { ?>
	    	<div class="col-lg-2">
	    		<label class="pure-material-checkbox"> 
	    			<input type="checkbox" name="out" /> <span>Piket Pulang</span>
	    		</label>
	    	</div>
	    	<?php } ?>
	    </div>
	    <input type="hidden" name="cekin" value="<?php echo encrypt_url($user_data->row()->absen_in,'absen_in_piket') ?>">
	    <input type="hidden" name="cekout" value="<?php echo encrypt_url($user_data->row()->absen_out,'absen_out_piket') ?>">
	    <input type="hidden" name="sch" value="<?php echo encrypt_url($user_data->row()->schabsmanual_id,'schabsmanual_id_piket') ?>">
        <input type="hidden" name="mod" value="add">
          <div class="text-left offset-lg-2" >
             <a href="javascript:history.back()" class="btn btn-sm bg-success-300 result">Kembali <i class="icon-arrow-left5 ml-2"></i></a>                    
              <button type="submit" class="btn btn-sm btn-info result" id="result">Tambah Piket <i class="icon-pen-plus ml-2"></i></button>
              <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
         </div><br>
         <?php echo form_close() ?>
	    <div class="form-group row">
	        <label class="col-form-label col-lg-2"></label>
	        <div class="col-lg-10">
		        <div class="table-responsive">
					<table id="datatable" class="table table-sm table-hover table-bordered">
						<thead>
							<tr class="table-active">
								<th width="1%" rowspan="2">No</th>
								<th class="text-nowrap" rowspan="2">Tanggal</th>
								<th class="text-nowrap" rowspan="2">Nama (NIP)</th>
								<th class="text-nowrap" colspan="2">Jenis Piket</th>
								<th width="1%" rowspan="2">Aksi</th>
							</tr>
							<tr class="table-active">
								<th>Masuk</th>
								<th>Pulang</th>
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
<script type="text/javascript">
 $(document).ready(function(){
	   table = $('#datatable').DataTable({ 
	    processing: true, 
	    serverSide: true, 
	    "ordering": false,
	    "paging": false,
	    "searching": false,
	    language: {
	      search: '<span></span> _INPUT_',
	      searchPlaceholder: 'Cari...',
	      processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
	    },  
	    ajax: {
	      url : uri_dasar+'mngabsenmanual/piket-manualpe/absenJson',
	      type:"post",
	      "data": function ( data ) { 
	        data.csrf_sikap_token_name= csrf_value;
	        data.sch=$('[name="sch"]').val();
	        
	      },
	    },
	    "columns": [
	    {"data": "id", searchable:false},
	    {"data": "tanggal", searchable:false},
	    {"data": "nama", searchable:false},
	    {"data": "status_in", searchable:false},
	    {"data": "status_out", searchable:false},
	    {"data": "action", searchable:false},
	    ],
	    rowCallback: function(row, data, iDisplayIndex) {
	      var info = this.fnPagingInfo();
	      var page = info.iPage;
	      var length = info.iLength;
	      var index = page * length + (iDisplayIndex + 1);
	      $('td:eq(0)', row).html(index);
	    },
	    createdRow: function(row, data, index) {
	          // $('td', row).eq(5).addClass('text-center');
	          $('td', row).eq(1).addClass('text-nowrap');
	        },


	      });
 });

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
                  bx_alert(res.message);
                  table.ajax.reload();
					// $('[name="u"]').val('').trigger('change');
              }else {
                  bx_alert(res.message);
              }
              result.attr("disabled", false);
              spinner.hide();
          }
      });
      return false;
  });

function confirmAksi(id) {
		$.ajax({
			url: uri_dasar+'mngabsenmanual/piket-manualpe/AjaxDel',
			data: {id: id},
			dataType :"json",
			error:function(){
				bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
			},
			success: function(res){
				if (res.status == true) {
					table.ajax.reload();
					bx_alert_ok(res.message,'success');
				}else {
					bx_alert(res.message);
				}
				
			}
		});
	}



</script>