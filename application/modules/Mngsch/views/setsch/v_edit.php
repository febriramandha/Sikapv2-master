<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Tambah Jadwal</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">

  <?php echo form_open('mngsch/setsch/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-3">Nama Jadwal Kerja <span class="text-danger">*</span></label>
              <div class="col-lg-9">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="nama" class="form-control" placeholder="Nama Jadwal Kerja" value="<?php echo $jadwal->name ?>" >
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-3">Jam Masuk <span class="text-danger">*</span></label>
              <div class="col-lg-9">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="jam_masuk" class="form-control clockpicker" placeholder="Jam Masuk" value="<?php echo jm($jadwal->start_time) ?>" >
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-3">Jam Pulang <span class="text-danger">*</span></label>
              <div class="col-lg-9">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="jam_pulang" class="form-control clockpicker" placeholder="Jam Pulang" value="<?php echo jm($jadwal->end_time) ?>" >
                  </div>
              </div>
          </div>

          <div class="form-group row">
              <label class="col-form-label col-lg-3">Jam Mulai Scan Masuk <span class="text-danger">*</span></label>
              <div class="col-lg-9">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="mulai_cin" class="form-control clockpicker" placeholder="Jam Mulai Scan Masuk" value="<?php echo jm($jadwal->check_in_time1) ?>" >
                  </div>
              </div>
          </div>

          <div class="form-group row">
              <label class="col-form-label col-lg-3">Jam Akhir Scan Masuk <span class="text-danger">*</span></label>
              <div class="col-lg-9">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="akhir_cin" class="form-control clockpicker" placeholder="Jam Akhir Scan Masuk" value="<?php echo jm($jadwal->check_in_time2) ?>" >
                  </div>
              </div>
          </div>

          <div class="form-group row">
              <label class="col-form-label col-lg-3">Jam Mulai Scan Pulang <span class="text-danger">*</span></label>
              <div class="col-lg-9">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="mulai_cout" class="form-control clockpicker" placeholder="Jam Mulai Scan Pulang" value="<?php echo jm($jadwal->check_out_time1) ?>" >
                  </div>
              </div>
          </div>

          <div class="form-group row">
              <label class="col-form-label col-lg-3">Jam Akhir Scan Pulang <span class="text-danger">*</span></label>
              <div class="col-lg-9">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="akhir_cout" class="form-control clockpicker" placeholder="Jam Akhir Scan Pulang" value="<?php echo jm($jadwal->check_out_time2) ?>" >
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-3">Hitungan Hari <span class="text-danger">*</span></label>
              <div class="col-lg-4">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="number" name="hari" class="form-control" placeholder="Jam Akhir Scan Pulang" value="<?php echo $jadwal->work_day ?>" >
                       <span><i>* sesuaikan jumlah hari jika jadwal shift</i></span>
                  </div>
              </div>
          </div>

          <div class="form-group row">
              <label class="col-form-label col-lg-3">Jenis Jam Kerja <span class="text-danger">*</span></label>
              <div class="col-lg-2">
                  <div class="form-group">
                      <select class="form-control select-nosearch" name="jenis" >  
                              <option disabled="">Pilih Jenis</option> 
                              <option value="umum" <?php if ($jadwal->sch_type == "umum") { echo "selected"; } ?>>Umum</option>
                              <option value="shift" <?php if ($jadwal->sch_type == "shift") { echo "selected"; } ?>>shift</option>
                      </select> 
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <div class="col-lg-3">
                    <label class="pure-material-checkbox"> 
                        <input type="checkbox"  name="cekin" <?php if ($jadwal->required_in == 1) { echo "checked"; } ?> /> <span>Harus Ceklok Masuk</span>
                    </label>
              </div>
              <div class="col-lg-3">
                    <label class="pure-material-checkbox"> 
                        <input type="checkbox" name="cekout" <?php if ($jadwal->required_out == 1) { echo "checked"; } ?>/> <span>Harus Ceklok Pulang</span>
                    </label>
              </div>
          </div>       
          <input type="hidden" name="id" value="<?php echo $jadwal->id ?>">
          <input type="hidden" name="mod" value="edit">
          <div class="text-left offset-lg-3" >
              <button type="reset" class="btn btn-sm bg-orange-300 result">Batal <i class="icon-cross3 ml-2"></i></button>                 
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
                bx_alert_successUpadate(res.message, 'mngsch/setsch');
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