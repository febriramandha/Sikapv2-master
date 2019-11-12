<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline pb-1 pt-sm-1">
		<h5 class="card-title">Data Instansi</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
    <div class="row">
       <!--  <div class="col-md-6">
            <a href="javascript:;" id="add" class="btn btn-info btn-sm legitRipple"><i class="icon-stack-plus mr-2"></i> Tambah</a>
        </div> -->
        <div class="col-md-12">
            <div class="text-right">
              <button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="result">
                <span><i class="icon-printer mr-2"></i> Cetak</span>
              </button> 
            </div>
        </div>
      
    </div>
		
    
	</div>

	<div class="table-responsive">
		<table id="datatable" class="table table-sm table-hover">
			<thead>
				<tr>
					<th width="1%">No</th>
					<th>Nama Instansi</th>
          <th width="1%">Status</th>
					<th width="1%">Urutan</th>
					<th class="col-2">Aksi</th>
				</tr>
			</thead>
			<tbody id="load_dt">
			</tbody>
		</table>
	</div>
</div>
<!-- /basic table -->
<!-- Basic modal -->
<div id="modal_default" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Title</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <?php echo form_open('master/instansi/AjaxSave','id="formAjax"'); ?>

      <div class="modal-body">
          <div class="text-center">
              <h6 class="m-0 font-weight-semibold" id="instansi"></h6>
          </div>
          <input type="hidden" name="id">
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Nama Instansi</label>
            <div class="col-lg-10">
              <div class="form-group">
                  <input type="text" class="form-control" name="nama" placeholder="Isi nama instansi disini">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Nama Singkat</label>
            <div class="col-lg-10">
              <div class="form-group">
                  <input type="text" class="form-control" name="alias" placeholder="Isi nama singkat disini">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Alamat</label>
            <div class="col-lg-10">
              <div class="form-group">
                  <input type="text" class="form-control" name="alamat" placeholder="Isi alamat">
              </div>
            </div>
          </div>
           <div class="form-group row">
            <label class="col-form-label col-lg-2">No Urut</label>
            <div class="col-lg-3">
              <div class="form-group">
                  <input type="number" class="form-control" id="order" name="order" placeholder="order">
              </div>
            </div>
          </div>
      </div>
      <input type="hidden" name="mod" value="add">
      <input type="hidden" name="parent" >
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Tutup</button>
        <button class="btn btn-primary btn-sm legitRipple" style="display: none;" id="loading" disabled>
                        Simpan <span ><i class="icon-spinner2 spinner"></i></span> 
        </button>                        
        <button type="submit" class="btn btn-sm btn-primary"  id="result">Simpan <i class="icon-checkmark4 ml-2"></i></button>

      </div>

    </div>
    <?php echo form_close(); ?>
  </div>
</div>
<!-- /basic modal -->


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
	        url : "<?php echo site_url('master/instansi/Getjson') ?>",
	        type:"post",
	        "data": {csrf_sikap_token_name: csrf_value},
	        beforeSend:function(){
		        	load_dt('#load_dt');
		     },
	    },
	    "columns": [
	        {"data": "id", searchable:false},
	        {"data": "dept_alias", searchable:false},
          {"data": "instansi_status", searchable:false},
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
          $('td', row).eq(4).addClass('text-nowrap');
        },


	});

	 // Initialize
	 dt_componen();
});

$(document).on('click', '.read', function(){
    var id = $(this).attr('id');
    window.location.assign(url+'master/instansi/read/'+id);
} );

$(document).on('click', '.plus', function(){
	var parent = $(this).attr('id');
    $('.modal-title').text('Tambah Instansi');
    $('input[name="mod"]').val('add'); 
    $('input[name="parent"]').val(parent);       
    $('#formAjax')[0].reset();
    $('#modal_default').modal('show');
    AjaxGetPosition(parent);
    $('#instansi').text("Instansi Induk: "+$(this).attr('data'));
} );

$('#add').click(function(){ 
    $('.modal-title').text('Tambah Instansi');
    $('input[name="mod"]').val('add'); 
    $('input[name="parent"]').val(0);       
    $('#formAjax')[0].reset();
    $('#modal_default').modal('show');
    AjaxGetPosition(0);
    $('#instansi').text('');
 });

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
                toastr["success"](res.alert);
            }else {
                toastr["error"](res.alert);
            }
        }
    });
    return false;
});

 function edit(id) {
    $.ajax({
        url: "<?php echo site_url('master/instansi/AjaxGet') ?>",
        data: {mod:"get_instansi",id: id},
        dataType :"json",
        success: function(res){
            $('.modal-title').text('Ubah Data Instansi');    
            $('#formAjax')[0].reset();
            $('#modal_default').modal('show');
            $('input[name="mod"]').val('edit');
            $('#instansi').text('');
            var r = res.data;
            $('input[name="nama"]').val(r.dept_name);
            $('input[name="alias"]').val(r.dept_alias);
            $('input[name="alamat"]').val(r.alamat);
            $('input[name="order"]').val(r.position_order);
            $('input[name="id"]').val(r.id);
            
        }
    });
}

function confirmAksi(id) {
        $.ajax({
            url: "<?php echo site_url('master/instansi/AjaxDel') ?>",
            data: {id: id},
            dataType :"json",
            success: function(res){
                if (res.status == true) {
                    table.ajax.reload();
                    toastr["success"](res.msg);

                }else {
                    toastr["warning"](res.msg);
                }
                
            }
        });
    }

function AjaxGetPosition(parent) {
	 $.ajax({
            url: "<?php echo site_url('master/instansi/AjaxGet') ?>",
            data: {mod: "get_posititon", parent:parent},
            dataType :"json",
            success: function(res){
           		$('[name="order"]').val(res.position);     
            }
        });
}

$(document).on('click', '.non_aktif', function(){
    var data = $(this).attr('id');
    bootbox.dialog({
    title:"Konfirmasi",
    message: "Ya Ingin Non Aktifkan Instansi",
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
                  url: url+'master/instansi/AjaxGet',
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
});

$(document).on('click', '.aktif', function(){
    var data = $(this).attr('id');
    bootbox.dialog({
    title:"Konfirmasi",
    message: "Ya Ingin Aktifkan Instansi",
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
                  url: url+'master/instansi/AjaxGet',
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
});


</script>