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
					<input type="text" name="rank1" class="form-control datepicker" placeholder="dari tanggal" >
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
					<input type="text" name="rank2" class="form-control datepicker" placeholder="sampai tanggal" >
				</div>
			</div>
		</div>
		<input type="hidden" name="agama_id" value="0">
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
						<th width="1%">No</th>
						<th class="text-nowrap" width="1%">Tanggal</th>
						<th class="text-center">
						<i class="icon-spinner2 spinner spinner_tempat" style="display: none" ></i>
							<span id="zuhur">Tempat Sholat Zuhur</span></th>
						<th class="text-center">
						<i class="icon-spinner2 spinner spinner_tempat" style="display: none" ></i>
							<span id="ashar">Tempat Sholat Ashar </span></th>	
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">

$('[name="instansi"]').change(function() {
	DataPegawai();
})
$(".datepicker").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
});

$('[name="pegawai"]').change(function() {
	cek_agama()
})

$(document).ready(function(){
	DataPegawai();
});

$('#kalkulasi').click(function() {
	result.attr("disabled", true);
    spinner.show();
	table.ajax.reload();
})

function DataPegawai() {
	var instansi = $('[name="instansi"]').val();
	var result  = $('.result');
	var spinner = $('#spinner_pegawai');
	$.ajax({
		type: 'get',
		url: uri_dasar+'report/rtibadah/AjaxGet',
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
      		cek_agama();
		}
	});
	
}

function cek_agama() {
	var pegawai_id = $('[name="pegawai"]').val()
	var result  = $('.result');
	var spinner = $('.spinner_tempat');
	$.ajax({
		type: 'get',
		url: uri_dasar+'report/rtibadah/AjaxGet',
		data: {mod:'CekAgama',pegawai:pegawai_id},
		dataType : "json",
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
				if (res.data.agama_id != 1) {
					$('#zuhur').text('Kegiatan Ibadah');
					$('#sahar').text('Tempat Ibadah');
				}else {
					$('#zuhur').text('Tempat Sholat Zuhur');
					$('#sahar').text('Tempat Sholat Ashar');
				}
				$('[name="agama_id"]').val(res.data.agama_id);
			}else {
				bx_alert(res.message);
			}

			result.attr("disabled", false);
      		spinner.hide();
		}
	});
}

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
          url : uri_dasar+'report/rtibadah/ibadahJson',
          type:"post",
          "data": function ( data ) { 
                data.csrf_sikap_token_name= csrf_value;
                data.pegawai		  = $('[name="pegawai"]').val();
                data.rank1			  = $('[name="rank1"]').val();
                data.rank2		  	  = $('[name="rank2"]').val();
                data.agama_id		  = $('[name="agama_id"]').val();
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
          {"data": "tgl_ibadah_tabel", searchable:false},
          {"data": "tempat1", searchable:false},
          {"data": "tempat2", searchable:false},
      ],
      rowCallback: function(row, data, iDisplayIndex) {
          var info = this.fnPagingInfo();
          var page = info.iPage;
          var length = info.iLength;
          var index = page * length + (iDisplayIndex + 1);
          $('td:eq(0)', row).html(index);
      },
       createdRow: function(row, data, index) {
       		$('td', row).eq(1).addClass('text-nowrap');
        },


  });
   dt_componen();

});

$('#cetak').click(function() {
	var rank1 	= $('[name="rank1"]').val();
	var rank2 	= $('[name="rank2"]').val();
	var pegawai = $('[name="pegawai"]').val();
	if (rank1 && rank2) {
		newWindow = window.open(uri_dasar + 'report/rtibadah/cetak/'+rank1+'/'+rank2+'?pg='+pegawai,"open",'height=600,width=1000');
		if (window.focus) {newWindow.focus()}
			return false;
	}else{
		bx_alert('rentang waktu hurus diisi');
	}
	
})

</script>