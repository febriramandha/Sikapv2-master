<!-- Search field -->
<div class="card">
	<div class="card-body">
		<div class="text-center mb-3 py-2">
			<h4 class="font-weight-semibold mb-1">Verifikator</h4>
		</div>

		<div class="col-md-12">
			<?php if ($user) { ?>
			<div class="row">
				<div class="col-md-3">
					<div class="card-img-actions d-inline-block mb-3">
						<img class="image_avatar img-fluid rounded-circle" src="<?php echo base_url('uploads/avatar/thumb/'.$user->avatar) ?>" width="170" height="170" alt="">
						<div class="card-img-actions-overlay card-img rounded-circle">
							<a data-popup="tooltip" title="Lihat Foto" href="<?php echo base_url('uploads/avatar/thumb/'.$user->avatar) ?>" class="image_avatar btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round">
								<i class="icon-eye2"></i>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					 <div class="table-responsive ">
						<table class="table" style="white-space: nowrap;">
							<tr >
								<th width="200">Nama Lengkap</th>
								<td width="2">:</td>
								<td ><?php echo name_degree(_name($user->nama),$user->gelar_dpn,$user->gelar_blk)   ?></td>
							</tr>
							<tr>
								<th>NIP</th>
								<td>:</td>
								<td><?php echo $user->nip ?></td>
							</tr>
							<tr>
								<th>Instansi</th>
								<td>:</td>
								<td><?php echo _name($user->dept_name) ?></td>
							</tr>
							<tr>
								<th>Pangkat/Golongan</th>
								<td>:</td>
								<td><?php echo $user->pangkat ?>/<?php echo $user->golongan ?></td>
							</tr>
							<tr>
								<th>Jabatan</th>
								<td>:</td>
								<td><?php echo $user->jabatan ?></td>
							</tr>
							<tr>
								<th>Eselon</th>
								<td>:</td>
								<td><?php echo $user->eselon ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		   <?php }else{ ?>
		   		<div class="alert alert-warning border-0 alert-dismissible">
						<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
						verifikator belum ditambahkan
				</div>
		   <?php } ?>
		</div>
	</div>
</div>
<!-- /search field -->