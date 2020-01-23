<!DOCTYPE html>
<html lang="en">
<head>
	<title>Masuk Ke <?php echo $app_name; ?> - SISTEM INFORMASI KINERJA APARATUR</title>
	<meta charset="UTF-8">
	<meta content="<?php echo $author ?>" name="author" />
	<?php foreach($meta as $name=>$content){ ?>
		<meta name="<?php echo $name; ?>" content="<?php echo $content; ?>" />
	<?php } ?>	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="<?php echo $favicon ?>"/>
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/themes/auth/') ?>vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/themes/auth/') ?>fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/themes/auth/') ?>vendor/animate/animate.css">
	<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/themes/auth/') ?>vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/themes/auth/') ?>css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/themes/auth/') ?>css/main.css">
	<!--===============================================================================================-->

	<!--===============================================================================================-->	
	<script src="<?php echo base_url('public/themes/auth/') ?>vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url('public/themes/auth/') ?>vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo base_url('public/themes/auth/') ?>vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>public/themes/plugin/bootbox/bootbox.js"></script>
</head>
<body>
	
	<?php echo $output ?>

	<script src="<?php echo base_url('public/themes/auth/') ?>vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
		function bx_alert(msg) {
			bootbox.alert({
				title: "Peringatan!",
				message: msg,
				closeButton: false,
				buttons: {
					ok: {
						label: 'Baiklah',
						className: 'btn-info'
					}
				},
			});
		}
	</script>

</body>
</html>

<!--
* Created By: Rian Reski A
* 2019
-->