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
$date_now = date('Y-m-d');

?>
  <div class="card-body">
    <?php if ($jumlkh) { ?>
    <div class="alert alert-warning alert-dismissible p-2">
      <?php echo $jumlkh->ket  ?>.
    </div>
    <?php }else { ?>
    <div class="alert alert-warning alert-dismissible p-2">
      <span class="font-weight-semibold">Anda tidak berhak mengisi form LKH.
    </div>
    <?php }?>
    <?php echo form_open('datalkh/lkh/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
      <div class="col-lg-12">
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Tanggal kegiatan <span class="text-danger">*</span></label>
            <div class="col-lg-5">
              <div class="form-group">
                <select class="form-control select-icons" name="tgl" data-fouc>
                        <?php $no=1; foreach ($tanggal_lkh as $v) { ?>
                        <option value="<?php echo encrypt_url($v,"tanggal_lkh_add_$date_now") ?>" data-icon="calendar3"><?php echo tgl_ind_hari($v) ?></option>
                        <?php } ?>  
                </select>
                <div class="p-1 mt-1 mb-0 alert alert-info border-0 alert-dismissible col-lg-8 col-12">
                    pilih tanggal yang tersedia
                </div>
                 <?php if (empty($tanggal_lkh)) { ?>
                  <div class="alert alert-warning border-0 alert-dismissible mb-0">
                    <span class="font-weight-semibold">Peringatan!</span> Jadwal anda belum tersedia mohon hubungi administrator tentang jadwal anda.
                  </div>
                  <?php } ?>
              </div>
            </div>
            <div class="col-lg-5 pt-1">
              <div class="form-group">
                <div class="p-1 m-0 alert alert-success border-0 alert-dismissible">
                    Total jam kerja: <span id="total_jam"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2"> Waktu Kegiatan <span class="text-danger">*</span> 
                <i class="icon-spinner2 spinner" style="display: none" id="spinner_waktu"></i>
            </label>
            <div class="col-lg-2">
              <div class="form-group-feedback form-group-feedback-left">
                <div class="form-control-feedback">
                  <i class="icon-watch2"></i>
                </div>
                <input type="text" name="jam1" class="form-control result" placeholder="jam mulai" readonly="">
              </div>
            </div>
            <div class="col-lg-1 mt-1 mt-lg-0 align-self-center">
              <span class="badge bg-teal">s/d</span>
            </div>
            <div class="col-lg-2">
              <div class="form-group-feedback form-group-feedback-left">
                <div class="form-control-feedback">
                  <i class="icon-pencil3"></i>
                </div>
                <input type="text" name="jam2" class="form-control clockpicker readonlyjm" placeholder="jam selesai" autocomplete="off">
              </div>
            </div>
             <div class="col-lg-5 pt-2">
              <div class="form-group">
                <div class="p-1 m-0 alert alert-warning border-0 alert-dismissible">
                    Jumlah jam yang harus diisi: <span id="total_jam_reg"></span>
                </div>
                <div class="progress mt-1">
                  <div class="progress-bar bg-teal" id="progress-bar" style="width: 100%">
                    <span id='percent'>0% Complete</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
              <label class="col-lg-2 col-form-label" >Dinas Luar </label>
              <div class="col-lg-9">
                    <label class="pure-material-checkbox"> 
                        <input type="checkbox" id="checked_dl" name="dl"/> <span>Melaksanakan Dinas Luar </span>
                    </label>
                    <div class="p-1 m-0 alert alert-info border-0 alert-dismissible col-lg-4 col-12">
                    Silahkan ceklis jika dinas luar
                    </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-lg-2 col-form-label">Uraian Kegiatan <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                 <textarea class="ckeditor_text" id="kegiatan" name="kegiatan"></textarea>
              </div>
           </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label">Hasil <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                 <textarea class="ckeditor_text" id="hasil" name="hasil"></textarea>
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
        <input type="hidden" name="jam1_encry">
        <input type="hidden" name="total_jam_encry">
        <input type="hidden" name="jam_pulang_encry">
        <input type="hidden" name="mod" value="add">
        <div class="text-left offset-lg-2" >
           <a href="<?php echo base_url('datalkh/lkh') ?>" class="btn btn-sm bg-warning legitRipple"><i class="icon-undo2"></i> Kembali</a>                  
          <button type="submit" class="btn btn-sm btn-info result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
          <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
        </div>
    </div>
  <?php echo form_close() ?> 
  </div>
</div>

<?php
  $verifikasi = '';
  if ($jumlah_nonver) {
  $verifikasi = 1;
 ?>
<!-- Large modal -->
<div id="modal_large" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <input id="tabel2"  type="hidden" value="1" readonly>
      <div class="modal-body p-0" id="load-body-modal">
    
      </div>
      <div class="alert alert-warning alert-dismissible mb-0 p-2">
        <span class="font-weight-semibold">Perhatian!</span> Pengisian LKH paling lambat <span class="badge bg-danger ml-1">23.59 WIB</span> pada hari berikunya
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<!-- /large modal -->
<?php } ?>
<input type="hidden" name="cekverifikasi" value="<?php echo $verifikasi ?>">
<script type="text/javascript">

$('.ckeditor_text').each(function(e){
      CKEDITOR.replace( this.id, {  
              height:'100px',
              tabSpaces: 4,
              customConfig: uri_dasar+'public/themes/plugin/ckeditor/custom/ckeditor_config_text_add_fix.js' });
});

function CKupdate(){
  for ( instance in CKEDITOR.instances )
      CKEDITOR.instances[instance].updateElement();
}

// Format icon
function iconFormat(icon) {
    var originalOption = icon.element;
    if (!icon.id) { return icon.text; }
    var $icon = '<i class="icon-' + $(icon.element).data('icon') + '"></i>' + icon.text;

    return $icon;
}

// Initialize with options
$('.select-icons').select2({
    templateResult: iconFormat,
    minimumResultsForSearch: Infinity,
    templateSelection: iconFormat,
    placeholder: 'Pilih Data',
    allowClear: true,
    escapeMarkup: function(m) { return m; }
});

 $('.clockpicker').clockpicker({
    placement: 'bottom',
    align: 'left',
    autoclose: true,
 });

$('.readonlyjm').on('focus',function(){
    $(this).trigger('blur');
});

$(document).ready(function(){
    load_jam($('[name="tgl"]').val());
    var verifikasi = $('[name="cekverifikasi"]').val();
    if (verifikasi == 1) {
        load_verifikasi()
    }  
});

$('[name="tgl"]').change(function() {
    load_jam($(this).val());
})

function load_jam(tgl_id) {
    var result  = $('.result');
    var spinner = $('#spinner_waktu');
    $.ajax({
        type: 'get',
        url: uri_dasar+"datalkh/lkh/AjaxGet",
        data: {mod:"time",tgl_id:tgl_id},
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
                $('[name="jam1"]').val(res.data.jam_mulai);
                $('[name="jam1_encry"]').val(res.data.jam_mulai_encry);
                $('[name="total_jam_encry"]').val(res.data.total_jam_encry);
                $('[name="jam_pulang_encry"]').val(res.data.jam_pulang_encry);
                $('#total_jam').text(res.data.total_jam);
                $('#total_jam_reg').text(res.data.total_jam_reg);
                
                $('#progress-bar').animate({width: res.data.persen+"%"}, 100);
                $('#percent').text(res.data.persen+"%");
               
            }
            result.attr("disabled", false);
            spinner.hide();
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

function load_verifikasi() {
   $.ajax({
          url: uri_dasar+'datalkh/verifikasi/index',
          dataType :"html",
          data:{ modul:'cek'},
          cache : true,
              success: function(x){
                    $('#modal_large').modal('show');
                      $('#load-body-modal').html(x);
              }
        });
}
</script>