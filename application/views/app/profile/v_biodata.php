<!-- Search field -->
<div class="card">
	<div class="card-body">
		<div class="text-center mb-3 py-2">
			<h4 class="font-weight-semibold mb-1"><?php echo $user->nama ?></h4>
			<span class="text-muted d-block"><?php echo $user->dept_name ?></span>
		</div>
		

		<div class="col-md-12">
			<div class="row">
				<div class="col-md-3">
					<div class="card-img-actions d-inline-block mb-3">
						<img class="image_avatar img-fluid rounded-circle" src="<?php echo base_url('uploads/avatar/thumb/'.$user->avatar) ?>" width="170" height="170" alt="">
						<div class="card-img-actions-overlay card-img rounded-circle">
							<a data-popup="tooltip" title="Lihat Foto" href="<?php echo base_url('uploads/avatar/thumb/'.$user->avatar) ?>" class="image_avatar btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round">
								<i class="icon-eye2"></i>
							</a>
							<a href="#" id="tukarphoto" data-popup="tooltip" title="Ganti Foto Max 2MB" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
								<i class="icon-link"></i>
							</a>
							<form id="formphoto">
								<input type="file" name="file" id="file" style="display: none;"/>
								<input type="hidden" name="mod" value="upload_photo" />
								<input type="hidden" name="csrf_sikap_token_name" value="<?php echo $this->security->get_csrf_hash(); ?>">
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="table-responsive ">
						<table class="table" style="white-space: nowrap;">
							<tr >
								<th width="200">Nama Lengkap</th>
								<td width="2">:</td>
								<td ><?php echo name_degree(_name($user->nama),$user->gelar_dpn,$user->gelar_blk)   ?></td>
							</tr>
							<tr>
								<th>NIP</th>
								<td>:</td>
								<td><?php echo $user->nip ?></td>
							</tr>
							<tr>
								<th>ID Absen</th>
								<td>:</td>
								<td><?php echo $user->key ?></td>
							</tr>
							<tr>
								<th>Instansi</th>
								<td>:</td>
								<td><?php echo _name($user->dept_name) ?></td>
							</tr>
							<tr>
								<th>Jenis Kelamin</th>
								<td>:</td>
								<td><?php echo gender($user->gender) ?></td>
							</tr>
							<tr>
								<th>Tanggal Lahir</th>
								<td>:</td>
								<td><i class="icon-calendar3 mr-1"></i><?php echo format_tgl_ind($user->lahir_tanggal) ?></td>
							</tr>
							<tr>
								<th>Umur</th>
								<td>:</td>
								<td><?php echo _umur($user->lahir_tanggal) ?></td>
							</tr>

							
							<tr>
								<th>Agama</th>
								<td>:</td>
								<td><?php echo $user->agama ?></td>
							</tr>
							<tr>
								<th>Status Pernikahan</th>
								<td>:</td>
								<td><?php echo status_kawin($user->statkawin_id) ?></td>
							</tr>
							<tr>
								<th>Status Kepegawaian</th>
								<td>:</td>
								<td><?php echo $user->status_pegawai ?></td>
							</tr>
							<tr>
								<th>Pangkat/Golongan</th>
								<td>:</td>
								<td><?php echo $user->pangkat ?>/<?php echo $user->golongan ?></td>
							</tr>
							<tr>
								<th>Jabatan</th>
								<td>:</td>
								<td><?php echo $user->jabatan ?></td>
							</tr>
							<tr>
								<th>Eselon</th>
								<td>:</td>
								<td><?php echo $user->eselon ?></td>
							</tr>
							<tr>
								<th>Login Terakhir</th>
								<td>:</td>
								<td><?php echo format_waktu_ind($user->last_login) ?></td>
							</tr>
							
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /search field -->

<script type="text/javascript">
	// Initialize
	$('[data-popup="tooltip"]').tooltip();
        // Demo tooltips, remove in production
        var demoTooltipSelector = '[data-popup="tooltip-demo"]';
        if($(demoTooltipSelector).is(':visible')) {
        	$(demoTooltipSelector).tooltip('show');
        	setTimeout(function() {
        		$(demoTooltipSelector).tooltip('hide');
        	}, 2000);
        }
        
	// $(document).on('click', '#tukarphoto', function(e){
		$("#tukarphoto").click(function(e){
			e.preventDefault();
			$("#file").trigger('click');
		});
		
   // $(document).on('change', '#file', function(){
   	$("#file").change(function(){
   		var photo=$(this).val();
   		if(photo=="")
   		{
   			return false;
   		}else{
   			$("#formphoto").trigger('submit');
   		}
   	});

    // $(document).on('submit', '#formphoto', function(e){
    	$("#formphoto").submit(function(e){
    		e.preventDefault();
    		var formData = new FormData($(this)[0]);
    		$.ajax({
    			url: '<?=base_url();?>app/profile/AjaxGet',
    			type: 'POST',
    			dataType:'JSON',
    			data: formData,
    			async: true,
    			cache: false,
    			contentType: false,
    			processData: false,
    			error:function(){
    				$('.card-img-actions').unblock();
    				toastr["warning"]('reques data gagal, mohon coba kembali');	
    			},
    			beforeSend:function(){
    				load_dt('.card-img-actions'); 
    			},
    			success: function (res) {
    				if(res.status== true)
    				{
    					$('.card-img-actions').unblock();
    					$(".image_avatar").attr('src',res.url);	
    					$(".image_avatar").attr('href',res.url);	
    					bx_alert_ok(res.msg,'success');
    				}else{
    					$('.card-img-actions').unblock();
    					bx_alert(res.msg);
    				}			  
    			}
    		});
    		
    		return false;
    	});

    </script>
