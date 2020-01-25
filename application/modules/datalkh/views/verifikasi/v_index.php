<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Verifikasi Data LKH</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>

	<div class="card-body">
    <div style="white-space: nowrap;overflow-x: auto;">
        <span class="badge bg-danger mr-1">.</span> Belum Diverifikasi 
        <i class="icon-checkmark2 text-teal mr-1 ml-2"></i> Telah Diverifikasi
    </div>
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
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
      "searching": false,
      "paging": false,
      language: {
            search: '<span></span> _INPUT_',
            searchPlaceholder: 'Cari...',
            processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
        },  
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
      ajax: {
          url : uri_dasar+'datalkh/verifikasi/indexJson',
          type:"post",
          "data": function ( data ) { 
                data.csrf_sikap_token_name= csrf_value;
              },
      },
      "columns": [
          {"data": "id", searchable:false},
          {"data": "jumlah", searchable:false},
          {"data": "nama_nip", searchable:false},
      ],
      rowCallback: function(row, data, iDisplayIndex) {
          var info = this.fnPagingInfo();
          var page = info.iPage;
          var length = info.iLength;
          var index = page * length + (iDisplayIndex + 1);
          $('td:eq(0)', row).html(index);
          $("#datatable thead").remove();
          $('td:eq(0)', row).css('width', '10px');
          $('td:eq(1)', row).css('width', '10px');
      },
       createdRow: function(row, data, index) {
       		$('td', row).eq(1).addClass('text-nowrap p-2');        
        },


  });

   // Initialize
   dt_componen();

});
</script>