<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header bg-white header-elements-inline py-2">
				<h6 class="card-title"><i class="icon-key mr-2 text-blue-400"></i>Edit nama pengguna atau kata sandi</h6>
			</div>
			<div class="card-body">
				<?php echo form_open('app/profile/AjaxGet','id="formAjax"'); ?>
            		<div class="alert alert-warning border-0 alert-dismissible">
						<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
						Kosongkan kata sandi jika tidak mengganti kata sandi
				    </div>
     
					<div class="form-group row">
						<label class="col-form-label col-lg-3">Nama Pengguna</label>
						<div class="col-lg-9">
							<div class="form-group-feedback form-group-feedback-left">
								<div class="form-control-feedback">
									<i class="icon-pencil3"></i>
								</div>
								<input class="form-control trim" placeholder="Isi nama pengguna" type="text" name="username" value="<?php echo $this->session->userdata('tpp_username'); ?>" autocomplete="off">
							</div>
						</div>
					</div>


					<div class="form-group row">
						<label class="col-form-label col-lg-3">Kata Sandi Baru</label>
						<div class="col-lg-9">
							<div class="form-group-feedback form-group-feedback-left">
									<div class="form-control-feedback">
										<i class="icon-pencil3"></i>
									</div>
									<input type="password" name="password_confirmation" placeholder="Kata Sandi Baru" class="form-control" autocomplete="new-password"/>	
							</div>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-form-label col-lg-3">Konfirmasi Kata Sandi Baru</label>
						<div class="col-lg-9">
							<div class="form-group-feedback form-group-feedback-left">
									<div class="form-control-feedback">
										<i class="icon-pencil3"></i>
									</div>
									<input type="password" name="password" placeholder="Konfirmasi Kata Sandi Baru" class="form-control" autocomplete="new-password" />
							</div>
						</div>
					</div>
					
					<input type="hidden" name="mod" value="AjaxSaveAkun">
					<div class="form-group row mb-0">
						<div class="col-lg-10 ml-lg-auto">
							<button type="reset" class="btn btn-sm bg-orange-300 result">Batal <i class="icon-cross3 ml-2"></i></button>
							<button type="submit" class="btn btn-sm btn-info result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
							<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>
			
		</div>
	</div>
</div>

<script type="text/javascript">
	$('.trim').bind('input', function(){
	    $(this).val(function(_, v){
	        return v.trim();
	    });
	});
	 //$(document).on('submit', '#formAjax', function(){
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
					load_profil('akun');
					bx_alert_ok(res.msg,'success');
				}else {
					bx_alert(res.msg);
				}

				result.attr("disabled", false);
            	spinner.hide();
            }
        });
        return false;
    });
</script>