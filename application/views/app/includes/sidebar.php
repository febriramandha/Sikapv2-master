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
			                  <?php echo nama_icon_nip($this->session->userdata('tpp_name'),'','',level_alias($this->session->userdata('tpp_level')),) ?>
						</div>
					</div>
				</div>
				<!-- /user menu -->
				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<!-- Main -->
						<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Menu utama</div> <i class="icon-menu" title="Main"></i>
						</li>
						<?php echo _sidebar_app() ?>						
						<!-- /layout -->	
					</ul>
				</div>
				<!-- /main navigation -->
			</div>
			<!-- /sidebar content -->
		</div>
		<!-- /main sidebar