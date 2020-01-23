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
    <?php echo form_open('datalkh/lkh/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
      <div class="col-lg-12">
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Tanggal kegiatan <span class="text-danger">*</span></label>
            <div class="col-lg-8">
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
                <label class="col-form-label col-lg-2">Kegiatan</label>
                <div class="col-md-5">

                    <div class="form-group">
                        <div class="form-group">
                            <label class="pure-material-checkbox"> 
                                <input name="zuhur" type="checkbox" /> <span id="text_zuhur">Zhuhur berjamaah</span>
                            </label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><img src="<?php echo base_url('public/images/mesjid2.png'); ?>" width="25px" height="25px"></span>
                            </span>
                            <input name="t_zuhur" class="form-control"  type="text" placeholder="Masukkan nama masjid" value="" >
                            
                        </div>
                        <span class="form-text text-danger" id="et_zuhur"></span>
                        <span class="form-text text-danger" id="ew_zuhur"></span>
                    </div>
                       

                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-lg-2">Kegiatan</label>
                <div class="col-md-5">
                    <div class="form-group">
                            <label class="pure-material-checkbox"> 
                                <input name="ashar" type="checkbox" /> <span>Ashar berjamaah</span>
                            </label>
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><img src="<?php echo base_url('public/images/mesjid2.png'); ?>" width="25px" height="25px"></span>
                            </span>
                            <input name="t_ashar" class="form-control"  type="text" placeholder="Masukkan nama masjid" value="" >
                           
                        </div>
                         <span class="form-text text-danger" id="et_ashar"></span>
                         <span class="form-text text-danger" id="ew_ashar"></span>
                    </div>
                </div>
            </div>
            <?php if ($user->gender==1): ?>
            <div class="form-group row">
                <label class="col-form-label col-lg-2" >Lainnya</label>
                <div class="col-md-5">
                    <label class="pure-material-checkbox"> 
                            <input name="pms" type="checkbox" /> <span>Haid</span>
                     </label>
                </div>
            </div>
            <?php endif ?>
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

// $('[name="tgl"]').change(function() {
//     $.ajax({
//         type: 'get',
//         url: uri_dasar+"datalkh/lkh/AjaxGet",
//         data: {mod:"time",tgl_id:$(this).val()},
//         dataType : "JSON",
//         error:function(){
//            bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
//         },
//         success: function(res) {
//             if (res.status == true) {
//                $('[name="jam1"]').val(res.data.jam_masuk);
//             }
//         }
//     });
// })

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
            bx_alert_success(res.message, 'datalkh/lkh');
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