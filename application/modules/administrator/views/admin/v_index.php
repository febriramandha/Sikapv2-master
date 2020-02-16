<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Data Admin</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>

	<div class="card-body">
		<div class="form-group row">
			<label class="col-form-label col-lg-2">Unit Kerja <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<select class="form-control select-search" name="instansi"> 
						<?php foreach ($instansi as $row) { ?>
							<option value="<?php echo encrypt_url($row->id,'instansi') ?>"><?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?></option>
						<?php } ?>
					</select> 
				</div>
			</div>
		</div>

		<div class="text-right mt-1">
			<button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
				<span><i class="icon-printer mr-2"></i> Cetak</span>
			</button> 
		</div>	
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<tr>
						<th width="1%">No</th>
						<th class="text-nowrap">Nama|NIP</th>
						<th class="text-nowrap">Nama Pengguna</th>
						<th class="text-nowrap">Unit Kerja</th>
						<th width="1%" style="font-size: 80%;">Kewanangan</th>
						<th width="1%" style="font-size: 80%;">Status Akun</th>
						<th width="1%">Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>	
	</div>
</div>
<!-- /basic table -->

<script type="text/javascript">
	$(document).ready(function(){
		table = $('#datatable').DataTable({ 
			processing: true, 
			serverSide: true, 
			"ordering": false,
			language: {
				search: '<span></span> _INPUT_',
				searchPlaceholder: 'Cari...',
				processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
			},  
			"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
			ajax: {
				url : uri_dasar+'administrator/admin/indexJson',
				type:"post",
				"data": function ( data ) {	
					data.csrf_sikap_token_name= csrf_value;
					data.instansi=$('[name="instansi"]').val();
				},
			},
			"columns": [
			{"data": "id", searchable:false},
			{"data": "nama_nip", searchable:false},
			{"data": "username", searchable:false},
			{"data": "dept_alias", searchable:false},
			{"data": "level", searchable:false},
			{"data": "status_user", searchable:false},
			{"data": "action", searchable:false},
			],
			rowCallback: function(row, data, iDisplayIndex) {
				var info = this.fnPagingInfo();
				var page = info.iPage;
				var length = info.iLength;
				var index = page * length + (iDisplayIndex + 1);
				$('td:eq(0)', row).html(index);
			},
			createdRow: function(row, data, index) {
           $('td', row).eq(1).addClass('text-nowrap p-1');
      },


  });

	 // Initialize
	 dt_componen();
	});
	$('[name="instansi"]').change(function() {
		table.ajax.reload();
	})

	$('#cetak').click(function() {
		newWindow = window.open(uri_dasar + 'administrator/admin/cetak/'+$('[name="instansi"]').val(),"open",'height=600,width=800');
		if (window.focus) {newWindow.focus()}
			return false;
	})
</script>