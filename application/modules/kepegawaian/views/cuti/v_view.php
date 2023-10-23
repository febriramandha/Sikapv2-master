<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Cuti Pegawai</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
		 <?php echo nama_icon_nip($user->nama, $user->gelar_dpn, $user->gelar_blk,$user->nip,'','',$user->jabatan) ?>
    		<hr>

		<?php echo form_open('kepegawaian/cuti/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2"> Rentang Waktu<span class="text-danger">*</span></label>
              <div class="col-lg-4">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="rank1" class="form-control datepicker" placeholder="tanggal mulai" >
                  </div>
              </div>
              <div class="col-lg-1">
                      <span>s/d</span>
              </div>
              <div class="col-lg-4">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="rank2" class="form-control datepicker" placeholder="tanggal berakhir" >
                  </div>
              </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-2"> Jenis Cuti<span class="text-danger">*</span></label>
            <div class="col-lg-8">
            	<select class="form-control select-nosearch" name="jenis">
            		<?php foreach ($cuti as $row ) { ?>
                        <option value="<?php echo $row->id ?>"><?php echo $row->kode ?> (<?php echo $row->nama ?>)</option>
                    <?php } ?>
            	</select>
            </div>
         </div>
          <input type="hidden" name="id">
          <input type="hidden" name="dept_id" value="<?php echo encrypt_url($user->dept_id,'dept_id') ?>">
          <input type="hidden" name="user_id" value="<?php echo encrypt_url($user->id,'user_id_cuti') ?>">
          <input type="hidden" name="mod" value="add">
          <div class="text-left offset-lg-2" >
             <a href="javascript:history.back()" class="btn btn-sm bg-success-300 result">Kembali <i class="icon-arrow-left5 ml-2"></i></a>                    
              <button type="submit" class="btn btn-sm btn-info result" id="result">Tambah Cuti <i class="icon-pen-plus ml-2"></i></button>
              <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
          </div>
        </div>
        <?php echo form_close() ?> 
        <hr>
        <div class="text-right mt-1">
				<button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
					<span><i class="icon-printer mr-2"></i> Cetak</span>
				</button> 
		</div>

		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<tr class="table-active">
						<th width="1%">No</th>
						<th class="text-nowrap">Tanggal Cuti</th>
						<th class="text-nowrap">Jenis Cuti</th>
						<th width="1%" style="font-size: 80%;">Jumlah Hari</th>
						<th width="1%">Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- /basic table -->

<script type="text/javascript">
$(".datepicker").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
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
	        url : uri_dasar+'kepegawaian/cuti/CutiJson/'+$('[name="user_id"]').val(),
	        type:"post",
	        "data": function ( data ) {	
        				data.csrf_sikap_token_name= csrf_value;
	            },
	    },
	    "columns": [
	        {"data": "id", searchable:false},
	        {"data": "start_date", searchable:false},
	        {"data": "cuti_nama", searchable:false},
	        {"data": "kode", searchable:false},
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
          // $('td', row).eq(5).addClass('text-center');
          $('td', row).eq(3).addClass('text-nowrap');
        },


	});

	 // Initialize
	 dt_componen();
});

$('#formAjax').submit(function() {
  var simpan  = $('#result');
  var result  = $('.result');
  var spinner = $('#spinner');
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType : "JSON",
        error:function(){
           result.attr("disabled", false);
           spinner.hide();
           bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
         beforeSend:function(){
            result.attr("disabled", true);
            spinner.show();
        },
        success: function(res) {
            if (res.status == true) {
                bx_alert_ok(res.message,'success');
                $('#formAjax')[0].reset();
                $('[name="jenis"]').val('').trigger('change');
                $('[name="mod"]').val('add');
                $('[name="id"]').val('');
                table.ajax.reload();
                simpan.html('Tambah Cuti <i class="icon-pen-plus ml-2"></i>');
            }else {
                bx_alert(res.message);
            }
            result.attr("disabled", false);
            spinner.hide();
        }
    });
    return false;
});

$(document).on('click', '.aksi_edit', function(){
    var id = $(this).attr('id');
    var result = $('#result');
    $.ajax({
        url: "<?php echo site_url('kepegawaian/cuti/AjaxGet') ?>",
        data: {id: id, modul:'dataEdit'},
        dataType :"json",
        error:function(){
           bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        success: function(res){  
            $('#formAjax')[0].reset();
            $('input[name="mod"]').val('edit');
            result.html('Edit Cuti <i class="icon-checkmark4 ml-2"></i>');
            var r = res.data;
            $('input[name="rank1"]').val(r.start_date);
            $('[name="rank2"]').val(r.end_date);
            $('[name="jenis"]').val(r.cuti_id).trigger('change');
            $('input[name="id"]').val(r.id);
            
        }
    });

} );

function confirmAksi(id) {
	$.ajax({
        type: 'get',
        url: uri_dasar+'kepegawaian/cuti/AjaxDel',
        data: {id:id},
        dataType : "JSON",
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

</script>