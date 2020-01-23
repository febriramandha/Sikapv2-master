<div class="card col-lg-12 ">
	<div class="card-header bg-white header-elements-sm-inline pb-0">
		<h6 class="font-weight-semibold"> <i class="icon-bell2 mr-3"></i>Info/Pengumuman</h6>
	</div>
	<div class="table-responsive m-0" id="pos">
		<table class="table text-nowrap">
			<?php
			foreach ($pos->result() as $row ) { ?>
				<thead>
					<tr>
						<td>
							<a href="#" class="text-default font-weight-semibold"><?php echo $row->title  ?> <span class="badge badge-info"><?php echo $row->kategori ?></span></a>
							<div class="text-muted font-size-sm">
								<span class="font-weight-semibold"><i class="icon-calendar3 mr-1"></i><?php echo format_waktu_ind($row->created_at)  ?></span><br>
								<span class="badge badge-mark border-blue mr-1"></span>
								<?php echo $row->description  ?>
							</div>
							<span class="text-muted"><?php echo $row->content  ?></span>
						</td>
					</tr>
				</thead>
			<?php } ?>
		</table>
	</div>
</div>