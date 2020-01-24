<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Data Tunjangan PNS</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
      <div class="text-left">
          <a href="<?php echo base_url('master/allowance/add') ?>" class="btn btn-sm btn-info"><i class="icon-pen-plus mr-1"></i> Tambah Baru</a>
      </div>
      <div class="text-right mt-1">
        <button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
          <span><i class="icon-printer mr-2"></i> Cetak</span>
        </button> 
      </div>
	</div>

	<div class="table-responsive">
		<table id="datatable" class="table table-sm table-bordered table-hover">
			<thead>
				<tr>
					<th width="1%">No</th>
					<th class="text-nowrap">Uraian</th>
					<th width="1%">Esolon</th>
					<th width="1%">Golongan</th>
    			<th width="1%">Besaran TPP Perbulan</th>
    			<th width="1%">No Urut</th>
    			<th width="1%">Status</th>
					<th width="1%">Aksi</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
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
              "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            ajax: {
                url : "<?php echo site_url('master/allowance/json') ?>",
                type:"post",
                "data": function ( data ) { 
                       data.csrf_sikap_token_name= csrf_value;
                    },
                beforeSend:function(){
                    load_dt('#load_dt');
               },
            },
            "columns": [
                {"data": "id", searchable:false},
                {"data": "name", searchable:false},
                {"data": "eselon", searchable:false},
                {"data": "golongan", searchable:false},
                {"data": "tpp", searchable:false},
                {"data": "position", searchable:false},
                {"data": "status_tunjangan", searchable:false},
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
                $('td', row).eq(4).addClass('text-nowrap');
                $('td', row).eq(7).addClass('text-nowrap');
              },

        });

     // Initialize
     dt_componen();
});

function confirmAksi(id) {
      $.ajax({
          url: "<?php echo site_url('master/allowance/AjaxDel') ?>",
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
    newWindow = window.open(uri_dasar + 'master/allowance/cetak',"open",'height=600,width=800');
    if (window.focus) {newWindow.focus()}
    return false;
})

</script>