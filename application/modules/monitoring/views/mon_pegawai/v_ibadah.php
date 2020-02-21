<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Laporan Ibadah</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>
	<div class="card-body">
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
			<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
		</div>	
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<?php 
						if ($agama_id != 1) {
							$t1 = "Kegiatan Ibadah";
							$t2 = "Tempat Ibadah";
						}else {
							$t1 = "Tempat Sholat Zuhur";
							$t2 = "Tempat Sholat Ashar";
						}

					 ?>

					<tr class="table-active">
						<th width="1%">No</th>
						<th class="text-nowrap" width="1%">Tanggal</th>
						<th class="text-center"><?php echo $t1 ?></th>
						<th class="text-center"><?php echo $t2 ?></th>	
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<input type="hidden" name="agama_id" value="<?php echo $agama_id ?>">
<script type="text/javascript">
 $('.readonlyjm').on('focus',function(){
    $(this).trigger('blur');
});

$(".datepicker").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
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
          url : uri_dasar+'monitoring/mon-pegawai/GetJson/<?= $this->uri->segment(4) ?>?p=ibadah',
          type:"post",
          "data": function ( data ) { 
                data.csrf_sikap_token_name= csrf_value;
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

</script>