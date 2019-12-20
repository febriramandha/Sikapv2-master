<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Semua Pos</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
	  <div class="text-left">
			<a href="<?php echo base_url('pos/add') ?>" class="btn btn-sm btn-info"><i class="icon-pen-plus mr-1"></i> Tambah Baru</a>
	  </div>		
	</div>

	<div class="table-responsive">
		<table id="datatable" class="table table-sm table-hover">
			<thead>
				<tr>
					<th width="1%">No</th>
					<th width="col-lg-2">Tanggal</th>
					<th width="col-lg-2">Judul</th>
          			<th width="col-lg-2">Deskripsi</th>
					<th width="1%">Kategori</th>
		            <th width="1%">Status</th>
					<th width="1%">Aksi</th>
				</tr>
			</thead>
			<tbody id="load_dt">
			</tbody>
		</table>
	</div>
</div>
<!-- /basic table -->

<script type="text/javascript">
	var url = "<?= site_url()  ?>";
	var table;
	var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
	$(document).ready(function() {

		table = $('#datatable').DataTable({ 
		    processing: true, 
		    serverSide: true, 
		    "ordering": false,
		    language: {
	            search: '<span></span> _INPUT_',
	            searchPlaceholder: 'Cari...',
	            processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
	        },   
	        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		    ajax: {
		        url : "<?php echo site_url('pos/json') ?>",
		        type:"post",
		        "data": {csrf_sikap_token_name: csrf_value},
		    },
		    "columns": [
		        {"data": "id", searchable:false},
		        {"data": "created_at",searchable:false },
		        {"data": "title" },
		        {"data": "description",searchable:false },
		        {"data": "kategori",searchable:false },
		        {"data": "status",searchable:false },
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
                $('td', row).eq(6).addClass('text-nowrap');
              },

		});

		 // Initialize
		 dt_componen();
	});

	function confirmAksi(id) {
        $.ajax({
            url: "<?php echo site_url('pos/AjaxDel') ?>",
            data: {id: id},
            dataType :"json",
            error:function(){
	           $('.table').unblock();
	           message
	        },
	         beforeSend:function(){
	            load_dt('.table');
	        },
	        success: function(res) {
	            if (res.status == true) {
	                bx_alert_ok(res.message,'success');
	               table.ajax.reload();
	            }else {
	               bx_alert(res.message);
	            }
	            $('.table').unblock();
	        }
        });
    }
</script>