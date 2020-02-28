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
    $date_now = date('Y-m-d');
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
    <?php echo form_open('datalkh/worship/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
      <div class="col-lg-12">
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Tanggal kegiatan <span class="text-danger">*</span></label>
            <div class="col-lg-6">
              <div class="form-group">
                <select class="form-control select-nosearch" name="tgl" data-fouc>
                        <?php $no=1; foreach ($tanggal_lkh as $row) { ?>
                        <option value="<?php echo encrypt_url($row->rentan_tanggal,"tanggal_lkh_add_$date_now") ?>"><?php echo tgl_ind_hari($row->rentan_tanggal) ?></option>
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
                <label class="col-form-label col-lg-2">Kegiatan</label>
                <div class="col-md-5">

                    <div class="form-group">
                        <div class="form-group">
                            <label class="pure-material-checkbox"> 
                                <input name="zuhur" type="checkbox" /> <span id="text_zuhur">Zuhur berjamaah</span>
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
            <?php if ($user->gender==2): ?>
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
var w_zuhur = "12:30:00";
var w_asar  = "15:50:00";
var jam_ini = "<?= date('H:i:s') ?>";
var gender = "<?= $user->gender ?>";
var hari = "<?= date('D') ?>";
var tgl_ini = "<?= date('Y-m-d') ?>";

 $(document).ready(function(){
    jumat(hari, gender);
    load_data($('[name="tgl"]').val());

});

$('[name="tgl"]').change(function() {
    da = $(this).val();
    // cek_waktu(jam_ini, w_zuhur, da, hari, gender);
    jumat(hari, gender);

    load_data(da)
})

function load_data(id) {
    $.ajax({
        type: 'get',
        url: uri_dasar+"datalkh/worship/AjaxGet",
        data: {mod:"time",tgl_id:id},
        dataType : "JSON",
        error:function(){
           bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        success: function(res) {
            if (res.status == true) {
               // $('[name="jam1"]').val(res.data.jam_masuk);
               if (res.data.data_ibadah) {
                     var a = res.data.data_ibadah;
                    $('[name="zuhur"]').prop('checked', a.cek_zuhur);
                    $('[name="ashar"]').prop('checked', a.cek_ashar);
                    $('[name="t_zuhur"]').val(a.t_zuhur);
                    $('[name="t_ashar"]').val(a.t_ashar);
                    $('[name="pms"]').prop('checked', a.cek_pms);
                    $('[name="dl"]').prop('checked', a.cek_dl);
                    jumat(a.hari, gender);
                    cek_waktu(jam_ini, w_zuhur,a.tgl_ibadah, hari, gender);
                   
               }
               
            }
        }
    });
}

function jumat(hari, gender) {
    if (hari == "Fri" && gender == 1) {
        $('#text_zuhur').text("Sholat Jumat");
    }else $('#text_zuhur').text("Zuhur berjamaah");    
}

function cek_waktu(jam_ini, waktu, da, hari, gender) {

        zuhur_dis = false;
        ashar_dis = false;
        ew_zuhur = '';
        ew_ashar = '';

        if (jam_ini < w_zuhur && da == tgl_ini ) {
            zuhur_dis = true;

            ew_zuhur = "Waktu sholat zuhur belum masuk";
            if (hari == "Fri" && gender == 1) {
                ew_zuhur = "Waktu sholat jumat belum masuk";
            }

        }

        if (jam_ini < w_asar && da == tgl_ini ) {
            ashar_dis = true;
            ew_ashar = "Waktu sholat Ashar belum masuk";
        }

         $('[name="zuhur"]').prop("disabled", zuhur_dis);
         $('[name="t_zuhur"]').prop("disabled", zuhur_dis);
         $('[name="ashar"]').prop("disabled", ashar_dis);
         $('[name="t_ashar"]').prop("disabled", ashar_dis);
         $("#ew_zuhur").html(ew_zuhur);
         $("#ew_ashar").html(ew_ashar);
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