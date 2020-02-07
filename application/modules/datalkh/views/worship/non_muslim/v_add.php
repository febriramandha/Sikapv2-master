<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Tambah Laporan Ibadah</h5>
		<div class="header-elements">
			<div class="list-icons">
        <a class="list-icons-item" data-action="collapse"></a>
      </div>
    </div>
  </div>
  <?php 
    $date_now = date('Y-m-d');
  ?>
  <div class="card-body">
    <?php echo form_open('datalkh/worship/AjaxSaveNonMuslim','class="form-horizontal" id="formAjax"'); ?>
      <div class="col-lg-12">
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Tanggal kegiatan <span class="text-danger">*</span></label>
            <div class="col-lg-6">
              <div class="form-group">
                <select class="form-control select-nosearch" name="tgl" data-fouc> 
                        <?php for ($i=0; $i < 8; $i++) {
                                  $tgl_minus = tgl_minus($date_now, $i);
                            ?>
                              <option value="<?php echo encrypt_url($tgl_minus,"tanggal_lkh_add_$date_now") ?>"><?php echo tgl_ind_hari($tgl_minus) ?></option>
                        <?php } ?>
                </select>
                <span class="text-danger"><i>* pilih tanggal yang tersedia</i></span>
                 <?php if (!$tanggal_lkh) { ?>
                  <div class="alert alert-warning border-0 alert-dismissible mb-0">
                    <span class="font-weight-semibold">Peringatan!</span> Jadwal anda belum ada mohon hubungi admin tentang jadwal anda.
                  </div>
                  <?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Kegiatan <span class="text-danger">*</span></label>
              <div class="col-md-8">
                  <div class="form-group">
                      <textarea name="kegiatan"  rows="5" cols="5" class="form-control" placeholder="Isi uraian kegiatan disini"></textarea>
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-lg-2 col-form-label">Tempat <span class="text-danger">*</span></label>
              <div class="col-lg-8">
                <input class="form-control" name="tempat" placeholder="Isi Tempat" type="text">
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

        <input type="hidden" name="mod" value="add">
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

$(document).ready(function(){
    load_data($('[name="tgl"]').val());

});

$('[name="tgl"]').change(function() {
    da = $(this).val();
    load_data(da)
})

function load_data(id) {
    $.ajax({
        type: 'get',
        url: uri_dasar+"datalkh/worship/AjaxGet",
        data: {mod:"ibadah_nonmuslim",tgl_id:id},
        dataType : "JSON",
        error:function(){
           bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        success: function(res) {
            if (res.status == true) {
               if (res.data) {
                       $('[name="kegiatan"]').val(res.data.kegiatan);
                       $('[name="tempat"]').val(res.data.tempat);
               }else {
                    $('[name="kegiatan"]').val('');
                    $('[name="tempat"]').val('');
               }
               
            }
        }
    });
}

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
            bx_alert_success(res.message, 'datalkh/worship');
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