<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline pb-1 pt-sm-1">
		<h5 class="card-title">Jenis Cuti</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-4">
					 <?php echo form_open('master/jnscuti/AjaxSave','id="formAjax"'); ?>
					 	 <input type="hidden" name="id">
						<div class="form-group">
							<label>Kode:</label>
							<input type="text" name="kode" class="form-control" placeholder="Kode Cuti">
						</div>

						<div class="form-group">
							<label>Nama:</label>
							<input type="text" name="nama" class="form-control" placeholder="Nama Cuti">
						</div>
						<input type="hidden" name="mod" value="add">
						<button type="submit" class="btn-summit btn btn-info btn-sm legitRipple"><i class="icon-stack-plus mr-2"></i> Tambah Jenis Cuti</button>
					<?php echo form_close(); ?>
				</div>
				<div class="col-md-8">
					<div class="table-responsive">
						<table class="table table-sm table-hover" id="datatable">
							<thead>
								<tr>
									<th width="1%">No</th>
									<th width="1%">Kode</th>
									<th>Nama</th>
									<th width="1%">Aksi</th>
								</tr>
							</thead>
							<tbody id="load_dt">	
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<!-- /basic table -->

<script type="text/javascript">
	var url = "<?= site_url()  ?>";
	var table;
	var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
	$(document).ready(function() {

		table = $('#datatable').DataTable({ 
		    processing: true, 
		    serverSide: true, 
		    "ordering": false,
		    language: {
	            search: '<span></span> _INPUT_',
	            searchPlaceholder: 'Cari...',
	        }, 
	        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		    ajax: {
		        url : "<?php echo site_url('master/jnscuti/Getjson') ?>",
		        type:"post",
		        "data": {csrf_sikap_token_name: csrf_value},
		        beforeSend:function(){
			        	load_dt('#load_dt');
			     },
		    },
		    "columns": [
		        {"data": "id", searchable:false},
		        {"data": "kode", },
		        {"data": "nama", },
		        {"data": "action", searchable:false},
		    ],
		    rowCallback: function(row, data, iDisplayIndex) {
		        var info = this.fnPagingInfo();
		        var page = info.iPage;
		        var length = info.iLength;
		        var index = page * length + (iDisplayIndex + 1);
		        $('td:eq(0)', row).html(index);

		        
		    },
		     createdRow: function(row, data, index) {
            	$('td', row).eq(3).addClass('text-nowrap');
            },


		});

		 // Initialize
		 dt_componen();
	});

$('#formAjax').submit(function() {
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType : "JSON",
        success: function(res) {
            if (res.status == true) {
                $('#formAjax')[0].reset();
                table.ajax.reload();
                $('.btn-summit').html('<i class="icon-stack-plus mr-2"></i> Tambah Jenis Cuti');
                toastr["success"](res.alert);
            }else {
                toastr["error"](res.alert);
            }
        }
    });
    return false;
});

function edit(id) {
    $.ajax({
        url: "<?php echo site_url('master/jnscuti/AjaxGet') ?>",
        data: {id: id},
        dataType :"json",
        success: function(res){  
            $('#formAjax')[0].reset();
            $('input[name="mod"]').val('edit');
            $('.btn-summit').html('<i class="icon-checkmark4 ml-2"></i> Ubah Jenis Cuti');
            var r = res.data;
            $('input[name="kode"]').val(r.kode);
            $('input[name="nama"]').val(r.nama);
            $('input[name="id"]').val(r.id);
            
        }
    });
}

function confirmAksi(id) {
        $.ajax({
            url: "<?php echo site_url('master/jnscuti/AjaxDel') ?>",
            data: {id: id},
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