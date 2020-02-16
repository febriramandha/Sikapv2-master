<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Jadwal Jam Kerja</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>

	<div class="card-body">
		<div class="text-left">
			<a href="<?php echo base_url('mngsch/setsch-start/add') ?>" class="btn btn-sm btn-info"><i class="icon-pen-plus mr-1"></i> Tambah Jadwal</a>
		</div>
		<div class="text-right mt-1">
			<button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
				<span><i class="icon-printer mr-2"></i> Cetak</span>
			</button> 
		</div>
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<tr class="table-active">
						<th width="1%">No</th>
						<th class="text-nowrap">Nama Jadwal<hr class="m-0">Priode</th>
						<th >Unit Kerja</th>
						<th >Status</th>
						<th width="1%">Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

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
				url : uri_dasar+'mngsch/setsch-start/indexJson',
				type:"post",
				"data": function ( data ) {	
					data.csrf_sikap_token_name= csrf_value;
				},
			},
			"columns": [
			{"data": "id", searchable:false},
			{"data": "sch_name", searchable:false},
			{"data": "dept_name", searchable:false},
			{"data": "status", searchable:false},
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
          // $('td', row).eq(5).addClass('text-center');
          $('td', row).eq(1).addClass('text-nowrap p-1');
          $('td', row).eq(2).addClass('p-1');
          $('td', row).eq(3).addClass('text-nowrap text-center');
          $('td', row).eq(4).addClass('text-nowrap text-center');
      },


  });

	 // Initialize
	 dt_componen();

	});

	function confirmAksi(id) {
		$.ajax({
			url: uri_dasar+'mngsch/setsch-start/AjaxDel',
			data: {id: id},
			dataType :"json",
			error:function(){
				bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
			},
			success: function(res){
				if (res.status == true) {
					table.ajax.reload();
					bx_alert_ok(res.message,'success');
				}else {
					bx_alert(res.message);
				}

			}
		});
	}
</script>