		<div class="sidebar sidebar-light sidebar-main sidebar-fixed sidebar-expand-md ">
			<!-- sidebar-fixed -->
			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
					Navigation
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
			<!-- /sidebar mobile toggler -->
			<!-- Sidebar content -->
			<div class="sidebar-content " style="overflow-y: auto;"> 
					<!-- User menu -->
				<div class="sidebar-user-material">
					<div class="sidebar-user-material-body pb-1">
						<div class="card-body text-center  pb-0">
							<a href="#" >
								<!-- class="d-md-none" -->
								<img src="<?php echo base_url('uploads/avatar/thumb/'.$this->session->userdata('tpp_avatar')) ?>" class="img-fluid rounded-circle shadow-1 mb-1" width="80" height="80" alt="">
							</a>
							<h6 class="mb-0 text-blue text-shadow-dark"><?php echo _name($this->session->userdata('tpp_name')) ?></h6>
							<span><?php echo level_alias($this->session->userdata('tpp_level')) ?></span>
						</div>
					</div>
				</div>
				<!-- /user menu -->
				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<!-- Main -->
						<!-- <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Menu utama</div> <i class="icon-menu" title="Main"></i>
						</li> -->
						<?php echo _sidebar_app() ?>						
						<!-- /layout -->	
					</ul>
				</div>
				<!-- /main navigation -->
			</div>
			<!-- /sidebar content -->
		</div>
		<!-- /main sidebar