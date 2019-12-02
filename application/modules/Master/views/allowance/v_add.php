<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Tambah Tunjangan</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">

  <?php echo form_open('master/mesin/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Uraian <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" class="form-control" name="nama" placeholder="Isi Uraian">
                  </div>
              </div>
          </div>
         <div class="form-group row">
            <label class="col-form-label col-lg-2">Pilih Esolon <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <div class="form-group" >
                  <select class="form-control select-nosearch" name="eselon" placeholder="Pilih Esolon">
                      <?php foreach ($eselon as $row) { ?>
                        <option value="<?php echo $row->id ?>"><?php echo $row->eselon ?></option>
                      <?php } ?>
                  </select>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Pilih Golongan <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <div class="form-group">
                 <select class="form-control select-nosearch" name="golongan" placeholder="Pilih Esolon">
                      <?php foreach ($golongan as $row) { ?>
                        <option value="<?php echo $row->id ?>"><?php echo $row->golongan ?>(<?php echo $row->pangkat ?>)</option>
                      <?php } ?>
                  </select>
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
                  <input type="text" class="form-control" name="tpp" placeholder="Isi besaran TPP" >
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
                  <input type="number" class="form-control" name="order" value="<?php echo $position ?>">
              </div>
            </div>
          </div>
          
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Status</label>
            <div class="col-lg-10">
              <div class="input-group">
                <span class="input-group-prepend">
                  <span class="input-group-text">
                    <input type="checkbox" name="status" class="form-control-switchery" checked data-fouc> 
                  </span>
                </span>
              </div>
            </div>
          </div>
          <input type="hidden" name="mod" value="add">
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
                bx_alert_success(res.message, 'master/mesin');
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