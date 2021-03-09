<div class="limiter">
	<div class="container-login100">
		<div class="wrap-login100" style="padding-top: 50px;">
			<span class="login100-form-title" style="font-size: 16px">
				SISTEM INFORMASI KINERJA<br> APARATUR PEMERINTAH
			</span>
			<div class="login100-pic js-tilt" data-tilt>
				<img src="<?php echo base_url('public/') ?>images/img-02-1_1.png" alt="IMG">
			</div>

			<?php echo form_open('auth/GetData','class="login100-form" id="formAjax"'); ?>
			<span class="login100-form-title" style="padding-bottom: 20px;">
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
				<input type="checkbox" name="remember"  id="checkbox_id" checked data-fouc>
				<label class="txt2 m-b-0" for="checkbox_id">Biarkan Tetap Masuk</label>
			</div>
			
			<div class="container-login100-form-btn">
				<button class="login100-form-btn col-md-4 col-4" id="result">
					Masuk
				</button>
			</div>
			<div class="text-center p-t-10">
				<a href="https://agamkab.go.id/" target="_blank">
					<img src="<?php echo base_url('public/') ?>images/situs-agam.png" width="200px">
				</a>
			</div>
			
		</form>
		<div class="col-12">
			<div class="text-center p-t-70">
				<a class="txt2" href="#">
					SIKAP 2.1.1
					&copy; 2018-<?php echo date('Y') ?> Powered by <a href="#">Web Programmer Dinas Komunikasi dan Informatika Kab. Agam </a>
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
				result.html('Masuk');
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
						message: res.message,
						closeButton: false
					});
					location.reload();
				}else {
					bx_alert(res.message);
					result.html('Masuk');
					result.attr("disabled", false);
				}
			}
		});
		return false;
	});
</script>
