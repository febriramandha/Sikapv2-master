<div class="card">
    <div class="card-header bg-white header-elements-inline">
      <h6 class="card-title">Tambah Pengguna</h6>
      <div class="header-elements">
        <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
          </div>
        </div>
    </div>
    <?php echo form_open('administrator/user/AjaxSave','class="wizard-form steps-basic" id="formAjax" data-fouc'); ?>
      <h6>Data Akun</h6>
      <fieldset>
        <div class="form-group row">
          <label class="col-form-label col-lg-2">Nama Pengguna <span class="text-danger">*</span></label>
          <div class="col-lg-10">
            <div class="form-group-feedback form-group-feedback-left">
              <div class="form-control-feedback">
                <i class="icon-pencil3"></i>
              </div>
              <input type="text" name="username" class="form-control trim" placeholder="nama pengguna" autocomplete="off" >
               <span class="text-danger"><i>* isi nama pengguna tanpa spasi</i></span>
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
              <input type="password" name="password_confirmation" class="form-control trim" placeholder="kata sandi" autocomplete="new-password"/>
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
              <input type="password" name="password" class="form-control trim" placeholder="ulangi kata sandi" autocomplete="off"/>
            </div>
          </div>
        </div>
      </fieldset>

      <h6>Biodata Pegawai</h6>
      <fieldset>
        <div class="form-group row">
          <label class="col-form-label col-lg-2">Ketegori Pengguna <span class="text-danger">*</span></label>
          <div class="col-lg-10">
            <div class="form-group">
             <select class="form-control select-nosearch" name="ketegori" >  
              <option disabled="">Pilih Ketegori</option> 
              <option value="1">PNS</option>
              <option value="2">NON PNS</option>
            </select> 
            <span class="text-danger"><i>* pilih PNS jika memiliki NIP</i></span>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Nama Lengkap <span class="text-danger">*</span></label>
        <div class="col-lg-4">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="text" name="nama" class="form-control" placeholder="nama lengkap">
          </div>
        </div>
        <div class="col-lg-3">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="text" name="gelar_dpn" class="form-control" placeholder="gelar depan">
          </div>
        </div>
        <div class="col-lg-3">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="text" name="gelar_blk" class="form-control" placeholder="gelar belakang">
          </div>
        </div>
      </div>
      <div class="form-group row nip_pegawai" id="nip">
        <label class="col-form-label col-lg-2">NIP <span class="text-danger">*</span></label>
        <div class="col-lg-10">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="text" name="nip" class="form-control" placeholder="NIP"  pattern="[0-9]{18,18}" title="18 karakter dan harus angka">
          </div>
        </div>
      </div>

      <div class="form-group row nip_pegawai">
          <label class="col-form-label col-lg-2">Eselon <span class="text-danger">*</span></label>
          <div class="col-lg-10">
            <div class="form-group">
             <select class="form-control select-nosearch" name="eselon" >  
              <option value="">Pilih Eselon</option> 
              <?php foreach ($eselon as $row) {?>    
                  <option value="<?php echo $row->id ?>"><?php echo $row->eselon ?></option>
              <?php } ?>
            </select> 
          </div>
        </div>
      </div>

       <div class="form-group row nip_pegawai">
          <label class="col-form-label col-lg-2">Pangkat/Golongan <span class="text-danger">*</span></label>
          <div class="col-lg-10">
            <div class="form-group">
             <select class="form-control select-nosearch" name="golongan" >  
               <option value="">Pilih Pangkat/Golongan</option> 
              <?php foreach ($golongan as $row) {?>    
                  <option value="<?php echo $row->id ?>"><?php echo $row->pangkat ?>(<?php echo $row->golongan ?>)</option>
              <?php } ?>
            </select> 
          </div>
        </div>
      </div>
      <div class="form-group row nip_pegawai" id="tpp">
        <label class="col-form-label col-lg-2">Terima TPP <span class="text-danger">*</span></label>
        <div class="col-lg-10">
          <div class="input-group">
            <span class="input-group-prepend">
              <span class="input-group-text">
                <input type="checkbox" name="tpp" class="form-control-switchery" data-fouc>
              </span>
            </span>
          </div>
          <span class="text-danger"><i>* aktifkan bagi penerima TPP</i></span>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Jabatan <span class="text-danger">*</span></label>
        <div class="col-lg-10">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="text" name="jabatan" class="form-control" placeholder="jabatan" >
             <span class="text-danger"><i>* data jabatan diperbarui dengan aplikasi SIMPEG (beri tanda (-) jika tidak ada) </i></span>
          </div>
        </div>
      </div>

      <div class="form-group row">
          <label class="col-form-label col-lg-2">Status Pegawai <span class="text-danger">*</span></label>
          <div class="col-lg-10">
            <div class="form-group">
             <select class="form-control select-nosearch" name="status_pegawai" >  
              <option value="">Pilih Status Pegawai</option> 
              <?php foreach ($status_peg as $row) {?>    
                  <option value="<?php echo $row->id ?>"><?php echo $row->nama ?></option>
              <?php } ?>
            </select> 
          </div>
        </div>
      </div>
      
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Unit Kerja <span class="text-danger">*</span></label>
        <div class="col-lg-10">
          <div class="form-group">
           <select class="form-control select-search" name="instansi" >  
            <option disabled="">Pilih Unit Kerja</option> 
            <?php foreach ($instansi as $row) { ?>
              <option value="<?php echo encrypt_url($row->id,'instansi') ?>"><?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?></option>
            <?php } ?>
          </select> 
        </div>
      </div>
    </div>



     <div class="form-group row">
            <label class="col-form-label col-lg-2">Jenis Kelamin <span class="text-danger">*</span></label>
          <div class="col-lg-10">
              <div class="form-group">
                   <select class="form-control select-nosearch" name="gender">  
                          <option disabled="">Pilih Jenis Kelamin</option> 
                          <option value="1" >Laki-Laki</option>
                          <option value="2" >Perempuan</option>
                  </select> 
              </div>
          </div>
      </div>
      <div class="form-group row">
            <label class="col-form-label col-lg-2">Agama <span class="text-danger">*</span></label>
          <div class="col-lg-10">
              <div class="form-group">
                   <select class="form-control select-nosearch" name="agama">  
                          <?php foreach ($agama as $row) {?>    
                            <option value="<?php echo $row->id ?>"><?php echo $row->agama ?></option>
                        <?php } ?>
                  </select> 
              </div>
          </div>
      </div>
  </fieldset>

  <h6>Kewanangan</h6>
  <fieldset>
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
          <option value="<?php echo encrypt_url(4,'level') ?>">User Eksekutif</option>
        </select> 
      </div>
    </div>
  </div>
</fieldset>

<h6>Status</h6>
<fieldset>
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

 <!--  <div class="form-group row">
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
  </div> -->
  <input type="hidden" name="mod" value="add">
</fieldset>
<?php echo form_close() ?> 
</div>


<script type="text/javascript">

  // Basic wizard setup
  $('.steps-basic').steps({
      headerTag: 'h6',
      bodyTag: 'fieldset',
      transitionEffect: 'fade',
      titleTemplate: '<span class="number">#index#</span> #title#',
      labels: {
          previous: '<i class="icon-arrow-left13 mr-2" /> Sebelumnya',
          next: 'Lanjut <i class="icon-arrow-right14 ml-2" />',
          finish: 'Simpan <i class="icon-arrow-right14 ml-2 result" /> <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i> '
      },
      onFinished: function (event, currentIndex) {
          $("#formAjax").submit(); 
      }
  });

  $('[name="ketegori"]').change(function(){ 
    if ($(this).val() == 2) {
      $('.nip_pegawai').hide(1000);
    }else {
      $('.nip_pegawai').show("slow");
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