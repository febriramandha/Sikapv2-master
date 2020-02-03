<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Laporan Kehadiran</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="form-group row">
			<label class="col-form-label col-lg-2">Instansi <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<select class="form-control select-search" name="instansi"> 
						<?php foreach ($instansi as $row) { ?>
							<option value="<?php echo encrypt_url($row->id,'instansi') ?>"><?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?></option>
						<?php } ?>
					</select> 
				</div>
			</div>
		</div>
		<div class="form-group row">
	        <label class="col-form-label col-lg-2">Tahun<span class="text-danger">*</span></label>
	        <div class="col-lg-10">
	          <div class="form-group">
	           <select class="form-control select-nosearch" name="tahun" > 
	           <?php foreach ($shcupacara_tahun as $row) {  ?> 
	            	<option value="<?php echo $row->tahun ?>"><?php echo $row->tahun ?></option> 
	          	<?php } ?>
	          </select> 
	        </div>
	      </div>
	    </div>
		<div class="form-group row">
	        <label class="col-form-label col-lg-2">Jadwal Upacara<span class="text-danger">*</span>
	        		<i class="icon-spinner2 spinner" style="display: none" id="spinner_jadwal"></i>
	        </label>
	        <div class="col-lg-10">
	          <div class="form-group">
	           <select class="form-control select-nosearch" name="jadwal" id="jadwal">  
	            
	          </select> 
	        </div>
	      </div>
	    </div>
	    <div class="text-left offset-lg-2" >                
			<button type="submit" class="btn btn-sm btn-info result" id="kalkulasi">Kalkulasi <i class="icon-search4 ml-2"></i></button>
			<button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
				<span><i class="icon-printer mr-2"></i> Cetak</span>
			</button> 
			<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
		</div>	
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<tr class="table-active">
						<th width="1%" rowspan="2">No</th>
						<th class="text-nowrap" rowspan="2">Nama</th>
						<th class="text-nowrap" rowspan="2">NIP</th>
						<th class="text-nowrap" rowspan="2">Pangkat</th>
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

$('[name="tahun"]').change(function() {
	jadwalUpacara();
})

$('#kalkulasi').click(function() {
	table.ajax.reload();
})

$(document).ready(function(){
	jadwalUpacara();
});

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
				url : uri_dasar+'report/rupacara/indexJson',
				type:"post",
				"data": function ( data ) {	
					data.csrf_sikap_token_name= csrf_value;
					data.tahun   	= $('[name="tahun"]').val();
					data.jadwal  	= $('[name="jadwal"]').val();
					data.instansi  	= $('[name="instansi"]').val();
				},
			},
			"columns": [
			{"data": "id", searchable:false},
			{"data": "nama", searchable:false},
			{"data": "nip", searchable:false},
			{"data": "pangkat", searchable:false},
			{"data": "cek1", searchable:false},
			{"data": "cek2", searchable:false},
			{"data": "cek3", searchable:false},
			{"data": "ket", searchable:false},
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

function jadwalUpacara() {
	var tahun = $('[name="tahun"]').val();
	var result  = $('.result');
	var spinner = $('#spinner_jadwal');
	$.ajax({
		type: 'get',
		url: uri_dasar+'report/rupacara/AjaxGet',
		data: {mod:'jadwalUpacara',tahun:tahun},
		dataType : "html",
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
			$('#jadwal').html(res);
			result.attr("disabled", false);
      		spinner.hide();
		}
	});
	
}

 $('#cetak').click(function() {
		newWindow = window.open(uri_dasar + 'report/rupacara/cetak/'+$('[name="instansi"]').val()+'/'+$('[name="jadwal"]').val(),"open",'height=600,width=800');
		if (window.focus) {newWindow.focus()}
			return false;
	})

</script>