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
      <?php if ($this->input->get('at')) { ?>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Jabatan Atasan</label>
        <div class="col-lg-10">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-price-tag3"></i>
            </div>
            <input type="text" name="atasan" class="form-control" value="<?php echo $atasan->nama_jabatan ?>"  readonly>
          </div>
        </div>
      </div>
      <input type="hidden" name="parent" value="<?php echo encrypt_url($atasan->id,'jabatan_id') ?>">
       <?php } ?>

       <div class="form-group row jabatan_tingkat">
        <label class="col-form-label col-lg-2">Jabatan Atasan <span class="text-danger">*</span></label>
        <div class="col-lg-10">
          <div class="form-group">
           <select class="form-control select-search" name="parent" >  
                <option disabled="">Pilih Jabatan Atasan</option> 
                <?php foreach ($jabatan_tertinggi as $row) { ?>
                <option value="<?php echo $row->id ?>"><?php echo $row->nama_jabatan ?></option> 
                <?php } ?>
          </select> 
        </div>
      </div>
    </div>

      <div class="form-group row">
        <label class="col-form-label col-lg-2">Jabatan Jenis <span class="text-danger">*</span></label>
        <div class="col-lg-10">
          <div class="form-group">
           <select class="form-control select-search" name="jenis_jabatan" >  
                <option disabled="">Pilih Ketegori</option> 
                <option value="1">Struktural</option> 
                <?php if ($this->input->get('at')) { ?>
                <option value="2">Fungsional</option> 
              <?php } ?>
          </select> 
        </div>
      </div>
    </div>
    <div class="form-group row jabatan_tingkat">
        <label class="col-form-label col-lg-2">Jabatan Tingkat <span class="text-danger">*</span></label>
        <div class="col-lg-10">
          <div class="form-group">
           <select class="form-control select-search" name="jabatan_tingkat" >  
                <option disabled="">Pilih Ketegori</option> 
                <?php foreach ($tingkat as $row) { ?>
                <option value="<?php echo $row->id ?>"><?php echo $row->jabatan_tingkat ?></option> 
                <?php } ?>
          </select> 
        </div>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-form-label col-lg-2">Nama Jabatan<span class="text-danger">*</span></label>
      <div class="col-lg-10">
        <div class="form-group-feedback form-group-feedback-left">
          <div class="form-control-feedback">
            <i class="icon-pencil3"></i>
          </div>
          <input type="text" class="form-control" name="nama_jabatan" placeholder="Isi nama jabatan disini">
        </div>
      </div>
    </div>
    
    <input type="hidden" name="mod" value="add">
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

$('[name="jenis_jabatan"]').change(function() {
     if ($(this).val()=='2') {
          $('.jabatan_tingkat').hide();
     }else {
           $('.jabatan_tingkat').show();
     }
})

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