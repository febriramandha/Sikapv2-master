<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline pb-1 pt-sm-1">
		<h5 class="card-title">Data Level Pengguna</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover">
				<thead>
					<tr>
						<th width="1%">No</th>
						<th width="1%">Level</th>
						<th >Nama</th>
					</tr>
				</thead>
				<tbody id="load_dt">
				</tbody>
			</table>
		</div>
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
        }, 
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
	    ajax: {
	        url : "<?php echo site_url('master/ulevel/Getjson') ?>",
	        type:"post",
	        "data": {csrf_sikap_token_name: csrf_value},
	        beforeSend:function(){
		        	load_dt('#load_dt');
		     },
	    },
	    "columns": [
	        {"data": "id", searchable:false},
	        {"data": "level", searchable:false},
	        {"data": "name", searchable:false},
	    ],
	    rowCallback: function(row, data, iDisplayIndex) {
	        var info = this.fnPagingInfo();
	        var page = info.iPage;
	        var length = info.iLength;
	        var index = page * length + (iDisplayIndex + 1);
	        $('td:eq(0)', row).html(index);
	    },

	});

	 // Initialize
	 dt_componen();
});
</script>