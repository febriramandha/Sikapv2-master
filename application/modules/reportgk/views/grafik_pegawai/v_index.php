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
							<td>100</td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Pegawai PNS</td>
							<td>100</td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Pegawai Non PNS</td>
							<td>100</td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Pegawai Non Aktif</td>
							<td>100</td>
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
	        			y: 100                }, 
        			
        			{
        				name: "Non PNS",
        				y: 10                }, 
        			{
        				name: "Non Aktif",
        				y: 5                }, 
        					
        			]
				}]
			});
    	});
	});

</script>