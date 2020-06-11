<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Tambah Jabatan</h5>
		<div class="header-elements">
			<div class="list-icons">
        <a class="list-icons-item" data-action="collapse"></a>
      </div>
    </div>
  </div>

  <div class="card-body">

    <?php echo form_open('master/jabatan/AjaxSave/'.$this->uri->segment(4),'class="form-horizontal" id="formAjax"'); ?>
    <div class="col-lg-12">

    <div class="form-group row">
      <label class="col-form-label col-lg-2">Nama Jabatan<span class="text-danger">*</span></label>
      <div class="col-lg-10">
        <div class="form-group-feedback form-group-feedback-left">
          <div class="form-control-feedback">
            <i class="icon-pencil3"></i>
          </div>
          <input type="text" class="form-control" name="nama_jabatan" value="<?php echo $jabatan->nama_jabatan ?>" placeholder="Isi nama jabatan disini">
        </div>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-lg-2">Status <span class="text-danger">*</span></label>
      <div class="col-lg-10">
        <div class="input-group">
          <span class="input-group-prepend">
            <span class="input-group-text">
              <input type="checkbox" name="status" class="form-control-switchery" <?php if ($jabatan->status == 1) { echo "checked";} ?> data-fouc> 
            </span>
          </span>
        </div>
      </div>
    </div>
    <input type="hidden" name="mod" value="edit">
    <div class="text-left offset-lg-2" >
       <a href="#" onclick="javascript:history.go(-1)" class="btn btn-sm bg-warning legitRipple"><i class="icon-undo2"></i> Kembali</a>                  
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
        bx_alert_successUpadate(res.message);
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