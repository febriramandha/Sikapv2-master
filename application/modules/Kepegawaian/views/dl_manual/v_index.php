<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Data Dinas</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">

		<?php echo form_open('kepegawaian/dl-manual/view/','class="form-horizontal" id="formAjax"'); ?>
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Instansi <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group">
                       <select class="form-control select-search" name="instansi" >  
                              <option disabled="">Pilih Instansi</option> 
                              <?php foreach ($instansi as $row) { ?>
                                <option value="<?php echo encrypt_url($row->id,'instansi') ?>"><?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?></option>
                              <?php } ?>
                      </select> 
                  </div>
              </div>
          </div>
          <input type="hidden" name="mod" value="next">
          <div class="text-left offset-lg-2" >                 
              <span class="btn btn-sm btn-info result" id="simpan">Pilih dan Lanjutkan <i class="icon-next2 ml-2"></i></span>
              <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
          </div>
        </div>
        <?php echo form_close() ?>  
	</div>
</div>

<script type="text/javascript">
$('#simpan').click(function() {
    var id = $('[name="instansi"]').val();
    window.location.href = uri_dasar+'kepegawaian/dl-manual/view/'+id; 
});
</script>