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
      <div class="text-right">
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
<!-- /basic table -->

<!-- Basic modal -->
<div id="modal_default" class="modal fade" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Title</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <?php echo form_open('master/allowance/AjaxSave','id="formAjax"'); ?>

      <div class="modal-body">
          <input type="hidden" name="id">
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Uraian</label>
            <div class="col-lg-10">
              <div class="form-group">
                  <input type="text" class="form-control" name="nama" placeholder="Isi Uraian">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Pilih Esolon</label>
            <div class="col-lg-10">
              <div class="form-group" >
                  <select class="form-control select-fixed-single" name="eselon" placeholder="Pilih Esolon">
                  		<?php foreach ($eselon as $row) { ?>
                  			<option value="<?php echo $row->id ?>"><?php echo $row->eselon ?></option>
                  		<?php } ?>
                  </select>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Pilih Golongan</label>
            <div class="col-lg-10">
              <div class="form-group">
                 <select class="form-control select-fixed-single" name="golongan" placeholder="Pilih Esolon">
                  		<?php foreach ($golongan as $row) { ?>
                  			<option value="<?php echo $row->id ?>"><?php echo $row->golongan ?>(<?php echo $row->pangkat ?>)</option>
                  		<?php } ?>
                  </select>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Besaran TPP</label>
            <div class="col-lg-10">
              <div class="form-group">
                  <input type="text" class="form-control" name="tpp" placeholder="Isi besaran TPP" >
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Nomor Urut</label>
            <div class="col-lg-10">
              <div class="form-group">
                  <input type="text" class="form-control" name="order" placeholder="" >
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2">Status</label>
            <div class="col-lg-10">
              <div class="form-group">
                  <span class="badge badge-success" disabled="">
				  	         <input type="radio" name="status" value="1" checked=""> Aktif
      				    </span>
      				    <span class="badge badge-danger" disabled="">
      				  	   <input type="radio" name="status" value="0" > Non Aktif
      				    </span>
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
$('#add').click(function(){ 
      $('.modal-title').text('Tambah Data Besaran TPP');
      $('input[name="mod"]').val('add');     
      $('#formAjax')[0].reset();
      $('#modal_default').modal('show');
      AjaxGetPosition();

    
 });

$('.select-fixed-single').select2({
    minimumResultsForSearch: Infinity,
    width: 350
});

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

function AjaxGetPosition() {
	$.ajax({
            url: "<?php echo site_url('master/allowance/AjaxGet') ?>",
            data: {mod: "Getposititon"},
            dataType :"json",
            success: function(res){
           		$('[name="order"]').val(res.data.position);     
            }
        });
}

$('#formAjax').submit(function() {
	var result = $('#result');
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType : "JSON",
         error:function(){
	      	 result.html('<span><i class="icon-checkmark4 ml-2"></i> Simpan</span>');
	      	 result.attr("disabled", false);
	      },
	       beforeSend:function(){
	       		result.html('<i class="icon-spinner2 spinner"></i> Proses..');
	 			result.attr("disabled", true);
	      },
        success: function(res) {
            if (res.status == true) {
                $('#formAjax')[0].reset();
                table.ajax.reload();
                $('#modal_default').modal('hide');
                toastr["success"](res.alert);
            }else {
                toastr["error"](res.alert);
            }

            result.html('<span><i class="icon-checkmark4 ml-2"></i> Simpan</span>');
	          result.attr("disabled", false);
        }
    });
    return false;
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
					        url: url+'master/allowance/AjaxDel',
					        data: {id:data},
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

$(document).on('click', '.edit', function(){
    var data = $(this).attr('data'); 
    $('#formAjax')[0].reset();
    $('#modal_default').modal('show');
    $('input[name="id"]').val(data);  
    $('.modal-title').text('Edit Data Besaran TPP');
    $('input[name="mod"]').val('edit');     
    $.ajax({
	    url: "<?php echo site_url('master/allowance/AjaxGet') ?>",
	    data: {mod: "edit", id:data},
	    dataType :"json",
	    success: function(res){
	   		$('input[name="nama"]').val(res.data.name);
	   		$('[name="eselon"]').val(res.data.eselon_id).trigger('change');
	   		$('[name="golongan"]').val(res.data.golongan_id).trigger('change');
	   		$('[name="tpp"]').val(res.data.tpp);
	   		$('[name="order"]').val(res.data.position);
	   		$('input:radio[name="status"]').filter('[value="'+res.data.status+'"]').attr('checked', true);
	    }
	});
} );


</script>