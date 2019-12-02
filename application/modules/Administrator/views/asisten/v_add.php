<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Tambah Pejabat Asisten</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
		<?php echo form_open('administrator/asisten/AjaxSave','id="formAjax"'); ?>
          <div class="form-group row">
	            <label class="col-form-label col-lg-2">Pilih Pejabat</label>
	            <div class="col-lg-10">
	              <div class="form-group">
	              	  <select name="pejabat" class="form-control select-fixed-single">
	              	  	<option value="">Pilih Pejabat..</option>
	              	  	<?php foreach ($eselon2b as $row): ?>
	              	  		<option value="<?php echo $row->id ?>" ><?php echo $row->nama ?></option>
	              	  	<?php endforeach ?>              	  		
	              	  </select>
	              </div>
	            </div>
          </div>
          <div class="form-group row">
	           <label class="col-form-label col-lg-2">Pilih Instansi</label>
	           <div class="col-lg-10">
	              	<div class="table-responsive">
						<table id="datatable" class="table table-sm table-hover">
							<thead>
								<tr>
									<th width="1%">#</th>
									<th class="text-nowrap">Nama Instansi</th>
								</tr>
							</thead>
							<tbody id="load_dt">
							</tbody>
						</table>
					</div>
	            </div>
          </div>
          <input type="hidden" name="mod" value="add">
          <div class="text-center">
				<button type="reset" class="btn btn-sm btn-default">Batal <i class="icon-cross3 ml-2"></i></button>
				<button type="submit" class="btn btn-sm btn-info" id="result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
		  </div>

          
		  <?php echo form_close(); ?>
	</div>
</div>
<!-- /basic table -->

<script type="text/javascript">
$('.select-fixed-single').select2({
    minimumResultsForSearch: Infinity,
    // width: 350
});
var table;
$(document).ready(function(){

     table = $('#datatable').DataTable({ 
	    processing: true, 
	    serverSide: true, 
	    "ordering": false,
	    paging:false,
	   	"searching": false,
	    ajax: {
	        url : "<?php echo site_url('administrator/asisten/JsonInstansi') ?>",
	        type:"post",
	        "data": function ( data ) {	
        				 data.csrf_sikap_token_name= csrf_value;
        				 data.cek_instansi= $('[name="pejabat"]').val();
	            },
	        beforeSend:function(){
		        	load_dt('#load_dt');
		     },
	    },
	    "columns": [
	        {"data": "action", searchable:false},
	        {"data": "dept_name", searchable:false},
	    ],

	});

});

$('#formAjax').submit(function() {
	var result = $('#result');
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType : "JSON",
        error:function(){
	      	 result.html('<span><i class="icon-checkmark4 ml-2"></i> Simpan</span>');
	      	 result.attr("disabled", false);
	      	 bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
	    },
	    beforeSend:function(){
       		result.html('<i class="icon-spinner2 spinner"></i> Proses..');
 			result.attr("disabled", true);
	    },
        success: function(res) {
            if (res.status == true) {
            	window.location.assign(uri_dasar+'administrator/asisten');
                bx_alert_ok(res.message,'success');
            }else {
                bx_alert(res.message);
            }
            result.html('<span><i class="icon-checkmark4 ml-2"></i> Simpan</span>');
	        result.attr("disabled", false);
        }
    });
    return false;
});


$('[name="pejabat"]').change(function(){ 
	table.ajax.reload();
 });


</script>