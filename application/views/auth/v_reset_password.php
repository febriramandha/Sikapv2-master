<div class="limiter">
	<div class="container-login100">
		<div class="wrap-login100" style="padding-top: 50px;">
			<span class="login100-form-title" style="font-size: 16px">
				Sistem Informasi Kinerja<br> Aparatur Pemerintah
			</span>
			<div class="login100-pic js-tilt" data-tilt>
				<img src="<?php echo base_url('public/') ?>images/welcome-sikap-1.png" alt="IMG">
			</div>

			<?php echo form_open('auth/email_reset_password_validation','class="login100-form" id="formAjax"'); ?>
			<span class="login100-form-title" >
				Reset kata sandi
			</span>

			<div class="wrap-input100">
				<input class="input100" type="email" name="email" placeholder="masukkan email anda">
				<span class="focus-input100"></span>
				<span class="symbol-input100">
					<i class="fa fa-inbox" aria-hidden="true"></i>
				</span>
			</div>
			
			<div class="container-login100-form-btn">
				<button class="login100-form-btn col-md-8 col-8" id="result">
					Reset kata sandi
				</button>
			</div>
			
		</form>
		<div class="col-12">
			<div class="text-center p-t-70">
				<a class="txt2" href="#">
					&copy; 2018-<?php echo date('Y') ?> Pemerintah Kabupaten Agam
				</a>
			</div>
		</div>
		
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
				result.html('Reset kata sandi');
				result.attr("disabled", false);
				bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
			},
			beforeSend:function(){
				result.html('<i class="fa fa-refresh fa-spin fa-1x fa-fw"></i>');
				result.attr("disabled", true);
			},
			success: function(res) {
				if (res.status == true) {
					bootbox.dialog({
						message: res.alert,
						closeButton: false
					});
				}else {
					bx_alert(res.alert);
					result.html('Reset kata sandi');
					result.attr("disabled", false);
				}
			}
		});
		return false;
	});
</script>
