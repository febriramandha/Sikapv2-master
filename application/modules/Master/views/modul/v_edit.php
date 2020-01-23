<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Edit Modul</h5>
		<div class="header-elements">
			<div class="list-icons">
        <a class="list-icons-item" data-action="collapse"></a>
      </div>
    </div>
  </div>

  <div class="card-body">

    <?php echo form_open('master/modul/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
    <div class="col-lg-12">
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Title <span class="text-danger">*</span></label>
        <div class="col-lg-10">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="text" class="form-control" name="title" placeholder="title" value="<?php echo $modul->title ?>">
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Controlles </label>
        <div class="col-lg-10">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="text" class="form-control" name="controller" placeholder="Controlles" value="<?php echo $modul->controller ?>">
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Fungsi</label>
        <div class="col-lg-10">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="text" class="form-control" name="fungsi" placeholder="Fungsi" value="<?php echo $modul->method ?>">
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Url</label>
        <div class="col-lg-10">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="text" class="form-control" name="url" placeholder="Url" value="<?php echo $modul->url ?>">
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Level</label>
        <div class="col-lg-10">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="text" class="form-control" name="level" placeholder="level" value="<?php echo $modul->level ?>">
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Icon</label>
        <div class="col-lg-10">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="text" class="form-control" name="icon" placeholder="Icon" value="<?php echo $modul->icon ?>">
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Nomor Urut<span class="text-danger">*</span></label>
        <div class="col-lg-2">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="number" class="form-control" name="order" placeholder="nomor urut" value="<?php echo $modul->position ?>">
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Status <span class="text-danger">*</span></label>
        <div class="col-lg-10">
          <div class="input-group">
            <span class="input-group-prepend">
              <span class="input-group-text">
                <input type="checkbox" name="status" class="form-control-switchery" <?php if ($modul->status == 1) { echo "checked";} ?> data-fouc> 
              </span>
            </span>
          </div>
        </div>
      </div>
      <input type="hidden" name="mod" value="edit">
      <input type="hidden" name="id" value="<?php echo $modul->id ?>">
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
                bx_alert_successUpadate(res.message, 'master/modul');
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