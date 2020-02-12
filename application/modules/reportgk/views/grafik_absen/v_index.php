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
			<div class="form-group row">
				<label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span></label>
				<div class="col-lg-10">
					<div class="form-group">
						<select class="form-control select-search" name="instansi"> 
							
						</select> 
					</div>
				</div>
			</div>
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
							<td><i class="icon-file-presentation2 mr-1"></i>Total Hadir</td>
							<td>100</td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Terlambat</td>
							<td>100</td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Pulang Cepat</td>
							<td>100</td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Terlambat & Pulang Cepat</td>
							<td>100</td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total TK</td>
							<td>100</td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Dinas Luar</td>
							<td>100</td>
						</tr>
						<tr>
							<td><i class="icon-file-presentation2 mr-1"></i>Total Cuti</td>
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
	        			y: 20                }, 
        			
        			{
	    				name: "Terlambat",
	    				y: 1                }, 
	    			{
	    				name: "Pulang Cepat",
	    				y: 5                }, 
	    			{
	    				name: "Terlmbat dan pulang cepat",
	    				y: 2                }, 
	    			{
	    				name: "TK",
	    				y: 3                }, 
	    			{
	    				name: "Dinas luar",
	    				y: 100                }, 
	    			{
	    				name: "Cuti",
	    				y: 11                }, 
	    					
	    			 ]
	    				
    			}]
			});
    	});
	});

</script>