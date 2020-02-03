<!-- Search field -->
<div class="card">
	<div class="card-body">
		<h5 class="mb-3">Monitoring Pegawai</h5>

		<form action="#">
			<div class="input-group mb-3">
				<div class="form-group-feedback form-group-feedback-left">
					<input type="text" class="form-control form-control-lg" value="" placeholder="Cari nama,nip, eselon, jabatan">
					<div class="form-control-feedback form-control-feedback-lg">
						<i class="icon-search4 text-muted"></i>
					</div>
				</div>

				<div class="input-group-append">
					<button type="submit" class="btn btn-primary btn-lg">Cari</button>
				</div>
			</div>
		</form>

		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<tr class="table-active">
						<th width="1%">No</th>
						<th class="text-nowrap">Nama<hr class="m-0">NIP</th>
						<th class="text-nowrap">Nama Instansi</th>
						<th class="text-nowrap">Jabatan</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>

	</div>
</div>
<!-- /search field -->