<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Edit Tunjangan</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">

  <?php echo form_open('master/allowance/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Uraian <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" class="form-control" name="nama" placeholder="Isi Uraian" value="<?php echo $tunjangan->name ?>">
                  </div>
              </div>
          </div>
         <div class="form-group row">
            <label class="col-form-label col-lg-2">Pilih Esolon <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <div class="form-group" >
                  <?php 
                    foreach ($eselon as $row) {
                      $datacat[$row->id] = $row->eselon; 
                      }
                      echo form_dropdown('eselon', $datacat, $tunjangan->eselon_id,'class="form-control select-nosearch"');
                    ?> 
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Pilih Golongan <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <div class="form-group">
                   <?php 
                    foreach ($golongan as $row) {
                      $datacat[$row->id] = $row->golongan.'('.$row->pangkat.')'; 
                      }
                      echo form_dropdown('golongan', $datacat, $tunjangan->golongan_id,'class="form-control select-nosearch"');
                    ?> 
              </div>
            </div>
          </div>
          <div class="form-group row">
          <label class="col-form-label col-lg-2">Besaran TPP <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <div class="form-group-feedback form-group-feedback-left">
                  <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                  </div>
                  <input type="text" class="form-control" name="tpp" placeholder="Isi besaran TPP" value="<?php echo $tunjangan->tpp ?>">
              </div>
            </div>
          </div>
          <div class="form-group row">
          <label class="col-form-label col-lg-2">Nomor Urut <span class="text-danger">*</span></label>
            <div class="col-lg-2">
              <div class="form-group-feedback form-group-feedback-left">
                  <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                  </div>
                  <input type="number" class="form-control" name="order" value="<?php echo $tunjangan->position ?>">
              </div>
            </div>
          </div>
          
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Status</label>
            <div class="col-lg-10">
              <div class="input-group">
                <span class="input-group-prepend">
                  <span class="input-group-text">
                    <input type="checkbox" name="status" class="form-control-switchery" <?php if ($tunjangan->status == 1) { echo "checked";} ?> data-fouc> 
                  </span>
                </span>
              </div>
            </div>
          </div>
          <input type="hidden" name="id" value="<?php echo encrypt_url($tunjangan->id,'allowance_id') ?>">
          <input type="hidden" name="mod" value="edit">
          <div class="text-left offset-lg-2" >
              <button type="reset" class="btn btn-sm bg-orange-300 result">Batal <i class="icon-cross3 ml-2"></i></button>                 
              <button type="submit" class="btn btn-sm btn-info result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
              <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
          </div>
        </div>
        <?php echo form_close() ?> 
	</div>
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
                bx_alert_successUpadate(res.message, 'master/allowance');
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