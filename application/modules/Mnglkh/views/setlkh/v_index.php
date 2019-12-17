<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Setup LKH</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
		<div class="col-md-12">
		 <?php echo form_open('mnglkh/setlkh/AjaxSave','id="formAjax"'); ?>
			<div class="form-group row">
			  <label class="col-form-label col-lg-3">Jumlah Hari Input LKH <span class="text-danger">*</span></label>
			  <div class="col-lg-2">
			      <div class="form-group-feedback form-group-feedback-left">
			          <div class="form-control-feedback">
			            <i class="icon-pencil3"></i>
			          </div>
			          <input type="number" class="form-control" name="input" placeholder="Isi jumlah hari" value="<?php echo $lkh[0]->jumlah ?>">
			      </div>
			  </div>
			</div>
			<div class="form-group row">
			  <label class="col-form-label col-lg-3">Jumlah Hari Verifikasi LKH <span class="text-danger">*</span></label>
			  <div class="col-lg-2">
			      <div class="form-group-feedback form-group-feedback-left">
			          <div class="form-control-feedback">
			            <i class="icon-pencil3"></i>
			          </div>
			          <input type="number" class="form-control" name="verifikasi" placeholder="Isi jumlah hari" value="<?php echo $lkh[1]->jumlah ?>">
			      </div>
			  </div>
			</div>
			<div class="text-left offset-lg-2" >
			  <button type="reset" class="btn btn-sm bg-orange-300 result">Batal <i class="icon-cross3 ml-2"></i></button>                 
			  <button type="submit" class="btn btn-sm btn-info result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
			  <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
			</div>
		<?php echo form_close(); ?>
		</div>
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
                bx_alert_successUpadate(res.message, 'mnglkh/setlkh');
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