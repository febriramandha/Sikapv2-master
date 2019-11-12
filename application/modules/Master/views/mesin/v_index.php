<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline pb-1 pt-sm-1">
		<h5 class="card-title">Data Mesin</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
      <div class="text-left">
			     <a href="javascript:;" id="add" class="btn btn-sm btn-info"><i class="icon-stack-plus mr-2"></i> Tambah Baru</a>
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
					<th class="text-nowrap">Nama Mesin</th>
					<th width="1%">No Mesin</th>
          <th width="1%">IP</th>
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
<!-- /basic table -->

<!-- Basic modal -->
<div id="modal_default" class="modal fade" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Title</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <?php echo form_open('master/mesin/AjaxSave','id="formAjax"'); ?>

      <div class="modal-body">
          <div class="text-center">
              <h6 class="m-0 font-weight-semibold" id="instansi"></h6>
          </div>
          <input type="hidden" name="id">
          <input type="hidden" name="instansi_add">
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Pilih Instansi</label>
            <div class="col-lg-10">
              <div class="form-group">
                  <select class="form-control select-search" name="instansi" >   
                     
                   </select> 
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">IP Adress</label>
            <div class="col-lg-10">
              <div class="form-group">
                  <input type="text" class="form-control" name="ip" placeholder="Isi IP Adress">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Password</label>
            <div class="col-lg-10">
              <div class="form-group">
                  <input type="text" class="form-control" name="password" placeholder="Isi password">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Port</label>
            <div class="col-lg-10">
              <div class="form-group">
                  <input type="text" class="form-control" name="port" placeholder="Isi port" value="4370">
              </div>
            </div>
          </div>
           <div class="form-group row">
            <label class="col-form-label col-lg-2">Keterangan</label>
            <div class="col-lg-10">
              <div class="form-group">
                  <textarea name="ket" class="form-control" placeholder="Isi keterangan"></textarea>
              </div>
            </div>
          </div>
      </div>
      <input type="hidden" name="mod" value="add">
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Tutup</button>                      
        <button type="submit" class="btn btn-sm btn-primary"  id="result">Simpan <i class="icon-checkmark4 ml-2"></i></button>

      </div>

    </div>
    <?php echo form_close(); ?>
  </div>
</div>
<!-- /basic modal -->
<script type="text/javascript">
var url = "<?= site_url()  ?>";
var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';

$(document).ready(function(){
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
                url : "<?php echo site_url('master/mesin/json') ?>",
                type:"post",
                "data": function ( data ) { 
                       data.csrf_sikap_token_name= csrf_value;
                       data.instansi=$('[name="instansi"]').val();
                    },
                beforeSend:function(){
                    load_dt('#load_dt');
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
                $('td', row).eq(6).addClass('text-nowrap');
              },

        });

     // Initialize
     dt_componen();
});

var data_instansi =  getInstansi();
$('[name="instansi"]').select2({
    placeholder: 'Pilih Instansi',
    allowClear: true,
    data: data_instansi,
});

if (data_instansi) {
  $('[name="instansi"]').val('').trigger('change');
}

function getInstansi() {
  var result = false;
    $.ajax({
        url: "<?php echo site_url('kepegawaian/data-pegawai/AjaxGet') ?>",
        data: {mod: "instansi"},
        dataType :"json",
        async: false,
        success: function(res){
          result = res;
        }
    });
  return result;
}

$('#add').click(function(){ 
      $('.modal-title').text('Tambah Data Mesin');
      $('input[name="mod"]').val('add');     
      $('#formAjax')[0].reset();
      $('#modal_default').modal('show');

 });

function getInstansi(id) {
  $.ajax({
      url: "<?php echo site_url('administrator/user/AjaxGet') ?>",
      data: {mod: "instansi", id:id},
      dataType :"json",
      success: function(res){
        $('#instansi').text('INSTANSI : '+res.instansi);
      }
  });
}


$('#formAjax').submit(function() {
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType : "JSON",
        success: function(res) {
            if (res.status == true) {
                $('#formAjax')[0].reset();
                table.ajax.reload();
                $('#modal_default').modal('hide');
                $('#instansi').text('');
                toastr["success"](res.alert);
            }else {
                toastr["error"](res.alert);
            }
        }
    });
    return false;
});

$(document).on('click', '.edit', function(){
    var data = $(this).attr('data'); 
    $('#formAjax')[0].reset();
    $('#modal_default').modal('show');
    $('input[name="id"]').val(data); 
    $('[name="mod"]').val('edit'); 
    $('.modal-title').text('Edit Data Mesin');
    $.ajax({
          url: "<?php echo site_url('master/mesin/AjaxGet') ?>",
          data: {mod: "get_edit", id:data},
          dataType :"json",
          success: function(res){
            if (res.status==true) {
                getInstansi(res.data.dept_id);
                $('input[name="instansi_add"]').val(res.data.dept_id);
                $('[name="ip"]').val(res.data.ip);
                $('[name="port"]').val(res.data.port);
                $('[name="password"]').val(res.data.password);
                $('[name="ket"]').val(res.data.ket);
                $('[name="id"]').val(res.data.id);
            }
          }
      });
} );

$(document).on('click', '.non_aktif', function(){
    var data = $(this).attr('data');
    bootbox.dialog({
    title:"Konfirmasi",
    message: "Ya Ingin Non Aktifkan Mesin",
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
                  url: url+'master/mesin/AjaxGet',
                  data: {mod: "non_aktif", id:data},
                  dataType : "JSON",
                   error:function(){
                     $('#load_dt').unblock();
                  },
                   beforeSend:function(){
                      load_dt('#load_dt');
                  },
                  success: function(res) {
                      if (res.status == true) {
                          toastr["success"](res.msg);
                          table.ajax.reload();
                      }else {
                          toastr["error"](res.msg);
                          $('#load_dt').unblock();
                      }
                      
                  }
              });
          }
          }
        }
    }
  });
} );

$(document).on('click', '.aktif', function(){
    var data = $(this).attr('data');
    bootbox.dialog({
    title:"Konfirmasi",
    message: "Ya Ingin Aktifkan Mesin",
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
                  url: url+'master/mesin/AjaxGet',
                  data: {mod: "aktif", id:data},
                  dataType : "JSON",
                   error:function(){
                     $('#load_dt').unblock();
                  },
                   beforeSend:function(){
                      load_dt('#load_dt');
                  },
                  success: function(res) {
                      if (res.status == true) {
                          toastr["success"](res.msg);
                          table.ajax.reload();
                      }else {
                          toastr["error"](res.msg);
                          $('#load_dt').unblock();
                      }
                      
                  }
              });
          }
          }
        }
    }
  });
} );

</script>