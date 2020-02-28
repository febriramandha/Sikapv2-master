<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Data Ibadah</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>

	<div class="card-body">
		<div class="text-left mb-2" >                 
			<a href="<?php echo base_url('datalkh/worship/add') ?>" class="btn btn-sm btn-info"><i class="icon-pen-plus mr-1"></i> Tambah Baru</a>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-lg-2"> Rentang Waktu <span class="text-danger">*</span></label>
			<div class="col-lg-4">
				<div class="form-group-feedback form-group-feedback-left">
					<div class="form-control-feedback">
						<i class="icon-pencil3"></i>
					</div>
					<input type="text" name="rank1" class="form-control datepicker readonlyjm" placeholder="tanggal mulai" autocomplete="off">
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
					<input type="text" name="rank2" class="form-control datepicker readonlyjm" placeholder="tanggal berakhir" autocomplete="off">
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
						<th class="text-nowrap" rowspan="2" width="1%">Tanggal</th>
						<th class="text-nowrap text-center" colspan="2">Tempat Sholat Berjamaah</th>
						<th rowspan="2" width="1%">Aksi</th>
					</tr>
					<tr class="table-active">
						<th class="px-1 text-center" >Zuhur</th>
						<th class="px-1 text-center">Ashar</th>
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
          url : uri_dasar+'datalkh/worship/indexJson',
          type:"post",
          "data": function ( data ) { 
                data.csrf_sikap_token_name= csrf_value;
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
          {"data": "tgl_ibadah_tabel", searchable:false},
          {"data": "t_zuhur", searchable:false},
          {"data": "t_ashar", searchable:false},
          {"data": "action", searchable:false},
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
       		$('td', row).eq(1).addClass('text-nowrap');
       		$('td', row).eq(4).addClass('text-nowrap text-center');

         
        },


  });

   // Initialize
   dt_componen();

});

	$('#kalkulasi').click(function() {
		result.attr("disabled", true);
        spinner.show();
		table.ajax.reload();
	})

 	function confirmAksi(id) {
		$.ajax({
			type: 'get',
			url: uri_dasar+'datalkh/worship/AjaxDel',
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

	$('#cetak').click(function() {
		var rank1 = $('[name="rank1"]').val();
		var rank2 = $('[name="rank2"]').val();
		if (rank1 && rank2) {
			newWindow = window.open(uri_dasar + 'datalkh/worship/cetak/'+rank1+'/'+rank2,"open",'height=600,width=1000');
			if (window.focus) {newWindow.focus()}
				return false;
		}else{
			bx_alert('rentang waktu hurus diisi');
		}
		
	})
</script>