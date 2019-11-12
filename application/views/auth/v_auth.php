<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100" style="padding-top: 50px;">
				<span class="login100-form-title" style="font-size: 16px">
						Sistem Informasi Kinerja<br> Aparatur Pemerintah
				</span>
				<div class="login100-pic js-tilt" data-tilt>
					<img src="<?php echo base_url('public/') ?>images/logo_sk.png" alt="IMG">
				</div>

			   <?php echo form_open('auth/GetData','class="login100-form" id="formAjax"'); ?>
					<span class="login100-form-title" >
						Masuk Ke SIKAP
					</span>

					<div class="wrap-input100">
						<input class="input100" type="text" name="username" placeholder="Nama Pengguna">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100">
						<input class="input100" type="password" name="password" placeholder="Kata Sandi">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					

					<div class="text-center">
						<input type="checkbox" name="remember"  id="checkbox_id" data-fouc>
						<label class="txt2 m-b-0" for="checkbox_id">Biarkan Tetap Masuk</label>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn col-md-4 col-4" id="result">
							Masuk
						</button>
					</div>
					<div class="text-center p-t-70">
						<a class="txt2" href="#">
							&copy; 2018-<?php echo date('Y') ?> Pemerintah Kabupaten Agam
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>

<script type="text/javascript">
        $('#formAjax').submit(function() {
        var result = $('#result');
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType :"JSON",
            error:function(){
	      	 		result.html('Masuk');
        			result.attr("disabled", false);
		     },
		    beforeSend:function(){
		       		result.html('<i class="fa fa-refresh fa-spin fa-1x fa-fw"></i>');
        			result.attr("disabled", true);
		    },
            success: function(res) {
                if (res.status == true) {
                    location.reload();
                    toastr["success"](res.alert);
                }else {
                   	toastr["warning"](res.alert);
                   	result.html('Masuk');
        			result.attr("disabled", false);
                }
            }
        });
        return false;
    });
</script>
	