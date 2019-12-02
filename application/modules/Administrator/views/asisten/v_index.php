<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Data Pejabat Asisten Daerah</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
		<div class="text-right">
			<a href="<?php echo base_url('administrator/asisten/add') ?>" class="btn btn-sm btn-info"><i class="icon-pen-plus mr-1"></i> Tambah Asisten</a>
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
				<tr>
					<th width="1%">No</th>
					<th class="text-nowrap">Nama(nip)</th>
					<th>Jabatan</th>
					<th>Instansi</th>
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
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
	    ajax: {
	        url : "<?php echo site_url('administrator/asisten/JsonAsisten') ?>",
	        type:"post",
	        "data": function ( data ) {	
        				 data.csrf_sikap_token_name= csrf_value;
	            },
	    },
	    rowsGroup: [1,2],
	    "columns": [
	        {"data": "id", searchable:false},
	        {"data": "nama_nip", searchable:false},
	        {"data": "jabatan", searchable:false},
	        {"data": "dept_alias", searchable:false},
	        {"data": "action", searchable:false},
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

$(document).on('click', '.deleted', function(){
    var data = $(this).attr('data');
    bootbox.dialog({
	  	title:"Konfirmasi",
	  	message: "Ya Ingin Hapus Data Ini",
		buttons: {
		    "cancel" : {
		      	"label" : "<i class='icon-cross3'></i> Tidak",
		      	"className" : "btn-danger"
		    },
		    "main" : {
		      	"label" : "<i class='icon-checkmark2'></i> Ya",
		      	"className" : "btn-primary",
		      	callback:function(result){
		        	if (result) {
						$.ajax({
					        url: uri_dasar+'administrator/asisten/AjaxDel',
					        data: {id:data},
					        dataType : "JSON",
					        error:function(){
						      	 $('#load_dt').unblock();
						      	 bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
						    },
						    beforeSend:function(){
						       		load_dt('#load_dt');
						    },
					        success: function(res) {
					            if (res.status == true) {
					            	bx_alert_ok(res.message,'success');
					                table.ajax.reload();
					            }else {
					                bx_alert(res.message);
					                $('#load_dt').unblock();
					            }
					            
					        }
					    });
					}
		    	}
		    }
		}
	});
});

$('#cetak').click(function() {
	  newWindow = window.open(uri_dasar + 'administrator/asisten/cetak',"open",'height=600,width=800');
		if (window.focus) {newWindow.focus()}
		return false;
})
</script>