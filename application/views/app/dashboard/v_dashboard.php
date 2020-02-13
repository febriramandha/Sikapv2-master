<!-- Simple statistics -->
<div class="mb-3">
	<h6 class="mb-0 font-weight-semibold">
		Beranda
	</h6>
</div>
<div class="row">
	<?php if ($this->session->userdata('tpp_level') == 1 || $this->session->userdata('tpp_level') == 2 || $this->session->userdata('tpp_level') == 4 || $this->session->userdata('tpp_level') == 5) {
 	?>
	<div class="col-lg-12">
		<div class="row">
			<div class="col-sm-6 col-xl-3">
				<div class="card card-body">
					<div class="media">
						<div class="mr-3 align-self-center">
							<i class="icon-users2 icon-3x text-info"></i>
						</div>

						<div class="media-body text-right">
							<h3 class="font-weight-semibold mb-0"><?php if ($user_all) { echo $user_all->count; } ?></h3>
							<span class="text-uppercase font-size-sm text-muted">total pengguna</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-xl-3">
				<div class="card card-body">
					<div class="media">
						<div class="mr-3 align-self-center">
							<i class="icon-home  icon-3x"></i>
						</div>

						<div class="media-body text-right">
							<h3 class="font-weight-semibold mb-0"><?php if ($instansi_all) { echo $instansi_all->count; } ?></h3>
							<span class="text-uppercase font-size-sm text-muted">total unit kerja</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-xl-3">
				<div class="card card-body">
					<div class="media">
						<div class="mr-3 align-self-center">
							<i class="icon-users4 icon-2x text-success-400"></i>
						</div>

						<div class="media-body text-right">
							<h3 class="font-weight-semibold mb-0"><?php if ($user_aktif_all) { echo $user_aktif_all->count; } ?></h3>
							<span class="text-uppercase font-size-sm text-muted">pengguna aktif</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-xl-3">
				<div class="card card-body">
					<div class="media">
						<div class="mr-3 align-self-center">
							<i class="icon-user-tie icon-3x text-warning-400"></i>
						</div>

						<div class="media-body text-right">
							<h3 class="font-weight-semibold mb-0"><?php if ($user_admin_all) { echo $user_admin_all->count; } ?></h3>
							<span class="text-uppercase font-size-sm text-muted">total admin</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="col-lg-6 d-flex">
		<div class="card col-lg-12 " style="height: 203px;">
			<div class="card-header bg-white header-elements-sm-inline pb-0">
				<h6 class="font-weight-semibold"> <i class="icon-bell2 mr-3"></i>Informasi/Pengumuman</h6>
			</div>
			<div class="table-responsive m-0 naikturun">
				<table class="table text-nowrap">
					<?php
					foreach ($pos->result() as $row ) { ?>
						<thead>
							<tr>
								<td>
									<a href="#" class="text-default font-weight-semibold"><?php echo $row->title  ?> <span class="badge badge-info"><?php echo $row->kategori ?></span></a>
									<div class="text-muted font-size-sm">
										<span class="font-weight-semibold"><i class="icon-calendar3 mr-1"></i><?php echo format_waktu_ind($row->created_at)  ?></span><br>
										<span class="badge badge-mark border-blue mr-1"></span>
										<?php echo $row->description  ?>
									</div>
									<span class="text-muted"><?php echo $row->content  ?></span>
								</td>
							</tr>
						</thead>
					<?php } ?>
				</table>
			</div>
			<a href="<?php echo base_url('app/article') ?>" class="list-group-item legitRipple">
				<i class="icon-arrow-right22 mr-3"></i>
				Tampilkan Semua (<?php echo $pos->num_rows() ?>)
			</a>
			
		</div>
	</div>

	<div class="col-lg-6 d-flex">
		<div class="card col-lg-12 " style="height: 203px;">
			<div class="card-header bg-white header-elements-sm-inline pb-0">
				<h6 class="font-weight-semibold"> <i class="icon-alarm mr-3"></i>Jadwal Kerja <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	</h6>
			</div>
			<div class="table-responsive m-0">
				<table class="table text-nowrap" id="datatable_jadwal">
					<thead>
						<tr class="table-active text-center">
							<th width="1%">No</th>
							<th class="py-0">H/Tanggal</th>
							<th class="py-0">Jam Masuk<hr class="m-0">(Mulai C/in - Akhir C/in)</th>
							<th class="py-0">Jam Pulang<hr class="m-0">(Mulai C/Out - Akhir C/Out)</th>
						</tr>
					</thead>
					<tbody>
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
var spinner = $('#spinner_jadwal');	
	$(document).ready(function(){
		table = $('#datatable_jadwal').DataTable({ 
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
				url : uri_dasar+'app/dashboard/jadwalJson',
				type:"post",
				"data": function ( data ) {	
					data.csrf_sikap_token_name= csrf_value;
				},
				beforeSend:function(){
		      		spinner.show();
				},
				"dataSrc": function ( json ) {
	                //Make your callback here.
		          	spinner.hide();
	                return json.data;
	            } 
			},
			"columns": [
			{"data": "id", searchable:false},
			{"data": "tanggal", searchable:false},
			{"data": "start_time_tabel", searchable:false},
			{"data": "end_time_tabel", searchable:false},
			
			],
			rowCallback: function(row, data, iDisplayIndex) {
				var info = this.fnPagingInfo();
				var page = info.iPage;
				var length = info.iLength;
				var index = page * length + (iDisplayIndex + 1);
				$('td:eq(0)', row).html(index);

			},
			createdRow: function(row, data, index) {
				$('td', row).eq(1).addClass('py-0');
				$('td', row).eq(2).addClass('py-0');
				$('td', row).eq(3).addClass('py-0');
	  },	
	});
	 // Initialize
	 dt_componen();

});


</script>

<?php if ($this->session->userdata('tpp_level') == 1 || $this->session->userdata('tpp_level') == 2 || $this->session->userdata('tpp_level') == 4 || $this->session->userdata('tpp_level') == 5) {
 ?>
<div class="card card-body" >
	<div class="row">
		<div class="col-lg-12">
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
		</div>
		<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
		<div id="grafik" class="col-lg-12">

		</div>
		
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	LoadGrafik();
});

$('[name="instansi"]').change(function() {
	LoadGrafik();
})

function LoadGrafik() {
	var instansi = $('[name="instansi"]').val();
	var result  = $('.result');
	var spinner = $('#spinner');
	$.ajax({
		type: 'get',
		url: uri_dasar+'reportgk/grafik-pegawai/AjaxGet',
		data: {mod:'Grafik',instansi:instansi},
		dataType : "html",
		error:function(){
			result.attr("disabled", false);
       		spinner.hide();
			// bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
			$('#grafik').unblock();
		},
		beforeSend:function(){
			result.attr("disabled", true);
      		spinner.show();
      		load_dt('#grafik');
		},
		success: function(res) {
			$('#grafik').html(res);
			result.attr("disabled", false);
      		spinner.hide();
      		$('#grafik').unblock();
		}
	});
}

</script>

<?php } ?>

<div class="card card-body" >
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group row">
				<label class="col-form-label col-lg-2"> Rentang Waktu <span class="text-danger">*</span></label>
				<div class="col-lg-4">
					<div class="form-group-feedback form-group-feedback-left">
						<div class="form-group">
				           <select class="form-control select-nosearch result" name="tahun" >  
				            <option disabled="">Pilih Tahun..</option> 
				             <?php foreach ($laporan_tahun as $row) {  ?> 
				            	<option value="<?php echo $row->tahun ?>" <?php if ($row->tahun == date('Y')) { echo "selected";} ?>><?php echo $row->tahun ?></option> 
				          	<?php } ?>
				          </select> 
				        </div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="form-group-feedback form-group-feedback-left">
						<div class="form-group">
				           <select class="form-control select-nosearch result" name="bulan" >  
				            <option disabled="">Pilih Bulan..</option> 
				            <?php for ($i=1; $i < 13; $i++) { ?>
				            	<option value="<?php echo $i ?>" <?php if ($i == date('m')) { echo "selected";} ?>><?php echo _bulan($i) ?></option>
				        	<?php } ?>
				          </select> 
				        </div>
					</div>
				</div>
			</div>
			<div class="text-left offset-lg-2">                
				<button class="btn btn-sm btn-info result" id="kalkulasi">Kalkulasi <i class="icon-search4 ml-2"></i></button>
				<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
			</div>
		</div>
		<div id="grafik_lkh" class="col-lg-12">

		</div>
	</div>
</div>

<script type="text/javascript">

$(document).ready(function(){
	LoadGrafikLkh();
});


$('#kalkulasi').click(function() {
	LoadGrafikLkh();
})


function LoadGrafikLkh() {
	var tahun  = $('[name="tahun"]').val();
	var bulan  = $('[name="bulan"]').val();
	var result  = $('.result');
	var spinner = $('#spinner');
	$.ajax({
		type: 'get',
		url: uri_dasar+'app/dashboard/AjaxGet',
		data: {mod:'Grafik',tahun:tahun, bulan:bulan},
		dataType : "html",
		error:function(){
			result.attr("disabled", false);
       		spinner.hide();
			// bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
			$('#grafik_lkh').unblock();
		},
		beforeSend:function(){
			result.attr("disabled", true);
      		spinner.show();
      		load_dt('#grafik_lkh');
		},
		success: function(res) {
			$('#grafik_lkh').html(res);
			result.attr("disabled", false);
      		spinner.hide();
      		$('#grafik_lkh').unblock();
		}
	});
}
</script>
