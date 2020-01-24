<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Tambah Laporan</h5>
		<div class="header-elements">
			<div class="list-icons">
        <a class="list-icons-item" data-action="collapse"></a>
      </div>
    </div>
  </div>

<?php 
$jam_mulai = date('H:i');
if ($tglshow) {
      // tanggal awal
      if ($tglshow->shiftuserrun_id) {
          $data_tgl_lkh = array();
          for ($i=0; $i < $jumlkh->count_inday; $i++) { 
                $data_tgl_lkh[] = tgl_minus(date('Y-m-d'), $i);
          }
      }else {
         $data_tgl_lkh = tgl_minus_lkh(date('Y-m-d'), $jumlkh->count_inday, $tglshow->hari_kerja);
        
      }
  }

 ?>
  <div class="card-body">
    <?php if ($jumlkh) { ?>
    <div class="alert alert-primary alert-dismissible">
      <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
      <span class="font-weight-semibold">Info!</span> <?php echo $jumlkh->ket  ?>.
    </div>
    <?php }else { ?>
    <div class="alert alert-primary alert-dismissible">
      <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
      <span class="font-weight-semibold">Anda tidak berhak mengisi form LKH.
    </div>
    <?php }?>
    <?php echo form_open('datalkh/worship/AjaxSaveNonMuslim','class="form-horizontal" id="formAjax"'); ?>
      <div class="col-lg-12">
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Tanggal kegiatan <span class="text-danger">*</span></label>
            <div class="col-lg-6">
              <div class="form-group">
                <select class="form-control select-nosearch" name="tgl" data-fouc>
                        <?php $no=1; foreach ($data_tgl_lkh as $r_value) { ?>
                        <option value="<?php echo $no++ ?>"><?php echo tgl_ind_hari($r_value) ?></option>
                        <?php } ?>  
                </select>
                <span class="text-danger"><i>* pilih tanggal yang tersedia</i></span>
                 <?php if (!$tglshow) { ?>
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
    load_data(1)

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