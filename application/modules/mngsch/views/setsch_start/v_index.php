<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Jadwal Jam Kerja</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>

	<div class="card-body">
		<div class="text-left">
			<a href="<?php echo base_url('mngsch/setsch-start/add') ?>" class="btn btn-sm btn-info"><i class="icon-pen-plus mr-1"></i> Tambah Jadwal</a>
		</div>
		<!-- <div class="text-right mt-1">
			<button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
				<span><i class="icon-printer mr-2"></i> Cetak</span>
			</button> 
		</div> -->
		<div class="form-group row mt-2">
			<label class="col-form-label col-lg-2"> Periode</label>
			<div class="col-lg-10">
				<div class="form-group-feedback form-group-feedback-left">
					<div class="form-control-feedback">
						<i class="icon-pencil3"></i>
					</div>
					<input type="text" name="rank1" class="form-control datepicker readonlyjm" placeholder="Tanggal" autocomplete="off">
				</div>
			</div>
		</div>
		<div class="text-left offset-lg-2">                
			<button class="btn btn-sm btn-info result" id="kalkulasi">Filter <i class="icon-search4 ml-2"></i></button>
			<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
		</div>
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<tr class="table-active">
						<th width="1%">No</th>
						<th class="text-nowrap">Nama Jadwal<hr class="m-0">Priode</th>
						<th >Unit Kerja</th>
						<th >Status</th>
						<th width="1%">Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div id="modal_default" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Salin Data Jadwal <b id="judul_modal_salin">?</b></h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<?php echo form_open('mngsch/setsch_start/AjaxSaveSalin','id="formAjax"'); ?>
			<div class="modal-body">
				<input type="hidden" name="id">
				<div class="form-group row">
					<div class="col-lg-12">
						<div class="form-group-feedback form-group-feedback-left">
							<div class="form-control-feedback">
							<i class="icon-pencil3"></i>
							</div>
							<input type="text" name="nama" class="form-control" placeholder="Nama Jadwal" >
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-5">
						<div class="form-group-feedback form-group-feedback-left">
							<div class="form-control-feedback">
								<i class="icon-pencil3"></i>
							</div>
							<input type="text" name="rank1" class="form-control datepicker readonlyjm" placeholder="Tanggal awal" autocomplete="off">
						</div>
					</div>
					<div class="col-lg-1">
						<div class="form-group">
							<span>s/d</span>
						</div>
					</div>
					<div class="col-lg-5">
						<div class="form-group-feedback form-group-feedback-left">
							<div class="form-control-feedback">
								<i class="icon-pencil3"></i>
							</div>
							<input type="text" name="rank2" class="form-control datepicker readonlyjm" placeholder="Tanggal akhir" autocomplete="off">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger result" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-sm btn-info result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
         		 <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
			</div>

		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$(".datepicker").datepicker({
	    format: 'dd-mm-yyyy',
	    autoclose: true,
	    todayHighlight: true,
	  });
	
	$(document).on('click', '.confirm-salin', function(){
		$('input[name="id"]').val($(this).attr('data-id'));
		$('#modal_default').modal('show');
		$('#judul_modal_salin').html($(this).attr('data-name'));
	});
	var result  = $('.result');
	var spinner = $('#spinner');
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
				url : uri_dasar+'mngsch/setsch-start/indexJson',
				type:"post",
				"data": function ( data ) {	
					data.csrf_sikap_token_name= csrf_value;
					data.rank1  = $('[name="rank1"]').val();
					data.rank2  = $('[name="rank1"]').val();
				},
				beforeSend:function(){
					result.attr("disabled", true);
		      		spinner.show();
				},
				"dataSrc": function ( json ) {
	                //Make your callback here.
	                result.attr("disabled", false);
		          	spinner.hide();
		          	$('#kalkulasi').show();
	                return json.data;
	            } 
			},
			"columns": [
			{"data": "id", searchable:false},
			{"data": "sch_name", searchable:false},
			{"data": "dept_name", searchable:false},
			{"data": "status", searchable:false},
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
          $('td', row).eq(1).addClass('text-nowrap p-1');
          $('td', row).eq(2).addClass('p-1');
          $('td', row).eq(3).addClass('text-nowrap text-center');
          $('td', row).eq(4).addClass('text-nowrap text-center');
      },


  });

	 // Initialize
	 dt_componen();

	});

	function confirmAksi(id) {
		$.ajax({
			url: uri_dasar+'mngsch/setsch-start/AjaxDel',
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
	
	$('#kalkulasi').click(function() {
		result.attr("disabled", true);
		spinner.show();
		table.ajax.reload();
	})

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
						bx_alert_ok(res.message);
						table.ajax.reload();
					}else {
						bx_alert(res.message);
					}
					$('#modal_default').modal('hide');
					result.attr("disabled", false);
					spinner.hide();
					
				}
			});
			return false;
		});
</script>