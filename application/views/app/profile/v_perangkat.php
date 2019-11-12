<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header bg-white">
				<h6 class="card-title"><i class="icon-screen3 mr-2 text-blue-400"></i>Perangkat Anda</h6>
			</div>
			<div class="card-body">
                    <div class="alert border-0 alert-dismissible">
                       Perangkat yang saat ini login atau telah aktif di akun Anda
                    </div>
				<div class="table-responsive ">
						<table id="datatable" class="table table-sm table-hover" style="font-size: 80%; width: 100%;" >
							<thead>
								<tr>
									<th width="2%">#</th>
									<th>Browser</th>
									<th>Versi</th>
									<th>Perangkat</th>
                                    <th>Waktu Habis</th>
                                    <th>Login Terakhir</th>
									<th width="15%">Aksi</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>

							
							
						</table>
				</div>
			</div>
			
		</div>
	</div>
</div>

<script type="text/javascript">
var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
        table = $('#datatable').DataTable({ 
            processing: true, 
            serverSide: true,
            "searching": false,
            "paging": false,
             oLanguage: {
                sProcessing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
            }, 
            ajax: {
                url : "<?=site_url();?>app/profile/AjaxGet",
                type:"post",
                "data": {csrf_sikap_token_name: csrf_value, mod:"jsonPlatform"},
               
            },
            "columns": [
                {"data": "id", searchable:false, orderable: false},
                {"data": "browser_agent", searchable:false,},
                {"data": "version_agent", searchable:false,},
                {"data": "platform_agent", searchable:false,},
                {"data": "cookie_expires", searchable:false, orderable: false},
                {"data": "last_login", searchable:false, orderable: false},
                {"data": "aksi", searchable:false, orderable: false},
            ],
           


        });	


     function confirmAksi(cookie) {
        $.ajax({
            url: "<?=site_url();?>app/profile/AjaxGet",
            data: {id:"PlatformDel", cookie: cookie},
            dataType :"json",
            success: function(res){
                if (res.status == true) {
                    table.ajax.reload();
                    toastr["success"](res.msg);

                }else {
                    toastr["warning"](res.msg);
                }
                
            }
        });
    }

	
</script>