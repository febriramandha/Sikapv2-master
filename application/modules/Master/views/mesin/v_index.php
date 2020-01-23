<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Data Mesin</h5>
		<div class="header-elements">
			<div class="list-icons">
        <a class="list-icons-item" data-action="collapse"></a>
      </div>
    </div>
  </div>

  <div class="card-body">
    <div class="text-left">
      <a href="<?php echo base_url('master/mesin/add') ?>" class="btn btn-sm btn-info"><i class="icon-pen-plus mr-1"></i> Tambah Mesin</a>
    </div>
    <div class="text-right mt-1">
      <button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
        <span><i class="icon-printer mr-2"></i> Cetak</span>
      </button> 
    </div>
    <div class="table-responsive">
      <table id="datatable" class="table table-sm table-bordered table-hover">
        <thead>
          <tr>
            <th width="1%">No</th>
            <th class="text-nowrap">Nama Mesin</th>
            <th width="1%">No Mesin</th>
            <th class="text-nowrap">Alamat IP</th>
            <th class="text-nowrap">Nama Instansi</th>
            <th width="1%">Status</th>
            <th width="1%">Keterangan</th>
            <th width="1%">Aksi</th>
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
    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    ajax: {
      url : "<?php echo site_url('master/mesin/indexJson') ?>",
      type:"post",
      "data": function ( data ) { 
       data.csrf_sikap_token_name= csrf_value;
       data.instansi=$('[name="instansi"]').val();
     },
   },
   "columns": [
   {"data": "id", searchable:false},
   {"data": "name", searchable:false},
   {"data": "machine_number", searchable:false},
   {"data": "ip", searchable:false},
   {"data": "dept_alias", searchable:false},
   {"data": "status_mesin", searchable:false},
   {"data": "ket", searchable:false},
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
    $('td', row).eq(7).addClass('text-nowrap');
  },

});

     // Initialize
     dt_componen();
   });

  function confirmAksi(id) {
    $.ajax({
      url: "<?php echo site_url('master/mesin/AjaxDel') ?>",
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
    newWindow = window.open(uri_dasar + 'master/mesin/cetak',"open",'height=600,width=800');
    if (window.focus) {newWindow.focus()}
      return false;
  })


</script>