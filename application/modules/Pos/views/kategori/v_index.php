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
					<div class="text-center">
			              <h6 class="m-0 font-weight-semibold">Tambah Kategori Baru</h6>
			          </div>
					 <?php echo form_open('pos/kategori/AjaxSave','id="formAjax"'); ?>
					 	 <input type="hidden" name="id">
						<div class="form-group">
							<label>Nama:</label>
							<input type="text" name="nama" class="form-control">
						</div>
						<div class="form-group">
							<label>Deskripsi:</label>
							<textarea name="desck" class="form-control"></textarea>
						</div>
						<input type="hidden" name="mod" value="add">
						<button type="submit" class="btn btn-info btn-sm legitRipple" id="result"><i class="icon-stack-plus mr-2"></i> Tambah Kategori</button>
					<?php echo form_close(); ?>
				</div>
				<div class="col-md-8">
					<div class="table-responsive">
						<table class="table" id="datatable">
							<thead>
								<tr>
									<th width="1%">No</th>
									<th>Nama</th>
									<th>Deskripsi</th>
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
		        url : "<?php echo site_url('pos/kategori/json') ?>",
		        type:"post",
		        "data": {csrf_sikap_token_name: csrf_value},
		        beforeSend:function(){
			        	load_dt('#load_dt');
			     },
		    },
		    "columns": [
		        {"data": "id", searchable:false},
		        {"data": "name", },
		        {"data": "description", },
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
	var result = $('#result');
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType : "JSON",
         error:function(){
	      	 result.html('<span><i class="icon-checkmark4 ml-2"></i> Tambah kategori</span>');
	      	 result.attr("disabled", false);
	      },
	      beforeSend:function(){
	       		result.html('<i class="icon-spinner2 spinner"></i> Proses..');
	 			result.attr("disabled", true);
	      },
        success: function(res) {
            if (res.status == true) {
                $('#formAjax')[0].reset();
                table.ajax.reload();
                result.html('<i class="icon-stack-plus mr-2"></i> Tambah kategori');
                toastr["success"](res.alert);
            }else {
                toastr["error"](res.alert);
            }
        }
    });
    return false;
});

function edit(id) {
	var result = $('#result');
    $.ajax({
        url: "<?php echo site_url('pos/kategori/AjaxGet') ?>",
        data: {id: id},
        dataType :"json",
        success: function(res){  
            $('#formAjax')[0].reset();
            $('input[name="mod"]').val('edit');
            result.html('<i class="icon-checkmark4 ml-2"></i> Ubah Ketegori');
            var r = res.data;
            $('input[name="nama"]').val(r.name);
            $('[name="desck"]').val(r.description);
            $('input[name="id"]').val(r.id);
            
        }
    });
}

function confirmAksi(id) {
        $.ajax({
            url: "<?php echo site_url('pos/kategori/AjaxDel') ?>",
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