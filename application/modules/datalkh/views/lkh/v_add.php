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
          //cek hari kerja
          $for_hari = json_decode($tglshow->hari_kerja, true);

          $masuk_kerja = array('0' => '');
          $i = 0;
          foreach ($for_hari as $v) {
              $masuk_kerja[$for_hari[$i]['f1']] = jm($for_hari[$i]['f2']);

              $i++;
          }
          $jam_mulai = $masuk_kerja[format_tanggal($data_tgl_lkh[0],'N')];

          if ($last_jam) {
               $jam_mulai =  jm($last_jam->jam_selesai);
          }

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
            <label class="col-form-label col-lg-2"> Waktu Kegiatan <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <div class="form-group-feedback form-group-feedback-left">
                <div class="form-control-feedback">
                  <i class="icon-pencil3"></i>
                </div>
                <input type="text" name="jam1" class="form-control clockpicker" placeholder="jam mulai" value="<?php echo $jam_mulai; ?>">
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
                <input type="text" name="jam2" class="form-control clockpicker" placeholder="jam selesai" >
              </div>
            </div>
          </div>
          <div class="form-group row">
              <label class="col-lg-2 col-form-label" >Dinas Luar </label>
              <div class="col-lg-9">
                    <label class="pure-material-checkbox"> 
                        <input type="checkbox" id="checked_dl" name="dl"/> <span>Melaksanakan Dinas Luar </span>
                    </label>
                    <div class="p-1 m-0 alert alert-info border-0 alert-dismissible col-md-5">
                    Silahkan ceklis jika dinas luar
                </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-lg-2 col-form-label">Uraian Kegiatan <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                 <textarea class="ckeditor" id="edit1" name="kegiatan"></textarea>
              </div>
           </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label">Hasil <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                 <textarea class="ckeditor" id="edit2" name="hasil"></textarea>
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
 $('.clockpicker').clockpicker({
    placement: 'bottom',
    align: 'left',
    autoclose: true,
 });

$(document).ready(function(){
    load_jam(1);
});

CKEDITOR.replaceClass = 'ckeditor';
$('.ckeditor').each(function(e){
      CKEDITOR.replace( this.id, {  height:'80px',
              tabSpaces: 4,
              customConfig: uri_dasar+'public/themes/plugin/ckeditor/custom/ckeditor_config.js' });
});

function CKupdate(){
for ( instance in CKEDITOR.instances )
    CKEDITOR.instances[instance].updateElement();
}

$('[name="tgl"]').change(function() {
    load_jam($(this).val());
})

function load_jam(tgl_id) {
    $.ajax({
        type: 'get',
        url: uri_dasar+"datalkh/lkh/AjaxGet",
        data: {mod:"time",tgl_id:tgl_id},
        dataType : "JSON",
        error:function(){
           bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        success: function(res) {
            if (res.status == true) {
               $('[name="jam1"]').val(res.data.jam_masuk);
            }
        }
    });
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