<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Data Kehadiran Upacara</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>

	<div class="card-body">
		<?php echo form_open('absen/data-upacara/cetak','class="form-horizontal" target="popup" id="formAjax"'); ?>
		<div class="form-group row">
			<label class="col-form-label col-lg-2"> Rentang Waktu <span class="text-danger">*</span></label>
			<div class="col-lg-4">
				<div class="form-group-feedback form-group-feedback-left">
					<div class="form-control-feedback">
						<i class="icon-pencil3"></i>
					</div>
					<input type="text" name="rank1" class="form-control datepicker" placeholder="tanggal mulai" >
				</div>
			</div>
			<div class="col-lg-1">
				<div class="form-group">
					<span>s/d</span>
				</div>
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
		<div class="text-left offset-lg-2" >                
			<span type="submit" class="btn btn-sm btn-info result" id="kalkulasi">Kalkulasi <i class="icon-search4 ml-2"></i></span>
			<button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
				<span><i class="icon-printer mr-2"></i> Cetak</span>
			</button> 
			<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
		</div>	
		<?php echo form_close() ?>	
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<tr class="table-active">
						<th width="1%" rowspan="2">No</th>
						<th class="text-nowrap" rowspan="2">Berita Acara</th>
						<th class="text-nowrap" rowspan="2">Tanggal (Jam)</th>
						<th class="text-nowrap text-center" colspan="3">Absen</th>
						<th rowspan="2" width="1%">Ket</th>
					</tr>
					<tr class="table-active">
						<th  width="1%" >Hadir (H)</th>
						<th  width="1%" >Tidak Hadir (A)</th>
						<th  width="1%" >Cuti (C)</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
 $(".datepicker").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
  });

$('#kalkulasi').click(function() {
	table.ajax.reload();
})

$(document).ready(function(){
		table = $('#datatable').DataTable({ 
			processing: true, 
			serverSide: true, 
			"ordering": false,
			"searching": false,
			language: {
				search: '<span></span> _INPUT_',
				searchPlaceholder: 'Cari...',
				processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
			},  
			"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
			ajax: {
				url : uri_dasar+'absen/data-upacara/indexJson',
				type:"post",
				"data": function ( data ) {	
					data.csrf_sikap_token_name= csrf_value;
					data.rank1   	= $('[name="rank1"]').val();
					data.rank2  	= $('[name="rank2"]').val();
				},
			},
			"columns": [
			{"data": "id", searchable:false},
			{"data": "ket", searchable:false},
			{"data": "tanggal", searchable:false},
			{"data": "cek1", searchable:false},
			{"data": "cek2", searchable:false},
			{"data": "cek3", searchable:false},
			{"data": "keterangan", searchable:false},
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
	  },
	});
 // Initialize
 dt_componen();

});

 $('#cetak').click(function() {
		window.open('about:blank','popup','width=1000,height=600')
		$('#formID').submit();	
})
</script>	