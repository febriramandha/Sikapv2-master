<!-- Search field -->
				<div class="card">
					<div class="card-body">
						<div class="text-center mb-3 py-2">
							<h4 class="font-weight-semibold mb-1"><?php echo $instansi->dept_name ?></h4>
							<span class="text-muted d-block">Data Instansi</span>
						</div>

						<div class="col-md-12">
							<div class="row">
								<div class="col-md-3">
									<div class="card-img-actions d-inline-block mb-3">
										<img class="image_avatar img-fluid rounded-circle" src="<?php echo base_url('uploads/avatar/thumb/') ?>avatar.png" width="170" height="170" alt="">
										<div class="card-img-actions-overlay card-img rounded-circle">
											<a data-popup="tooltip" title="Lihat Foto" href="<?php echo base_url('uploads/avatar/thumb/') ?>avatar.png" class="image_avatar btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round">
												<i class="icon-eye2"></i>
											</a>
											<a href="#" id="tukarphoto" data-popup="tooltip" title="Ganti Foto Max 2MB" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
												<i class="icon-link"></i>
											</a>
											<form id="formphoto">
												<input type="file" name="file" id="file" style="display: none;"/>
											</form>
										</div>
									</div>
								</div>
								<div class="col-md-9">
									 <div class="table-responsive ">
										<table class="table" style="white-space: nowrap;">
											<tr>
												<td width="150">Nama Instansi</td>
												<td width="2">:</td>
												<td><?php echo $instansi->dept_name ?></td>
											</tr>
											<tr>
												<td>Singkatan</td>
												<td>:</td>
												<td><?php echo $instansi->dept_alias ?></td>
											</tr>
											<tr>
												<td>Alamat</td>
												<td>:</td>
												<td><?php echo $instansi->alamat ?></td>
											</tr>
											<tr>
												<td>Urutan</td>
												<td>:</td>
												<td><?php echo $instansi->position_order ?></td>
											</tr>
											<tr>
												<td>Jumlah Total Sub Instansi</td>
												<td>:</td>
												<td>1</td>
											</tr>
											<tr>
												<td>Jumlah Total Pengguna</td>
												<td>:</td>
												<td>1</td>
											</tr>
											<tr>
												<td>Jumlah Total Pengguna PNS</td>
												<td>:</td>
												<td>1</td>
											</tr>
											<tr>
												<td>Jumlah Total Pengguna Non PNS</td>
												<td>:</td>
												<td>1</td>
											</tr>
											<tr>
												<td>Jumlah Total Pengguna Aktif</td>
												<td>:</td>
												<td>1</td>
											</tr>
											<tr>
												<td>Jumlah Total Pengguna Non Aktif</td>
												<td>:</td>
												<td>1</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>

						

						
						
					</div>
				</div>
				<!-- /search field -->