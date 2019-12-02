<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>404 Page Not Found</title>
	<meta content="rianreski" name="author" />
	<link rel="icon" href="<?php echo config_item('base_url') ?>public/images/favicon.ico" type="image/gif">
	<!-- Global stylesheets -->
	<link href="<?php echo config_item('base_url') ?>public/themes/material/css/font_family.css" rel="stylesheet" type="text/css">
	<link href="<?php echo config_item('base_url') ?>public/themes/material/global_assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo config_item('base_url') ?>public/themes/material/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo config_item('base_url') ?>public/themes/material/css/bootstrap_limitless.css" rel="stylesheet" type="text/css">
	<link href="<?php echo config_item('base_url') ?>public/themes/material/css/layout.css" rel="stylesheet" type="text/css">
	<link href="<?php echo config_item('base_url') ?>public/themes/material/css/components.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo config_item('base_url') ?>public/themes/material/css/colors.min.css" rel="stylesheet" type="text/css">
	<!-- Core JS files -->
	<script src="<?php echo config_item('base_url') ?>public/themes/material/global_assets/js/main/jquery.min.js"></script>
	<script src="<?php echo config_item('base_url') ?>public/themes/material/global_assets/js/main/bootstrap.bundle.min.js"></script>
	<script src="<?php echo config_item('base_url') ?>public/themes/material/global_assets/js/plugins/loaders/blockui.min.js"></script>
	<script src="<?php echo config_item('base_url') ?>public/themes/material/global_assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /core JS files -->

</head>

<body>
		
<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-light navbar-static">
		<div class="navbar-brand">
			<a href="<?php echo config_item('base_url') ?>" class="d-inline-block">
				<img src="<?php echo config_item('base_url') ?>public/images/logo-01-14-dark-b.png" alt="" style="height: 2rem; margin: -10px;">
			</a>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">			</ul>

			<span class="navbar-text ml-md-3">
				<span class="badge badge-mark border-orange-300 mr-2"></span>
				404 Page Not Found
			</span>

			
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">

				<!-- Container -->
				<div class="flex-fill">

					<!-- Error title -->
					<div class="text-center mb-3">
						<h1 class="error-title">404</h1>
						<h5>Oops, halaman error. Maaf halaman yang Anda cari tidak ditemukan!.</h5>
						<p>Cobalah Kembali ke Halaman Utama</p>
					</div>
					<!-- /error title -->


					<!-- Error content -->
					<div class="row">
						<div class="col-xl-4 offset-xl-4 col-md-8 offset-md-2">
							<!-- Buttons -->
							<div class="row">
								<div class="offset-sm-3 col-sm-6">
									<a href="<?php echo config_item('base_url') ?>" class="btn btn-primary btn-block"><i class="icon-home4 mr-2" ></i> Halaman Beranda <span id="pesan"></span></a>
								</div>
							</div>
							<!-- /buttons -->

						</div>
					</div>
					<!-- /error wrapper -->

				</div>
				<!-- /container -->

			</div>
			<!-- /content area -->


			<!-- Footer -->
			<div class="navbar navbar-light">
				<div class="text-center w-100">
					<button type="button" class="navbar-toggler" >
						<i class="icon-circles mr-2"></i>
						&copy; 2018 - <?php echo date('Y') ?> <a href="#">Pemerintah Kabupaten Agam</a> 
					</button>
				</div>
			</div>
			<!-- /footer -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->
</body>
</html>

<script type="text/javascript">
	var url = "<?php echo config_item('base_url') ?>"; // url tujuan
            var count = 30; // dalam detik
            function countDown() {
                if (count > 0) {
                    count--;
                    var waktu = count + 1;
                    $('#pesan').html(waktu);
                    setTimeout("countDown()", 1000);
                } else {
                    window.location.href = url;
                }
            }
            countDown();
</script>