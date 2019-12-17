<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Data Instansi</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
     <div class="text-right mt-1">
        <button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
          <span><i class="icon-printer mr-2"></i> Cetak</span>
        </button> 
    </div>    
  </div>

	<div class="table-responsive">
		<table id="datatable" class="table table-sm table-hover">
			<thead>
				<tr>
					<th width="1%">No</th>
					<th>Nama Instansi</th>
          <th width="1%" style="font-size: 80%;">Jumlah Pengguna</th>
    			<th width="1%">Status</th>
    			<th width="1%" class="text-nowrap">Kecamatan</th>
					<th width="1%" class="text-nowrap">No Urut</th>
					<th width="1%" >Aksi</th>
				</tr>
			</thead>
			<tbody id="load_dt">
			</tbody>
		</table>
	</div>
</div>
<!-- /basic table -->

<script type="text/javascript">
var table;
$(document).ready(function() {

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
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
	    ajax: {
	        url : "<?php echo site_url('master/instansi/indexJson') ?>",
	        type:"post",
	        "data": {csrf_sikap_token_name: csrf_value},
	    },
	    "columns": [
	        {"data": "id", searchable:false},
	        {"data": "dept_alias", searchable:false},
          {"data": "jum_user", searchable:false},
          {"data": "instansi_status", searchable:false},
          {"data": "kecamatan", searchable:false},
	        {"data": "position_order", searchable:false},
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
          $('td', row).eq(5).addClass('text-center');
          $('td', row).eq(4).addClass('text-center text-nowrap');
        },


	});
	 // Initialize
	 dt_componen();
});



function confirmAksi(id) {
      $.ajax({
          url: "<?php echo site_url('master/instansi/AjaxDel') ?>",
          data: {id: id},
          dataType :"json",
          error:function(){
             $('.table').unblock();
             bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
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

$('#cetak').click(function() {
    newWindow = window.open(uri_dasar + 'master/instansi/cetak',"open",'height=600,width=800');
    if (window.focus) {newWindow.focus()}
    return false;
})

</script>