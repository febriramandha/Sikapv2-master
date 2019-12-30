<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Tambah Jadwal LKH</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
  <?php echo form_open('mnglkh/schlkh/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Nama Jadwal<span class="text-danger">*</span></label>
              <div class="col-lg-9">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="nama" class="form-control" placeholder="Nama Jadwal" >
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-2"> Rentang Waktu<span class="text-danger">*</span></label>
              <div class="col-lg-4">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="rank1" class="form-control datepicker" placeholder="tanggal mulai" >
                  </div>
              </div>
              <div class="col-lg-1">
                  <div class="form-group">
                      <span>s/d</span>
                  </div>
              </div>
              <div class="col-lg-4">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="rank2" class="form-control datepicker" placeholder="tanggal berakhir" >
                  </div>
              </div>
          </div>

           <div class="form-group row">
              <label class="col-form-label col-lg-2"> Waktu LKH<span class="text-danger">*</span></label>
              <div class="col-lg-4">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="lkh_in" class="form-control" placeholder="waktu hari LKH input" >
                  </div>
              </div>
              <div class="col-lg-1">
                  <div class="form-group">
                      <span>dan</span>
                  </div>
              </div>
              <div class="col-lg-4">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="lkh_ver" class="form-control" placeholder="waktu hari LKH verifikasi" >
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-lg-2 col-form-label">Pesan Pengumuman <span class="text-danger">*</span></label>
              <div class="col-lg-9">
                 <textarea id="ckeditor" name="isi"></textarea>
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
$(".datepicker").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
});
var ckeditor = CKEDITOR.replace('ckeditor',
            {
              height:'120px',
              customConfig: uri_dasar+'public/themes/plugin/ckeditor/custom/ckeditor_config.js'}
);
  
function CKupdate(){
for ( instance in CKEDITOR.instances )
    CKEDITOR.instances[instance].updateElement();
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
                bx_alert_success(res.message, 'mnglkh/schlkh');
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