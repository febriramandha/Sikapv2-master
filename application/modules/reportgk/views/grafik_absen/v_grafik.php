<?php 

$jum_hari_kerja_rekap 	= array(0);
$jum_hadir_kerja_rekap	= array(0);
$jum_terlambar_rekap	= array(0);
$jum_pulang_cepat_rekap	= array(0);
$jum_tk_rekap			= array(0);
$jum_tidak_upacara_rekap= array(0);
$jum_tidak_sholatza_rekap= array(0);
$jum_dinas_luar_rekap	= array(0);
$jum_cuti_rekap			= array(0);  
$user = array();

foreach ($pegawai_absen as $row) {
	$jum_hari_kerja_rekap[]  	= jum_hari_kerja_rekap($row->json_absen);
	$jum_hadir_kerja_rekap[] 	= jum_hadir_kerja_rekap($row->json_absen);
	$jum_terlambar_rekap[] 		= jum_terlambar_rekap($row->json_absen);
	$jum_pulang_cepat_rekap[] 	= jum_pulang_cepat_rekap($row->json_absen);
	$jum_tk_rekap[] 			= jum_tk_rekap($row->json_absen);
	$jum_tidak_upacara_rekap[] 	= jum_tidak_upacara_rekap($row->json_absen);
	$jum_tidak_sholatza_rekap[] = jum_tidak_sholatza_rekap($row->json_absen, $row->agama_id);
	$jum_dinas_luar_rekap[] 	= jum_dinas_luar_rekap($row->json_absen);
	$jum_cuti_rekap[] 			= jum_cuti_rekap($row->json_absen);  
	$user[] = 1;  
}

$jum_hari_kerja_rekap_sum 	= array_sum($jum_hari_kerja_rekap);
$jum_hadir_kerja_rekap_sum	= array_sum($jum_hadir_kerja_rekap);
$jum_terlambar_rekap_sum	= array_sum($jum_terlambar_rekap);
$jum_pulang_cepat_rekap_sum	= array_sum($jum_pulang_cepat_rekap);
$jum_tk_rekap_sum			= array_sum($jum_tk_rekap);
$jum_tidak_upacara_rekap_sum= array_sum($jum_tidak_upacara_rekap);
$jum_tidak_sholatza_rekap_sum= array_sum($jum_tidak_sholatza_rekap);
$jum_dinas_luar_rekap_sum	= array_sum($jum_dinas_luar_rekap);
$jum_cuti_rekap_sum			= array_sum($jum_cuti_rekap); 
$jum_user = count($user);

// $jum_hari_kerja_rekap_round 	= round($jum_hari_kerja_rekap_sum/$jum_user,0);
// $jum_hadir_kerja_rekap_round	= round($jum_hadir_kerja_rekap_sum/$jum_user,0);
// $jum_terlambar_rekap_round		= round($jum_terlambar_rekap_sum/$jum_user,0);
// $jum_pulang_cepat_rekap_round	= round($jum_pulang_cepat_rekap_sum/$jum_user,0);
// $jum_tk_rekap_round				= round($jum_tk_rekap_sum/$jum_user,0);
// $jum_tidak_upacara_rekap_round	= round($jum_tidak_upacara_rekap_sum/$jum_user,0);
// $jum_tidak_sholatza_rekap_round	= round($jum_tidak_sholatza_rekap_sum/$jum_user,0);
// $jum_dinas_luar_rekap_round		= round($jum_dinas_luar_rekap_sum/$jum_user,0);
// $jum_cuti_rekap_round			= round($jum_cuti_rekap_sum/$jum_user,0); 




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
							<td><i class="icon-file-presentation2 mr-1"></i>Total Pengguna</td>
							<td><?php echo $jum_user ?></td>
						</tr>
						<!-- <tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Jumlah Hari Kerja</td>
							<td><?php echo $jum_hari_kerja_rekap_round ?></td>
						</tr> -->
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Hadir</td>
							<td><?php echo $jum_hadir_kerja_rekap_sum ?></td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Terlambat</td>
							<td><?php echo $jum_terlambar_rekap_sum ?></td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Pulang Cepat</td>
							<td><?php echo $jum_pulang_cepat_rekap_sum ?></td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total TK</td>
							<td><?php echo $jum_tk_rekap_sum  ?></td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Tidak Upacara</td>
							<td><?php echo $jum_tidak_upacara_rekap_sum ?></td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Tidak Shalat Zuhur/Ashar Berjamaah</td>
							<td><?php echo $jum_tidak_sholatza_rekap_sum ?></td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Dinas Luar</td>
							<td><?php echo $jum_dinas_luar_rekap_sum ?></td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Cuti</td>
							<td><?php echo $jum_cuti_rekap_sum ?></td>
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
        		text: 'Grafik Kehadiran Pegawai'
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
	        			name: "Hadir",
	        			y: <?php echo $jum_hadir_kerja_rekap_sum ?>                }, 
        			
        			{
	    				name: "Terlambat",
	    				y: <?php echo $jum_terlambar_rekap_sum ?>                }, 
	    			{
	    				name: "Pulang Cepat",
	    				y: <?php echo $jum_pulang_cepat_rekap_sum ?>                }, 
	    			{
	    				name: "TK",
	    				y: <?php echo $jum_tk_rekap_sum ?>                }, 
	    			{
	    				name: "Tidak Upacara",
	    				y: <?php echo $jum_tidak_upacara_rekap_sum ?>               }, 
	    			{
	    				name: "Tidak Shalat Zuhur/Ashar Berjamaah",
	    				y: <?php echo $jum_tidak_sholatza_rekap_sum ?>              }, 
	    			{
	    				name: "Dinas luar",
	    				y: <?php echo $jum_dinas_luar_rekap_sum ?>                }, 
	    			{
	    				name: "Cuti",
	    				y: <?php echo $jum_cuti_rekap_sum ?>                }, 
	    					
	    			 ]
	    				
    			}]
			});
    	});
	});
</script>