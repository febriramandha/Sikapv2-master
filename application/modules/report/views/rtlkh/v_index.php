<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Laporan LKH</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="form-group row">
			<label class="col-form-label col-lg-2">Unit Kerja <span class="text-danger">*</span></label>
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
	        <label class="col-form-label col-lg-2">Pegawai<span class="text-danger">*</span>
	       	 	<i class="icon-spinner2 spinner" style="display: none" id="spinner_pegawai"></i>
	    	</label>
	        <div class="col-lg-10">
	          <div class="form-group">
	           <select class="form-control select-search" name="pegawai" id="pegawai"> 
	           		
	          </select> 
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
					<input type="text" name="rank1" class="form-control datepicker readonlyjm" placeholder="dari tanggal" >
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
					<input type="text" name="rank2" class="form-control datepicker readonlyjm" placeholder="sampai tanggal" >
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
						<th width="1%" >No</th>
						<th class="text-nowrap text-center" width="1%">Tanggal</th>
						<th class="text-nowrap text-center p-1" width="1%">Jam <hr class="m-0">(mulai - selesai)</th>
						<th class="text-nowrap text-center pl-md-3 pl-5 pr-md-3 pr-5" >Uraian Kegiatan</th>
						<th class="text-nowrap text-center pl-md-3 pl-5 pr-md-3 pr-5" >Hasil</th>
						<th width="1%" class="p-1">Status</th>
						<th width="1%" style="font-size: 80%;" class="normal_text p-1">Pejabat <br>pemeriksa</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
$('.readonlyjm').on('focus',function(){
    $(this).trigger('blur');
});

$('[name="instansi"]').change(function() {
	DataPegawai();
})
$(".datepicker").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
});

$(document).ready(function(){
	DataPegawai();
});

$('#kalkulasi').click(function() {
	result.attr("disabled", true);
    spinner.show();
	table.ajax.reload();
})

var result  = $('.result');
var spinner = $('#spinner');
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
          url : uri_dasar+'report/rtlkh/lkhJson',
          type:"post",
          "data": function ( data ) { 
                data.csrf_sikap_token_name= csrf_value;
                data.pegawai		  = $('[name="pegawai"]').val();
                data.rank1			  = $('[name="rank1"]').val();
                data.rank2		  	  = $('[name="rank2"]').val();
              },
           "dataSrc": function ( json ) {
                //Make your callback here.
                result.attr("disabled", false);
	          	spinner.hide();
                return json.data;
            }  
      },
      "columns": [
          {"data": "id", searchable:false},
          {"data": "tgl_lkh_tabel", searchable:false},
          {"data": "jam_mulai", searchable:false},
          {"data": "kegiatan", searchable:false},
          {"data": "hasil", searchable:false},
          {"data": "status_lkh", searchable:false},
          {"data": "ver", searchable:false},
      ],
      rowsGroup: [1],
      rowCallback: function(row, data, iDisplayIndex) {
          var info = this.fnPagingInfo();
          var page = info.iPage;
          var length = info.iLength;
          var index = page * length + (iDisplayIndex + 1);
          $('td:eq(0)', row).html(index);
      },
       createdRow: function(row, data, index) {
       		$('td', row).eq(1).addClass('text-nowrap p-2');
       		$('td', row).eq(2).addClass('text-center text-nowrap p-1');
			$('td', row).eq(3).addClass('normal_text p-2');
			$('td', row).eq(4).addClass('normal_text p-2');
			$('td', row).eq(5).addClass('text-center p-1');
			$('td', row).eq(6).addClass('text-center p-1');
         
        },


  });

   dt_componen();

});

function DataPegawai() {
	var instansi = $('[name="instansi"]').val();
	var result  = $('.result');
	var spinner = $('#spinner_pegawai');
	$.ajax({
		type: 'get',
		url: uri_dasar+'report/rtlkh/AjaxGet',
		data: {mod:'DataPegawai',instansi:instansi},
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

$('#cetak').click(function() {
		var rank1 	= $('[name="rank1"]').val();
		var rank2 	= $('[name="rank2"]').val();
		var pegawai = $('[name="pegawai"]').val();
		if (rank1 && rank2) {
			newWindow = window.open(uri_dasar + 'report/rtlkh/cetak/'+rank1+'/'+rank2+'?pg='+pegawai,"open",'height=600,width=1000');
			if (window.focus) {newWindow.focus()}
				return false;
		}else{
			bx_alert('rentang waktu hurus diisi');
		}
		
	})
</script>