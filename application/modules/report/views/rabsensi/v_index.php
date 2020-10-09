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
	<?php echo form_open('report/rabsensi/cetak','class="form-horizontal" target="popup" id="formAjax"'); ?>
	<div class="card-body">
		<div class="form-group row">
			<label class="col-form-label col-lg-2">Unit Kerja <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<select class="form-control select-search result" name="instansi"> 
						<?php foreach ($instansi as $row) { ?>
							<option value="<?php echo encrypt_url($row->id,'instansi') ?>"><?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?></option>
						<?php } ?>
					</select> 
				</div>
			</div>
		</div>
		<div class="form-group row">
	        <label class="col-form-label col-lg-2">Kategori Pengguna <span class="text-danger">*</span></label>
	        <div class="col-lg-10">
	          <div class="form-group">
	           <select class="form-control select-nosearch result" name="kategori" >  
	            <option value="0">Semua..</option> 
	            <option value="1">PNS</option>
	            <option value="2">NON PNS</option>
	          </select> 
	        </div>
	      </div>
	    </div>
	    <div class="form-group row" id="tpp">
	      <label class="col-form-label col-lg-2">Ketagori Lainnya </label>
	      <div class="col-lg-10">
	          <label class="pure-material-checkbox mt-2"> 
		          <input type="checkbox" class="result"  name="tpp" /> <span>Pernerima TPP</span>
		        </label>
	      </div>
	    </div>
	    <div class="form-group row">
	        <label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span> 
	        	<i class="icon-spinner2 spinner" style="display: none" id="spinner_pegawai"></i>
	        </label>
	        <div class="col-lg-10">
	          <div class="form-group">
	          	<div id="pegawai">
						<select class="form-control multiselect-clickable-groups" name="pegawai[]" multiple="multiple" data-fouc>
						</select>						
				</div>
	        </div>
	      </div>
	    </div>
		<div class="form-group row">
			<label class="col-form-label col-lg-2"> Rentang Waktu <span class="text-danger">*</span></label>
			<div class="col-lg-4">
				<div class="form-group-feedback form-group-feedback-left">
					<div class="form-control-feedback">
						<i class="icon-pencil3"></i>
					</div>
					<input type="text" name="rank1" class="form-control datepicker readonlyjm" placeholder="Tanggal awal" >
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
					<input type="text" name="rank2" class="form-control datepicker readonlyjm" placeholder="Tanggal akhir" >
				</div>
			</div>
		</div>
		<div class="text-left offset-lg-2">                
			<span class="btn btn-sm btn-info result" id="kalkulasi">Tampilkan <i class="icon-search4 ml-2"></i></span>
			<button type="submit" class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1 result" id="cetak">
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
						<th class="text-nowrap" rowspan="2">Nama<hr class="m-0">NIP</th>
						<th class="text-nowrap" rowspan="2">Tanggal</th>
						<th class="text-nowrap" colspan="3">Masuk</th>
						<th class="text-nowrap" colspan="3">Pulang</th>
						<th rowspan="2">DL</th>
						<th rowspan="2">Cuti</th>
						<th rowspan="2" width="1%">Ket</th>
					</tr>
					<tr class="table-active">
						<th class="px-1" >Jam Masuk</th>
						<th class="px-1">Masuk Kerja</th>
						<th class="px-1">Terlambat</th>

						<th class="px-1">Jam Pulang</th>
						<th class="px-1">Pulang Kerja</th>
						<th class="px-1">Pulang Cepat</th>
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

$('.readonlyjm').on('focus',function(){
    $(this).trigger('blur');
});

$('.multiselect-clickable-groups').multiselect({
    includeSelectAllOption: true,
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
    placeholder: 'Pilih Pegawai',
});



$(document).ready(function(){
	DataPegawai();
});

$('[name="instansi"]').change(function() {
	DataPegawai();
})

$('[name="kategori"]').change(function() {
	DataPegawai();
})

$('[name="tpp"]').change(function() {
	DataPegawai();
})

$('#kalkulasi').click(function() {
	result.attr("disabled", true);
    spinner.show();
    $('#kalkulasi').hide();
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
				url : uri_dasar+'report/rabsensi/indexJson',
				type:"post",
				"data": function ( data ) {	
					data.csrf_sikap_token_name= csrf_value;
					data.pegawai= $('[name="pegawai[]"]').val();
					data.rank1  = $('[name="rank1"]').val();
					data.rank2  = $('[name="rank2"]').val();
				},
				beforeSend:function(){
					result.attr("disabled", true);
					$('#kalkulasi').hide();
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
			rowsGroup: [1],
			"columns": [
			{"data": "id", searchable:false},
			{"data": "nama_nip", searchable:false},
			{"data": "tanggal", searchable:false},
			{"data": "start_time_tabel", searchable:false},
			{"data": "jam_masuk_tabel", searchable:false},
			{"data": "terlambat_tabel", searchable:false},
			{"data": "end_time_tabel", searchable:false},
			{"data": "jam_pulang_tabel", searchable:false},
			{"data": "pulang_cepat_tabel", searchable:false},
			{"data": "dinas_luar_tabel", searchable:false},
			{"data": "cuti", searchable:false},
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
	     		 $('td', row).eq(2).addClass('text-nowrap p-1 text-center');
	     		 $('td', row).eq(3).addClass('text-nowrap p-2 text-center');
	     		 $('td', row).eq(4).addClass('text-nowrap p-2 text-center');
	     		 $('td', row).eq(5).addClass('text-nowrap p-2 text-center');
	     		 $('td', row).eq(6).addClass('text-nowrap p-2 text-center');
	     		 $('td', row).eq(7).addClass('text-nowrap p-2 text-center');
	     		 $('td', row).eq(8).addClass('text-nowrap p-2 text-center');
	     		 $('td', row).eq(9).addClass('text-nowrap p-2 text-center');
	     		 $('td', row).eq(10).addClass('text-nowrap p-2 text-center');
	     		 $('td', row).eq(11).addClass('text-nowrap p-2 text-center');
	  },
	});
 // Initialize
 dt_componen();

});


function DataPegawai() {
	var dept_id = $('[name="instansi"]').val();
	var pns 	= $('[name="kategori"]').val();
	var tpp 	= $('[name="tpp"]').is(':checked');
	var result  = $('.result');
	var spinner = $('#spinner_pegawai');
	$.ajax({
		type: 'get',
		url: uri_dasar+'report/rabsensi/AjaxGet',
		data: {mod:'DataPegawai',id:dept_id,pns:pns,tpp:tpp},
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
			$('#pegawai').html(res);
			result.attr("disabled", false);
      		spinner.hide();
		}
	});
	
}

 
var result  = $('.result');
var spinner = $('#spinner');
$('#cetak').click(function() {
			// newWindow = window.open(uri_dasar + 'report/rabsensi/cetak/'+rank1+'/'+rank2+'/?in='+instansi,"open",'height=600,width=800');
			// if (window.focus) {newWindow.focus()}
			// 	return false;
			window.open('about:blank','popup','width=1000,height=600')
			$('#formID').submit();
		
		
})


</script>

