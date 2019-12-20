<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Tambah Jam Kerja</h5>
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
              <label class="col-form-label col-lg-3">Nama Jam Kerja <span class="text-danger">*</span></label>
              <div class="col-lg-9">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="nama" class="form-control" placeholder="Nama Jadwal Kerja" autocomplete="off" >
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
                      <input type="text" name="jam_masuk" class="form-control clockpicker" placeholder="Jam Masuk" value="07:30" >
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
                      <input type="text" name="jam_pulang" class="form-control clockpicker" placeholder="Jam Pulang" value="16:30" >
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
                      <input type="text" name="mulai_cin" class="form-control clockpicker" placeholder="Jam Mulai Scan Masuk" value="06:30" >
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
                      <input type="text" name="akhir_cin" class="form-control clockpicker" placeholder="Jam Akhir Scan Masuk" value="12:00" >
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
                      <input type="text" name="mulai_cout" class="form-control clockpicker" placeholder="Jam Mulai Scan Pulang" value="12:01" >
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
                      <input type="text" name="akhir_cout" class="form-control clockpicker" placeholder="Jam Akhir Scan Pulang" value="23:59" >
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
                      <input type="number" name="hari" class="form-control" placeholder="Jam Akhir Scan Pulang" value="1" >
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
                              <option value="umum">Umum</option>
                              <option value="shift">shift</option>
                      </select> 
                  </div>
              </div>
          </div>
           <div class="form-group row">
              <div class="col-lg-3">
                    <label class="pure-material-checkbox"> 
                        <input type="checkbox"  name="cekin"  /> <span>Harus Ceklok Masuk</span>
                    </label>
              </div>
              <div class="col-lg-3">
                    <label class="pure-material-checkbox"> 
                        <input type="checkbox" name="cekout" /> <span>Harus Ceklok Pulang</span>
                    </label>
              </div>
          </div>     
                      
         
          <input type="hidden" name="mod" value="add">
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
                bx_alert_success(res.message, 'mngsch/setsch');
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