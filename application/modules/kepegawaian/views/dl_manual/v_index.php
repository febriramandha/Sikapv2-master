<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Data Dinas</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">

		<?php echo form_open('kepegawaian/dl-manual/view/','class="form-horizontal" id="formAjax"'); ?>
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Unit Kerja <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group">
                       <select class="form-control select-search" name="instansi" >  
                              <?php foreach ($instansi as $row) { ?>
                                <option value="<?php echo encrypt_url($row->id,'instansi') ?>"><?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?></option>
                              <?php } ?>
                      </select> 
                  </div>
              </div>
          </div>
          <input type="hidden" name="mod" value="next">
          <div class="text-left offset-lg-2" >                 
              <span class="btn btn-sm btn-info result" id="simpan">Tambah Dinas Luar <i class="icon-pen-plus ml-2"></i></span>
              <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
          </div>
        </div>
        <?php echo form_close() ?>  
        <hr>
    <div class="table-responsive">
      <table id="datatable" class="table table-sm table-hover table-bordered">
        <thead>
          <tr class="table-active">
            <th width="1%">No</th>
            <th class="text-nowrap">Tanggal</th>
            <th class="text-nowrap">Kegiatan</th>
            <th class="text-nowrap">Hasil</th>
            <th width="1%">Pegawai</th>
            <th width="1%">Aksi</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
	</div>
</div>
<input type="hidden" name="stag" value="0">
<script type="text/javascript">
$('#simpan').click(function() {
    var id = $('[name="instansi"]').val();
    window.location.href = uri_dasar+'kepegawaian/dl-manual/add/'+id; 
});

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
          url : uri_dasar+'kepegawaian/dl-manual/indexJson',
          type:"post",
          "data": function ( data ) { 
                data.csrf_sikap_token_name= csrf_value;
                  if ($('[name="stag"]').val() == 1) {
                    data.instansi=$('[name="instansi"]').val();
                  }else {
                      data.instansi= localStorage.index_instansi;
                  } 
              },
      },
      "columns": [
          {"data": "id", searchable:false},
          {"data": "tanggal", searchable:false},
          {"data": "uraian", searchable:false},
          {"data": "hasil", searchable:false},
          {"data": "pegawai", searchable:false},
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
          $('td', row).eq(2).addClass('p-0');
          $('td', row).eq(3).addClass('p-0');
          $('td', row).eq(4).addClass('text-nowrap p-1');
          $('td', row).eq(5).addClass('text-nowrap text-center');
        },


  });

   // Initialize
   dt_componen();
   loadSettings();

});

$(window).on('unload', function(){
    saveSettings();
});

$('[name="instansi"]').change(function() {
    if ($('[name="stag"]').val() == 1) {
        table.ajax.reload();
    }else {
          $('[name="stag"]').val(1);
    }
})

function loadSettings() {
  if (localStorage.index_instansi) {
     $('[name="instansi"]').val(localStorage.index_instansi).trigger('change');
     if (!$('[name="instansi"]').val()) {
        $('[name="instansi"]').val($('[name="instansi"] option:first').val()).trigger('change');
     }
  }
}

function saveSettings() {
    var instansi = $('[name="instansi"]').val();
    if (instansi) {
      localStorage.index_instansi = instansi;
    }
    
}


function confirmAksi(id) {
        $.ajax({
            url: uri_dasar+'kepegawaian/dl-manual/AjaxDel',
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