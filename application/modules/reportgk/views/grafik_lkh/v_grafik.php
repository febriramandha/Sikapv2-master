<?php 
	$jum_hari_kerja_rekap_lkh = array(0);
	// $jum_data_kerja_rekap_lkh = array(0);
	$total_jum_lkh_rekap 	  = array(0);
	$jum_data_kerja_rekap_terverifikasi = array(0);
	$jum_hari_kerja_rekap_lkh_menunggu = array(0);
	$jum_hari_kerja_rekap_lkh_ditolak = array(0);
	$jum_hari_kerja_rekap_lkh_terverikasi_atasan = array(0);
	$jum_hari_kerja_rekap_lkh_terverikasi_otomatis = array(0);
	$user = array();
	foreach ($pegawai_lkh as $row) {
		$jum_hari_kerja_rekap_lkh[] = jum_hari_kerja_rekap_lkh($row->json_jadwal_lkh);

		// $jum_data_kerja_rekap_lkh_terverifikasi = jum_data_kerja_rekap_lkh_field($row->json_jadwal_lkh,'f9');
		// $jum_data_kerja_rekap_lkh_menunggu 		= jum_data_kerja_rekap_lkh_field($row->json_jadwal_lkh,'f10');
		// $jum_data_kerja_rekap_lkh_ditolak 		= jum_data_kerja_rekap_lkh_field($row->json_jadwal_lkh,'f11');

		$total_jum_lkh_rekap[] 		= total_jum_lkh_rekap($row->json_jadwal_lkh); 
		$jum_data_kerja_rekap_terverifikasi[] 	= total_kerja_rekap_lkh_field($row->json_jadwal_lkh,'f9');
		$jum_hari_kerja_rekap_lkh_menunggu[] 	= total_kerja_rekap_lkh_field($row->json_jadwal_lkh,'f10'); 
		$jum_hari_kerja_rekap_lkh_ditolak[] 	= total_kerja_rekap_lkh_field($row->json_jadwal_lkh,'f11');
		$jum_hari_kerja_rekap_lkh_terverikasi_atasan[] = total_kerja_rekap_lkh_field($row->json_jadwal_lkh,'f12');
		$jum_hari_kerja_rekap_lkh_terverikasi_otomatis[] = total_kerja_rekap_lkh_field($row->json_jadwal_lkh,'f13'); 
		$user[] = 1;
     }

	$jum_hari_kerja_rekap_lkh_sum = array_sum($jum_hari_kerja_rekap_lkh);
	$total_jum_lkh_rekap_sum 	  = array_sum($total_jum_lkh_rekap);
	$jum_data_kerja_rekap_terverifikasi_sum =array_sum($jum_data_kerja_rekap_terverifikasi);
	$jum_hari_kerja_rekap_lkh_menunggu_sum = array_sum($jum_hari_kerja_rekap_lkh_menunggu);
	$jum_hari_kerja_rekap_lkh_ditolak_sum = array_sum($jum_hari_kerja_rekap_lkh_ditolak);
	$jum_hari_kerja_rekap_lkh_terverikasi_atasan_sum = array_sum($jum_hari_kerja_rekap_lkh_terverikasi_atasan);
	$jum_hari_kerja_rekap_lkh_terverikasi_otomatis_sum = array_sum($jum_hari_kerja_rekap_lkh_terverikasi_otomatis);

	$user_tot = count($user);
	$jum_hari_kerja_rekap_lkh_round 				= round($jum_hari_kerja_rekap_lkh_sum/$user_tot,0);
	$total_jum_lkh_rekap_round 	  					= round($total_jum_lkh_rekap_sum/$user_tot,0);
	$jum_data_kerja_rekap_terverifikasi_round 	  	= round($jum_data_kerja_rekap_terverifikasi_sum/$user_tot,0);
	$jum_hari_kerja_rekap_lkh_menunggu_round 	  	= round($jum_hari_kerja_rekap_lkh_menunggu_sum/$user_tot,0);
	$jum_hari_kerja_rekap_lkh_ditolak_round 	  	= round($jum_hari_kerja_rekap_lkh_ditolak_sum/$user_tot,0);
	$jum_hari_kerja_rekap_lkh_terverikasi_atasan_round 	  	= round($jum_hari_kerja_rekap_lkh_terverikasi_atasan_sum/$user_tot,0);
	$jum_hari_kerja_rekap_lkh_terverikasi_otomatis_round 	  	= round($jum_hari_kerja_rekap_lkh_terverikasi_otomatis_sum/$user_tot,0);

 ?>

<div class="row">
	<div class="col-lg-4">
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover">
				<thead>
					<tr>
						<th></th>
						<th width="1%" ></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><i class="icon-file-presentation2 mr-1"></i> Total Pengguna</td>
						<td><?php echo $user_tot ?></td>
					</tr>
					<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Jumlah Hari Kerja</td>
							<td><?php echo $jum_hari_kerja_rekap_lkh_round ?></td>
					</tr>
					<tr>
						<td><i class="icon-file-presentation2 mr-1"></i>Total Terverifikasi</td>
						<td><?php echo $jum_data_kerja_rekap_terverifikasi_round ?></td>
					</tr>
					<tr>
						<td><i class="icon-file-presentation2 mr-1"></i>Total Verifikasi Atasan</td>
						<td><?php echo $jum_hari_kerja_rekap_lkh_terverikasi_atasan_round ?></td>
					</tr>
					<tr>
						<td><i class="icon-file-presentation2 mr-1"></i>Total Verifikasi Otomatis</td>
						<td><?php echo $jum_hari_kerja_rekap_lkh_terverikasi_otomatis_round ?></td>
					</tr>
					<tr>
						<td><i class="icon-file-presentation2 mr-1"></i>Total Menunggu Verifikasi</td>
						<td><?php echo $jum_hari_kerja_rekap_lkh_menunggu_round ?></td>
					</tr>
					<tr>
						<td><i class="icon-file-presentation2 mr-1"></i>Total Laporan ditolak</td>
						<td><?php echo $jum_hari_kerja_rekap_lkh_ditolak_round ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-lg-8 col-sm-6">
		<div class="media" id="pengguna">
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
        		text: 'Grafik LKH Pegawai'
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
	        			name: "Terverifikasi Atasan",
	        			y: <?php echo $jum_hari_kerja_rekap_lkh_terverikasi_atasan_round ?>                }, 
        			
        			{
	    				name: "Terverifikasi Otomatis",
	    				y: <?php echo $jum_hari_kerja_rekap_lkh_terverikasi_otomatis_round ?>                }, 
	    			{
	    				name: " Menunggu Verifikasi",
	    				y: <?php echo $jum_hari_kerja_rekap_lkh_menunggu_round ?>                }, 
	    			{
	    				name: "Laporan ditolak",
	    				y: <?php echo $jum_hari_kerja_rekap_lkh_ditolak_round ?>                }, 
	    					
	    			 ]
	    				
    			}]
			});
    	});
	});

</script>