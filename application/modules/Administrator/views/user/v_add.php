<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Tambah Pengguna</h5>
		<div class="header-elements">
			<div class="list-icons">
        <a class="list-icons-item" data-action="collapse"></a>
      </div>
    </div>
  </div>

  <div class="card-body">

    <?php echo form_open('administrator/user/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
    <div class="col-lg-12">
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Nama Pengguna <span class="text-danger">*</span></label>
        <div class="col-lg-10">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="text" name="username" class="form-control trim" placeholder="isi nama pengguna" autocomplete="off" >
            <!--  <span><i>* isi nama pengguna tanpa spasi</i></span> -->
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Kata Sandi <span class="text-danger">*</span></label>
        <div class="col-lg-10">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="password" name="password_confirmation" class="form-control trim" placeholder="isi kata sandi" autocomplete="new-password"/>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Ulangi Kata Sandi <span class="text-danger">*</span></label>
        <div class="col-lg-10">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="password" name="password" class="form-control trim" placeholder="isi ulangi kata sandi" autocomplete="off"/>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Ketegori Pengguna <span class="text-danger">*</span></label>
        <div class="col-lg-10">
          <div class="form-group">
           <select class="form-control select-nosearch" name="ketegori" >  
            <option disabled="">Pilih Ketegori</option> 
            <option value="1">PNS/CPNS</option>
            <option value="2">NON PNS</option>
          </select> 
        </div>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-lg-2">Nama Lengkap <span class="text-danger">*</span></label>
      <div class="col-lg-10">
        <div class="form-group-feedback form-group-feedback-left">
          <div class="form-control-feedback">
            <i class="icon-pencil3"></i>
          </div>
          <input type="text" name="nama" class="form-control" placeholder="isi nama lengkap">
          <span><i>* isi tanpa nama gelar jika pns/cpns</i></span>
        </div>
      </div>
    </div>
    <div class="form-group row" id="nip">
      <label class="col-form-label col-lg-2">NIP <span class="text-danger">*</span></label>
      <div class="col-lg-10">
        <div class="form-group-feedback form-group-feedback-left">
          <div class="form-control-feedback">
            <i class="icon-pencil3"></i>
          </div>
          <input type="text" name="nip" class="form-control" placeholder="isi NIP"  pattern="[0-9]{18,18}" title="18 karakter dan harus angka">
        </div>
      </div>
    </div>
    <div class="form-group row" id="tpp">
      <label class="col-form-label col-lg-2">Terima TPP <span class="text-danger">*</span></label>
      <div class="col-lg-10">
        <div class="input-group">
          <span class="input-group-prepend">
            <span class="input-group-text">
              <input type="checkbox" name="tpp" class="form-control-switchery" data-fouc>
            </span>
          </span>
        </div>
        <span><i>* aktifkan bagi penerima TPP</i></span>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-lg-2">Instansi <span class="text-danger">*</span></label>
      <div class="col-lg-10">
        <div class="form-group">
         <select class="form-control select-search" name="instansi" >  
          <option disabled="">Pilih Instansi</option> 
          <?php foreach ($instansi as $row) { ?>
            <option value="<?php echo encrypt_url($row->id,'instansi') ?>"><?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?></option>
          <?php } ?>
        </select> 
      </div>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-form-label col-lg-2">Jenis Pengguna <span class="text-danger">*</span></label>
    <div class="col-lg-10">
      <div class="form-group">
       <select class="form-control select-nosearch" name="level" >  
        <option disabled="">Pilih Jenis Pengguna</option> 
        <option value="<?php echo encrypt_url(1,'level') ?>">Super Administrator</option>
        <option value="<?php echo encrypt_url(2,'level') ?>">Admin Instansi</option>
        <option value="<?php echo encrypt_url(5,'level') ?>">Pimpinan</option>
        <option value="<?php echo encrypt_url(3,'level') ?>" selected>Pegawai</option>
        <option value="<?php echo encrypt_url(4,'level') ?>">Admin Monitoring</option>
      </select> 
    </div>
  </div>
</div>

<div class="form-group row">
  <label class="col-form-label col-lg-2">Status Absen Finger</label>
  <div class="col-lg-10">
    <div class="input-group">
      <span class="input-group-prepend">
        <span class="input-group-text">
          <input type="checkbox" name="status_att" class="form-control-switchery" checked data-fouc>
        </span>
      </span>
    </div>
    <span><i>* aktifkan untuk mendaftarkan pada mesin sidik jari</i></span>
  </div>
</div>

<div class="form-group row">
  <label class="col-form-label col-lg-2">Status Akun</label>
  <div class="col-lg-10">
    <div class="input-group">
      <span class="input-group-prepend">
        <span class="input-group-text">
          <input type="checkbox" name="status_akun" class="form-control-switchery" checked data-fouc> 
        </span>
      </span>
    </div>
    <span><i>* aktifkan untuk masuk sebagai pengguna</i></span>
  </div>
</div>
<input type="hidden" name="mod" value="add">
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

  $('[name="ketegori"]').change(function(){ 
    if ($(this).val() == 2) {
      $('#nip').hide(1000);
      $('#tpp').hide(1000);
    }else {
      $('#nip').show("slow");
      $('#tpp').show("slow");
    }
  });

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
        bx_alert_success(res.message, 'administrator/user');
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