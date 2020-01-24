<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Jadwal LKH Tidak Tetap</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
      <div class="text-left">
          <a href="<?php echo base_url('mnglkh/schlkh/add') ?>" class="btn btn-sm btn-info"><i class="icon-pen-plus mr-1"></i> Tambah Jadwal</a>
      </div>
      <div class="text-right mt-1">
        <button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
          <span><i class="icon-printer mr-2"></i> Cetak</span>
        </button> 
      </div>
	</div>

	<div class="table-responsive">
		<table id="datatable" class="table table-sm table-hover table-bordered">
			<thead>
				<tr class="table-active">
					<th width="1%">No</th>
					<th class="text-nowrap">Nama Jadwal<hr class="m-0">Priode</th>
					<th class="text-nowrap">Pesan Pengumuman</th>
					<th class="text-nowrap">Instansi</th>
					<th width="1%" style="font-size: 80%;">Jumlah Hari Input</th>
					<th width="1%" style="font-size: 80%;">Jumlah Hari verifikasi</th>
					<th width="1%">Aksi</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>