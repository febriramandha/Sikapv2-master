<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Edit Pengguna</h5>
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
                      <input type="text" name="username" class="form-control trim" placeholder="isi nama pengguna" autocomplete="off" value="<?php echo $user->username ?>">
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Kata Sandi</label>
              <div class="col-lg-10">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="password" name="password_confirmation" class="form-control trim" placeholder="isi kata sandi" autocomplete="new-password"/>
                       <span><i>* kosongkan jika tidak ingin mengganti kata sandi</i></span>
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Ulangi Kata Sandi </label>
              <div class="col-lg-10">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="password" name="password" class="form-control trim" placeholder="isi ulangi kata sandi" autocomplete="off"/>
                      <span><i>* kosongkan jika tidak ingin mengganti kata sandi</i></span>
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Ketegori Pengguna <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group">
                       <select class="form-control select-nosearch" name="ketegori" disabled="true">  
                              <option disabled="">Pilih Ketegori</option> 
                              <option value="1" <?php if ($user->pns == 1) { echo "selected";} ?>>PNS</option>
                              <option value="2" <?php if ($user->pns == 2) { echo "selected";} ?>>NON PNS</option>
                      </select> 
                      <input type="hidden" name="ketegori" value="<?php echo $user->pns ?>"/>
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
                      <input type="text" name="nama" class="form-control" placeholder="isi nama lengkap" value="<?php echo $user->nama ?>">
                      <span><i>* isi tanpa nama gelar jika pns/cpns</i></span>
                  </div>
              </div>
          </div>
          <?php if ($user->pns == 1) { ?>
          <div class="form-group row">
              <label class="col-form-label col-lg-2">NIP <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="nip" class="form-control" placeholder="isi NIP"  pattern="[0-9]{18,18}" title="18 karakter dan harus angka" value="<?php echo $user->nip ?>" disabled>
                      <input type="hidden" name="nip" value="<?php echo $user->nip ?>"/>
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Terima TPP <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="input-group">
                    <span class="input-group-prepend">
                      <span class="input-group-text">
                        <input type="checkbox" name="tpp" class="form-control-switchery" <?php if ($user->tpp == 1) { echo "checked";} ?> data-fouc>
                      </span>
                    </span>
                  </div>
                  <span><i>* aktifkan bagi penerima TPP</i></span>
            </div>
          </div>
          <?php } ?>
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Instansi <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group">
                       <?php 
                        foreach ($instansi as $row) {
                          $datacat[encrypt_url($row->id,'instansi')] = '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name); 
                          }
                          echo form_dropdown('instansi', $datacat, encrypt_url($user->dept_id,'instansi'),'class="form-control select-search"');
                        ?>
                  </div>
              </div>
          </div>

          <div class="form-group row">
              <label class="col-form-label col-lg-2">Jenis Pengguna <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group">
                       <select class="form-control select-nosearch" name="level" >  
                              <option disabled="">Pilih Jenis Pengguna</option> 
                              <option value="<?php echo encrypt_url(1,'level') ?>" <?php if ($user->level == 1) { echo "selected";} ?>>Super Administrator</option>
                              <option value="<?php echo encrypt_url(2,'level') ?>" <?php if ($user->level == 2) { echo "selected";} ?>>Admin Instansi</option>
                              <option value="<?php echo encrypt_url(5,'level') ?>" <?php if ($user->level == 5) { echo "selected";} ?>>Pimpinan</option>
                              <option value="<?php echo encrypt_url(3,'level') ?>" <?php if ($user->level == 3) { echo "selected";} ?>>Pegawai</option>
                              <option value="<?php echo encrypt_url(4,'level') ?>" <?php if ($user->level == 4) { echo "selected";} ?>>User Eksekutif</option>
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
                    <input type="checkbox" name="status_att" class="form-control-switchery" <?php if ($user->att_status == 1) { echo "checked";} ?> data-fouc>
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
                    <input type="checkbox" name="status_akun" class="form-control-switchery" <?php if ($user->status == 1) { echo "checked";} ?> data-fouc> 
                  </span>
                </span>
              </div>
              <span><i>* aktifkan untuk masuk sebagai pengguna</i></span>
            </div>
          </div>
          <input type="hidden" name="mod" value="edit">
          <input type="hidden" name="user_id" value="<?php echo encrypt_url($user->user_id,'user_id') ?>">
          <input type="hidden" name="login_id" value="<?php echo encrypt_url($user->login_id,'login_id') ?>">
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
                bx_alert_successUpadate(res.message, 'administrator/user');
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