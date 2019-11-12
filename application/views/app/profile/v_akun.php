<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header bg-white">
				<h6 class="card-title"><i class="icon-key mr-2 text-blue-400"></i>Ganti username atau kata sandi</h6>
			</div>
			<div class="card-body">
				<?php echo form_open('app/profile/AjaxGet','id="formAjax"'); ?>
            		<div class="alert alert-warning border-0 alert-dismissible">
						<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
						Kosongkan Kata Sandi Jika Tidak Ingin Mengganti Kata Sandi
				    </div>
     
					<div class="form-group row">
						<label class="col-form-label col-lg-3">Username</label>
						<div class="col-lg-9">
							<input class="form-control" placeholder="Isi Username" type="text" name="username" value="<?php echo $this->session->userdata('tpp_username'); ?>">
						</div>
					</div>


					<div class="form-group row">
						<label class="col-form-label col-lg-3">Kata Sandi Baru</label>

						<div class="col-lg-9">
							<div class="form-group">
									<input type="password" name="password_confirmation" placeholder="Kata Sandi Baru" class="form-control"/>	
							</div>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-form-label col-lg-3">Konfirmasi Kata Sandi Baru</label>

						<div class="col-lg-9">
							<div class="form-group">
									<input type="password" name="password" placeholder="Konfirmasi Kata Sandi Baru" class="form-control" />
							</div>
						</div>
					</div>
					
					<input type="hidden" name="mod" value="AjaxSaveAkun">
					<div class="form-group row mb-0">
						<div class="col-lg-10 ml-lg-auto">
							<button type="reset" class="btn btn-sm btn-default">Batal <i class="icon-cross3 ml-2"></i></button>
							<button type="submit" class="btn btn-sm btn-info" id="result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>
			
		</div>
	</div>
</div>

<script type="text/javascript">
	 //$(document).on('submit', '#formAjax', function(){
     $('#formAjax').submit(function() {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType : "JSON",
            success: function(res) {
            	
            	if (res.status == true) {
					load_profil('akun');
                    toastr["success"](res.msg);
				}else {
					toastr["warning"](res.msg);
				}
            }
        });
        return false;
    });
</script>