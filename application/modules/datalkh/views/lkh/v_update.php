<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">perbaiki laporan ditolak</h5>
		<div class="header-elements">
			<div class="list-icons">
        <a class="list-icons-item" data-action="collapse"></a>
      </div>
    </div>
  </div>

  <div class="card-body">
    <div class="alert alert-warning alert-dismissible">
        <span class="font-weight-semibold">alasan ditolak!</span> <?php echo $datalkh->comment ?>
    </div>  
    <?php echo form_open('datalkh/lkh/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
      <div class="col-lg-12">
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Tanggal kegiatan <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <div class="form-group">
                <input type="text" name="tgl" class="form-control clockpicker" value="<?php echo tgl_ind_hari($datalkh->tgl_lkh) ?>" readonly>
                <span><i>* tidak dapat merubah tanggal</i></span>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2"> Waktu Kegiatan <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <div class="form-group-feedback form-group-feedback-left">
                <div class="form-control-feedback">
                  <i class="icon-pencil3"></i>
                </div>
                <input type="text" name="jam1" class="form-control clockpicker" placeholder="jam mulai" value="<?php echo jm($datalkh->jam_mulai) ?>" readonly>
                <span><i>* tidak dapat merubah jam mulai</i></span>
              </div>
            </div>
            <div class="col-lg-1">
              <div class="form-group">
                <span class="m-0">s/d</span>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group-feedback form-group-feedback-left">
                <div class="form-control-feedback">
                  <i class="icon-pencil3"></i>
                </div>
                <input type="text" name="jam2" class="form-control clockpicker" placeholder="jam selesai" value="<?php echo jm($datalkh->jam_selesai) ?>" readonly>
                <span><i>* tidak dapat merubah jam selesai</i></span>
              </div>
            </div>
          </div>
          <div class="form-group row">
              <label class="col-lg-2 col-form-label" >Dinas Luar </label>
              <div class="col-lg-9">
                    <label class="pure-material-checkbox"> 
                        <input type="checkbox" id="checked_dl" name="dl" <?php if ($datalkh->jenis == 3) { echo "checked"; } ?> /> <span>Melaksanakan Dinas Luar </span>
                    </label>
                    <div class="p-1 m-0 alert alert-info border-0 alert-dismissible col-md-5">
                    Silahkan ceklis jika dinas luar
                </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-lg-2 col-form-label">Uraian Kegiatan <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                 <textarea class="ckeditor" id="edit1" name="kegiatan"><?php echo $datalkh->kegiatan ?></textarea>
              </div>
           </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label">Hasil <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                 <textarea class="ckeditor" id="edit2" name="hasil"><?php echo $datalkh->kegiatan ?></textarea>
              </div>
           </div>
           <div class="form-group row">
              <label class="col-lg-2 col-form-label">Verifikator <span class="text-danger">*</span></label>
              <div class="col-lg-9">
                <?php if ($verifikator) {
                      echo nama_icon_nip($verifikator->ver_nama, $verifikator->ver_gelar_dpn,$verifikator->ver_gelar_blk, $verifikator->jabatan);
                      if ($verifikator->user_id_ver) {
                            echo '<input type="hidden" name="verifikator" value="'.encrypt_url($verifikator->user_id_ver,'verifikator').'">';
                      }
                }?>
              </div>
           </div>
        <input type="hidden" name="mod" value="update">
        <input type="hidden" name="id" value="<?php echo $this->uri->segment(4) ?>">
        <div class="text-left offset-lg-2" >
           <a href="javascript:history.back()" class="btn btn-sm bg-success-300 result">Kembali <i class="icon-arrow-left5 ml-2"></i></a>                  
          <button type="submit" class="btn btn-sm btn-info result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
          <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
        </div>
    </div>
  <?php echo form_close() ?> 
  </div>
</div>

<script type="text/javascript">

CKEDITOR.replaceClass = 'ckeditor';
$('.ckeditor').each(function(e){
      CKEDITOR.replace( this.id, {  height:'80px',
              tabSpaces: 4,
              customConfig: uri_dasar+'public/themes/plugin/ckeditor/custom/ckeditor_config_text_add.js' });
});

function CKupdate(){
for ( instance in CKEDITOR.instances )
    CKEDITOR.instances[instance].updateElement();
}


  $('#formAjax').submit(function() {
      CKupdate();
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
            bx_alert_successUpadate(res.message, 'datalkh/lkh');
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