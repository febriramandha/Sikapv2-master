<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Setup LKH Instansi</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
      <div class="text-right">
          <a href="<?php echo base_url('master/allowance/add') ?>" class="btn btn-sm btn-info"><i class="icon-pen-plus mr-1"></i> Tambah Baru</a>
      </div>
	</div>

	<div class="table-responsive">
		<table id="datatable" class="table table-sm table-bordered table-hover">
			<thead>
				<tr>
					<th width="1%">No</th>
					<th class="text-nowrap">Nama Instansi</th>
					<th width="1%" style="font-size: 80%;">Jumlah Hari Input</th>
					<th width="1%" style="font-size: 80%;">Jumlah Hari verifikasi</th>
					<th width="1%" style="font-size: 80%;">Tanggal Mulai</th>
					<th width="1%" style="font-size: 80%;">Tanggal Berakhir</th>
	    			<th width="1%">Status</th>
					<th width="1%">Aksi</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>