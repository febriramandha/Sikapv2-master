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

				<?php 
						$jumlah_pns = array(0);
						$jumlah_non_pns = array(0);
						$jumlah_non_aktif = array(0);
						foreach ($data_grafik->result() as $row) {
									$jumlah_pns[] 		= $row->jum_user_pns;
									$jumlah_non_pns[] 	= $row->jum_user_non_pns;
									$jumlah_non_aktif[] = $row->jum_user_non_aktif;
						}

						$total_pns 		= array_sum($jumlah_pns);
						$total_non_pns 	= array_sum($jumlah_non_pns);
						$total_non_aktif= array_sum($jumlah_non_aktif);
						$total_pengguna = $total_pns+$total_non_pns+$total_non_aktif;


				 ?>


				<tbody>
					<tr>
						<td><i class="icon-file-presentation2 mr-1"></i>Total Pengguna</td>
						<td><?php echo $total_pengguna ?></td>
					</tr>
					<tr>
						<td><i class="icon-file-presentation2 mr-1"></i>Pegawai PNS</td>
						<td><?php echo $total_pns ?></td>
					</tr>
					<tr>
						<td><i class="icon-file-presentation2 mr-1"></i>Pegawai Non PNS</td>
						<td><?php echo $total_non_pns ?></td>
					</tr>
					<tr>
						<td><i class="icon-file-presentation2 mr-1"></i>Pegawai Non Aktif</td>
						<td><?php echo $total_non_aktif ?></td>
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
        		text: 'Grafik Pegawai'
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
	        			name: "PNS",
	        			y: <?php echo $total_pns ?> }, 
        			
        			{
        				name: "Non PNS",
        				y: <?php echo $total_non_pns ?> }, 
        			{
        				name: "Non Aktif",
        				y: <?php echo $total_non_aktif ?> }, 
        					
        			]
				}]
			});
    	});
	});

</script>