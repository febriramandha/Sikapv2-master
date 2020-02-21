<!-- Search field -->
<div class="card">
	<div class="card-body">
		<h5 class="mb-3">Monitoring Pegawai</h5>
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
<script type="text/javascript">
var result  = $('.result');
var spinner = $('#spinner');

$('#cari').click(function() {
	result.attr("disabled", true);
    spinner.show();
    $('#cari').hide();
	table.ajax.reload();
})


$(document).ready(function(){
     table = $('#datatable').DataTable({ 
	    processing: true, 
	    serverSide: true, 
	    "ordering": false,
	    stateSave: true,
	    language: {
            search: '<span></span> _INPUT_',
            searchPlaceholder: 'Cari...',
            processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
        },  
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
	    ajax: {
	        url : uri_dasar+'monitoring/mon-pegawai/indexJson',
	        type:"post",
	        "data": function ( data ) {	
        				data.csrf_sikap_token_name= csrf_value;
        				data.cari  = $('[name="pegawai"]').val();
	            },
	        beforeSend:function(){
					result.attr("disabled", true);
					$('#cari').hide();
		      		spinner.show();
			},
			"dataSrc": function ( json ) {
                //Make your callback here.
                result.attr("disabled", false);
	          	spinner.hide();
	          	$('#cari').show();
                return json.data;
            } 
	    },
	    "columns": [
	        {"data": "id", searchable:false},
	        {"data": "nama_nip", searchable:false},
	        {"data": "instansi", searchable:false},
	        {"data": "jabatan", searchable:false},
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
           $('td', row).eq(2).addClass('text-nowrap p-2');
           $('td', row).eq(3).addClass('p-2');
        },


	});
	 dt_componen();
});
</script>