<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Verifikasi Laporan</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>
<?php 
$date_now = date('Y-m-d');
?>
	<div class="card-body">
		<div class="alert alert-warning alert-dismissible">
			<?php if ($tanggal_lkh) { ?>
		    <span class="font-weight-semibold"> H - <?php echo $jumlkh->count_verday-1 ?> verifikasi</span> 
			<?php }else { echo "tidak ada laporan yang harus diverifikasi";} ?>
		</div>	
		
		<div class="form-group row">
		  <label class="col-lg-2 col-form-label">Data Laporan </label>
		  <div class="col-lg-9" style="overflow-x: auto;white-space: nowrap;">
		   <?php echo nama_icon_nip($user->nama, $user->gelar_dpn,$user->gelar_blk, $user->jabatan); ?>
		  </div>
		</div>

		<?php if ($tanggal_lkh): ?>
		<div class="form-group row mb-0">
		  <label class="col-lg-2 col-form-label">Tanggal verifikasi </label>
		  <div class="col-lg-10">
				<ul class="nav nav-pills nav-pills-bordered nav-pills-toolbar">
					<?php $no=1;
					 foreach ($tanggal_lkh as $row) {  $tgl_verifikasi[] = $row->rentan_tanggal;?>
						<li class="nav-item col-md-4 p-0 m-0">
							<a href="#pill" class="tanggal nav-link " da="<?php echo encrypt_url($row->rentan_tanggal,"tanggal_lkh_verifikasi_$date_now") ?>" data-toggle="tab">
								<?php echo tglInd_hrtabel($row->rentan_tanggal) ?>
								<span class="badge bg-danger" id="<?php echo $row->rentan_tanggal ?>" ></span>
							</a>
						</li>
					<?php } ?>
					<input type="hidden" name="tgl_id" value="0">
				</ul>
			</div>
		</div>
		<?php endif ?>
		 <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
		<?php echo form_open('datalkh/verifikasi/AjaxSaveVer/','id="formAjax"'); ?>
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<tr class="table-active">
						<th width="1%" >No</th>
						<th class="text-center p-2" width="1%">
							<label class="pure-material-checkbox ml-1"> 
								<input class=""  type="checkbox" id="checkAll" /> <span></span>
							</label>
						</th>
						<th class="text-nowrap text-center" width="1%">Tanggal</th>
						<th class="text-nowrap text-center p-1" width="1%">Jam <hr class="m-0">(mulai - selesai)</th>
						<th class="text-nowrap text-center pl-md-3 pl-5 pr-md-3 pr-5" >Uraian Kegiatan</th>
						<th class="text-nowrap text-center pl-md-3 pl-5 pr-md-3 pr-5" >Hasil</th>
						<th width="1%" class="p-1">Status</th>
						<th width="1%" class="p-1">Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
				<tr>
					<td colspan="9" align="left">
						<input type="hidden" name="user_id_" value="<?php echo encrypt_url($user->user_id,'user_id') ?>">
						<button type="submit" class="btn btn-sm bg-info legitRipple result"><i class="icon-checkmark2"></i> Verifikasi LKH yang dipilih</button>
						<a href="<?php echo base_url('datalkh/verifikasi') ?>" class="btn btn-sm bg-warning legitRipple"><i class="icon-undo2"></i> Kembali</a> 
					</td>
                </tr>
			</table>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
<!-- Basic modal -->
<div id="modal_default" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Beri alasan untuk laporan ditolak</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<?php echo form_open('datalkh/verifikasi/AjaxSaveComment/','id="formAjaxComment"'); ?>
			<div class="modal-body">
				<input type="hidden" name="id">
				<div class="form-group row">
					<div class="col-lg-12">
						<textarea class="form-control" placeholder="isi alasan ditolak" name="komentar"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="user_id_" value="<?php echo encrypt_url($user->user_id,'user_id_comment') ?>">
				<input type="hidden" name="mod" value="Comment">
				<button type="button" class="btn btn-sm btn-danger result" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-sm btn-info result">Kirim <i class="icon-checkmark4 ml-2"></i></button>
         		 <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	

			</div>

		</div>
		<?php echo form_close(); ?>
	</div>
</div>
<input type="hidden" name="user_id" value="<?php echo encrypt_url($user->user_id,'user_id') ?>">
<!-- /basic modal -->	
<script type="text/javascript">
var tgl_lkh = <?= json_encode($tgl_verifikasi) ?>;
var user_id = $('[name="user_id"]').val();
$(document).on('click', '.tol', function(){
	$('input[name="id"]').val($(this).attr('data_id'));
	$('#modal_default').modal('show');
});
$('#checkAll').click(function () {    
    $('.checkbox').prop('checked', this.checked);  
});



function load_notif(tgl) {
    $.ajax({
          type:"get",
          url: uri_dasar+'datalkh/verifikasi/AjaxGet',
          data:{ mod:'load_notif', id:user_id, tgl, tgl},
          dataType :"JSON",
          cache : true,
              success: function(res){
                    if (res.status) {
                    	var tgl = res.data.tanggal;
                    	var jum = res.data.count;
                    		for (var i = 0; i < jum.length; i++) {
                    			$('#'+tgl[i]).text(jum[i]);
                    		}
                    }
              }
        });
}


$(document).ready(function(){
	load_notif(tgl_lkh);
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
          url : uri_dasar+'datalkh/verifikasi/viewJson/<?= $this->uri->segment(4) ?>',
          type:"post",
          "data": function ( data ) { 
                data.csrf_sikap_token_name= csrf_value;
                data.tgl_id 	 = $('input[name="tgl_id"]').val();
              },
      },
      "columns": [
          {"data": "id", searchable:false},
          {"data": "cek", searchable:false},
          {"data": "tgl_lkh_tabel", searchable:false},
          {"data": "jam_mulai", searchable:false},
          {"data": "kegiatan", searchable:false},
          {"data": "hasil", searchable:false},
          {"data": "status_lkh", searchable:false},
          {"data": "action", searchable:false},
      ],
      rowsGroup: [2],
      rowCallback: function(row, data, iDisplayIndex) {
          var info = this.fnPagingInfo();
          var page = info.iPage;
          var length = info.iLength;
          var index = page * length + (iDisplayIndex + 1);
          $('td:eq(0)', row).html(index);
      },
       createdRow: function(row, data, index) {
       		$('td', row).eq(1).addClass('text-center p-2');
       		$('td', row).eq(2).addClass('text-nowrap p-2');
       		$('td', row).eq(3).addClass('text-center text-nowrap p-1');
			$('td', row).eq(4).addClass('normal_text p-2');
			$('td', row).eq(5).addClass('normal_text p-2');
			$('td', row).eq(6).addClass('text-center p-1');
			$('td', row).eq(7).addClass('text-center p-1');
			$('td', row).eq(8).addClass('text-center p-2');
         
        },


  });

   // Initialize
   dt_componen();

});

$('.tanggal').click(function(){ 
  	var da=$(this).attr("da");
	$('input[name="tgl_id"]').val(da);
	table.ajax.reload();

});

 $('#formAjax').submit(function() {
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
          	table.ajax.reload();
          	load_notif(tgl_lkh);
            bx_alert_ok(res.message, 'success');
          }else {
            bx_alert(res.message);
          }
          result.attr("disabled", false);
          spinner.hide();
        }
    });
      return false;
  });

 $('#formAjaxComment').submit(function() {
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
          	table.ajax.reload();
          	load_notif(tgl_lkh);
            bx_alert_ok(res.message, 'success');
            $('#modal_default').modal('hide');
          }else {
            bx_alert(res.message);
          }
          result.attr("disabled", false);
          spinner.hide();
        }
    });
      return false;
  });

 $(document).on('click', '.ver', function(){
     var lkh_id = $(this).attr('data_id');
     $.ajax({
          type:"get",
          url: uri_dasar+'datalkh/verifikasi/AjaxGet',
          data:{ mod:'verifikasi', lkh_id:lkh_id, id:user_id},
          dataType :"JSON",
          cache : true,
          error:function(){
	           bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
	       },
          beforeSend:function(){
          		$('.loading'+lkh_id).block({
		            message: '<i class="icon-spinner spinner"></i>',
		            overlayCSS: {
		                backgroundColor: '#fff',
		                opacity: 0.8,
		                cursor: 'wait'
		            },
		            css: {
		                border: 0,
		                padding: 0,
		                backgroundColor: 'none'
		            }
	        });
	      		
	      },
          success: function(res){
	           if (res.status == true) {
	           		load_notif(tgl_lkh);
	           		toastr["success"](res.message);
	                table.ajax.reload();
	                load_notif(tgl_lkh);
	            }
          }
        });
});
</script>