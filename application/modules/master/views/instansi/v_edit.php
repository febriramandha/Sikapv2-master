<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Edit Unit Kerja</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">

		<?php echo form_open('master/instansi/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Nama Unit Kerja <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" class="form-control" name="nama" placeholder="Isi nama instansi disini" value="<?php echo $instansi->dept_name ?>">
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Nama Singkat <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" class="form-control" name="alias" placeholder="Isi nama singkat disini" value="<?php echo $instansi->dept_alias ?>">
                  </div>
              </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Kategori Unit Kerja <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <div class="form-group">
               <select class="form-control select-search" name="kategori" >  
                    <option disabled="">Pilih Ketegori</option> 
                    <option value="nonunit" <?php if($instansi->dept_type == "nonunit") { echo "selected";} ?>>Non Unit Kerja</option> 
                    <option value="opd" <?php if($instansi->dept_type == "opd") { echo "selected";} ?>>OPD</option> 
                    <option value="subopd" <?php if($instansi->dept_type == "subopd") { echo "selected";} ?>>Sub OPD</option> 
                    <option value="puskesmas" <?php if($instansi->dept_type == "puskesmas") { echo "selected";} ?>>Puskesmas</option> 
                    <option value="sekolah" <?php if($instansi->dept_type == "sekolah") { echo "selected";} ?>>Sekolah</option> 
              </select> 
            </div>
          </div>
        </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Kecamatan <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group">
                      <?php 
                        foreach ($kecamatan as $row) {
                          $datacat[encrypt_url($row->id,'kecamatan_id')] = $row->nama; 
                          }
                          echo form_dropdown('kecamatan', $datacat, encrypt_url($instansi->kecamatan_id,'kecamatan_id'),'class="form-control select-search"');
                        ?> 
                      <span><i>* pilih kecamatan instansi untuk kebutuhan laporan</i></span>
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Nomor Urut<span class="text-danger">*</span></label>
              <div class="col-lg-2">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="number" class="form-control" name="order" placeholder="nomor urut" value="<?php echo $instansi->position_order ?>">
                  </div>
              </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Status <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <div class="input-group">
                <span class="input-group-prepend">
                  <span class="input-group-text">
                    <input type="checkbox" name="status" class="form-control-switchery" <?php if ($instansi->status == 1) { echo "checked";} ?> data-fouc> 
                  </span>
                </span>
              </div>
            </div>
          </div>
           <div class="form-group row">
              <label class="col-form-label col-lg-2">Absen Online</label>
              <div class="col-lg-10">
                <div class="input-group">
                  <span class="input-group-prepend">
                    <span class="input-group-text">
                      <input type="checkbox" name="absen_online" class="form-control-switchery" <?php if ($instansi->absen_online == 1) { echo "checked";} ?> data-fouc> 
                    </span>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-2">Alamat</label>
                <div class="col-lg-10">
                  <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                      <i class="icon-pencil3"></i>
                    </div>
                    <input type="text" class="form-control" name="alamat" value="<?php echo $instansi->alamat ?>" placeholder="Isi alamat">
                  </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-2">Titik Koordinat Kantor</label>
                <div class="col-lg-10">
                  <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                      <i class="icon-pencil3"></i>
                    </div>
                    <input type="text" class="form-control" name="latlong" value="<?php echo $instansi->latlong ?>" placeholder="Isi Titik Koordinat Kantor">
                  </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-2">Radius <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                  <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                      <i class="icon-pencil3"></i>
                    </div>
                    <input type="number" class="form-control" name="radius" placeholder="Isi Radius" value="<?php echo $instansi->radius ?>">
                  </div>
                </div>
              </div>
          <input type="hidden" name="mod" value="edit">
          <input type="hidden" name="id" value="<?php echo encrypt_url($instansi->id,'instansi') ?>">
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
                bx_alert_successUpadate(res.message, 'master/instansi');
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