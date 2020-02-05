<!-- Simple statistics -->
<div class="mb-3">
	<h6 class="mb-0 font-weight-semibold">
		Beranda
	</h6>
</div>
<div class="row">
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
				<h6 class="font-weight-semibold"> <i class="icon-alarm mr-3"></i>Jadwal Kerja</h6>
			</div>
			<div class="table-responsive m-0 naikturun">
				<table class="table text-nowrap table-bordered">
					<tr class="table-active text-center">
						<th class="py-0">Hari</th>
						<th class="py-0">Jam Masuk<hr class="m-0">(Mulai C/in - Akhir C/in)</th>
						<th class="py-0">Jam Pulang<hr class="m-0">(Mulai C/Out - Akhir C/Out)</th>
					</tr>
					<tr>
						<td>Senin</td>
						<td>07:30 (06:30 - 12:00)</td>
						<td>16:00 (12:01 - 23:59) </td>
					</tr>
				</table>
			</div>
			<a href="<?php echo base_url('app/article') ?>" class="list-group-item legitRipple">
				<i class="icon-arrow-right22 mr-3"></i>
				Tampilkan Semua 
			</a>
			
		</div>
	</div>
	
	
</div>
<!-- <div class="mb-3">
	<h6 class="mb-0 font-weight-semibold">
		Kabupaten Agam
	</h6>
</div> -->

<div class="row">
	<div class="col-lg-4 col-sm-6 d-flex">
		<div class="card card-body" >
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">OPD Kabupaten Agam</h6>
					<span class="text-muted">Total OPD <?php if ($opd_all) { echo $opd_all->count; } ?></span><br>
					<span class="text-muted">Total Pengguna <?php if ($user_opd_all) { echo $user_opd_all->count; } ?></span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-office icon-2x text-purple-300"></i>
				</div>
			</div>
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">Puskesmas</h6>
					<span class="text-muted">Total Puskesmas <?php if ($puskesmas_all) { echo $puskesmas_all->count; } ?></span><br>
					<span class="text-muted">Total Pengguna 10</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-home8 icon-2x text-warning"></i>
				</div>
			</div>
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">Sekolah</h6>
					<span class="text-muted">Total Sekolah <?php if ($sekolah_all) { echo $sekolah_all->count; } ?></span><br>
					<span class="text-muted">Total Pengguna 10</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-stack2 icon-2x text-success"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-8 col-sm-6 d-flex">
		<div class="card card-body" >
			<div class="media" id="pengguna">
			</div>
		</div>
	</div>
	
</div>

<div class="mb-3">
	<h6 class="mb-0 font-weight-semibold">
		Statistik Kehadiran
	</h6>
</div>

<div class="row">
	<div class="col-lg-4 col-sm-6 d-flex">
		<div class="card card-body" >
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">Kehadiran</h6>
					<span class="text-muted">Total Hadir</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-history icon-2x text-info-300"></i>
				</div>
			</div>
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">Terlambat</h6>
					<span class="text-muted">Total Kecamatan 20</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-file-presentation icon-2x text-warning"></i>
				</div>
			</div>
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">Pulang Cepat</h6>
					<span class="text-muted">Total Puskesmas 20</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-file-presentation icon-2x"></i>
				</div>
			</div>
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">TK</h6>
					<span class="text-muted">Total Sekolah 20</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-copy4  icon-2x text-danger"></i>
				</div>
			</div>
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">Dinas Luar</h6>
					<span class="text-muted">Total Sekolah 20</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-paste2 icon-2x text-success"></i>
				</div>
			</div>
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">Absen Keterangan</h6>
					<span class="text-muted">Total 20</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-copy3 icon-2x text-orange-300"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-8 col-sm-6 d-flex">
		<div class="card card-body" >
			<div class="media" id="kehadiran">
			</div>
		</div>
	</div>
	
</div>

<div class="mb-3">
	<h6 class="mb-0 font-weight-semibold">
		Statistik Laporan Kerja Harian
	</h6>
</div>

<div class="row">
	<div class="col-lg-4 col-sm-6 d-flex">
		<div class="card card-body" >
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">Semua Laporan</h6>
					<span class="text-muted">Total: </span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-archive icon-2x text-purple-300"></i>
				</div>
			</div>
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">Terverifikasi Atasan</h6>
					<span class="text-muted">Total:</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-file-check icon-2x text-success"></i>
				</div>
			</div>
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">Terverifikasi Otomatis</h6>
					<span class="text-muted">Total:</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-file-check2 icon-2x text-warning"></i>
				</div>
			</div>
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">Menunggu Verifikasi</h6>
					<span class="text-muted">Total:</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-paste2 icon-2x text-info"></i>
				</div>
			</div>
			<div class="media">
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">Laporan Ditolah</h6>
					<span class="text-muted">Total:</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-file-minus2 icon-2x text-danger"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-8 col-sm-6 d-flex">
		<div class="card card-body" >
			<div class="media" id="kehadiran">
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {

		$(document).ready(function () {

        // Build the chart
        $('#pengguna').highcharts({
        	chart: {
        		plotBackgroundColor: null,
        		plotBorderWidth: null,
        		plotShadow: false,
        		type: 'pie'
        	},
        	title: {
        		text: 'Grafik Pengguna Kabupaten Agam'
        	},
        	tooltip: {
        		pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        	},
        	plotOptions: {
        		pie: {
        			allowPointSelect: true,
        			cursor: 'pointer',
        			dataLabels: {
        				enabled: false
        			},
        			showInLegend: true
        		}
        	},
        	series: [{
        		name: "Persentase",
        		colorByPoint: true,
        		data: [
        		
        		{
        			name: "OPD",
        			y: 179                }, 
        			
        			{
        				name: "Puskesmas",
        				y: 152                }, 
        				
        				{
        					name: "Sekolah",
        					y: 171                }, 
        					
        					]
        				}]
        			});
    });
	});

</script>

