<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-light fixed-top">
	<!-- Header with logos -->
	<div class="navbar-header navbar-dark d-none d-md-flex align-items-md-center bg-light">
		<div class="navbar-brand navbar-brand-md"  style="margin-left: 0;padding-top: 0;padding-bottom: 0;">
			<a href="<?php echo base_url() ?>" class="d-inline-block"  >
				<img src="<?php echo base_url() ?>public/images/logo-01-14-dark_11.png" alt="" style="height: 50px;" >
			</a>
		</div>
		<div class="navbar-brand navbar-brand-xs">
			<a href="<?php echo base_url() ?>" class="d-inline-block">
				<img src="<?php echo base_url() ?>public/images/logo_yy.png" alt="">
			</a>
		</div>
	</div>
	<!-- /header with logos -->
	<!-- Mobile controls -->
	<div class="d-flex flex-1 d-md-none">
		<div class="navbar-brand mr-auto">
			<a href="<?php echo base_url() ?>" class="d-inline-block">
				<img src="<?php echo base_url() ?>public/images/logo-01-14-dark_11.png" alt="" style="height: 2rem; margin: -10px;">
			</a>
		</div>	
		<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
			<i class="icon-paragraph-justify3"></i>
		</button>
	</div>
	<!-- /mobile controls -->
	<!-- Navbar content -->
	<div class="collapse navbar-collapse" id="navbar-mobile">
		<ul class="navbar-nav">
			<li class="nav-item">
				<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
					<i class="icon-paragraph-justify3"></i>
				</a>
			</li>
		</ul>
		<span class="navbar-text ml-md-3 mr-md-auto">
			<span class="badge badge-mark border-success-300 mr-2"></span>  
		</span>
		<ul class="navbar-nav">
			<li class="nav-item dropdown">
			</li>
			<li class="nav-item dropdown dropdown-user">
				<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
					<img src="<?php echo base_url('uploads/avatar/thumb/'.$this->session->userdata('tpp_avatar')) ?>" class="rounded-circle" alt="" width="35" >
					<span>Hai, <?php echo ucwords(strtolower($this->session->userdata('tpp_name'))) ?></span>
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="<?php echo base_url('app/profile') ?>" class="dropdown-item"><i class="icon-user"></i>Profil</a>
					<a href="<?php echo base_url('auth/logout') ?>" class="dropdown-item"><i class="icon-move-left"></i> Keluar</a>
				</div>
			</li>
		</ul>
	</div>
	<!-- /navbar content -->
</div>
<!-- /main navbar -->
