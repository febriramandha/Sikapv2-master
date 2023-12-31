

<!-- Search field -->
<div class="card">
	<div class="card-body">
		<div class="mb-3 py-2">
			<div class="col-lg-9" style="overflow-x: auto;white-space: nowrap;">
			<?php echo nama_icon_nip($user->nama, $user->gelar_dpn,$user->gelar_blk, $user->jabatan); ?>
			</div>
		</div>
		<div class="col-md-12">
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
					<div class="table-responsive">
						<table id="datatable" class="table table-sm table-hover">
							<thead>
								<tr>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<a href="<?php echo base_url('monitoring/mon-pegawai/view/'.$this->uri->segment(4)) ?>?p=absen" class="list-icons-item" data-popup="tooltip" title="Lihat Data Kehadiran"><i class="icon-file-stats mr-1"></i>  Data Kerhadiran</a>
									</td>
								</tr>
								<tr>
									<td>
										<a href="<?php echo base_url('monitoring/mon-pegawai/view/'.$this->uri->segment(4)) ?>?p=lkh" class="list-icons-item" data-popup="tooltip" title="Lihat Data Kehadiran"><i class="icon-libreoffice mr-1"></i>  Data LKH</a>
									</td>
								</tr>
								<tr>
									<td>
										<a href="<?php echo base_url('monitoring/mon-pegawai/view/'.$this->uri->segment(4)) ?>?p=ibadah" class="list-icons-item" data-popup="tooltip" title="Lihat Data Kehadiran"><i class="icon-book3 mr-1"></i>  Data Ibadah</a>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-9">
					<hr class="mx-0">
					<h5>Biodata</h5><hr class="m-0">
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
								<th>ID Absen</th>
								<td>:</td>
								<td><?php echo $user->key ?></td>
							</tr>
							<tr>
								<th>Unit Kerja</th>
								<td>:</td>
								<td><?php echo _name($user->dept_name) ?></td>
							</tr>
							<tr>
								<th>Jenis Kelamin</th>
								<td>:</td>
								<td><?php echo gender($user->gender) ?></td>
							</tr>
							<tr>
								<th>Tanggal Lahir</th>
								<td>:</td>
								<td><i class="icon-calendar3 mr-1"></i><?php echo format_tgl_ind($user->lahir_tanggal) ?></td>
							</tr>
							<tr>
								<th>Umur</th>
								<td>:</td>
								<td><?php echo _umur($user->lahir_tanggal) ?></td>
							</tr>

							
							<tr>
								<th>Agama</th>
								<td>:</td>
								<td><?php echo $user->agama ?></td>
							</tr>
							<tr>
								<th>Status Pernikahan</th>
								<td>:</td>
								<td><?php echo status_kawin($user->statkawin_id) ?></td>
							</tr>
							<tr>
								<th>Status Kepegawaian</th>
								<td>:</td>
								<td><?php echo $user->status_pegawai ?></td>
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
							<tr>
								<th>Login Terakhir</th>
								<td>:</td>
								<td><?php echo format_waktu_ind($user->last_login) ?></td>
							</tr>
							
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /search field -->