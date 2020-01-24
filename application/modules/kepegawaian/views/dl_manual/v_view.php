<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Dinas luar Manual</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
		 <?php echo nama_icon_nip($instansi->dept_name) ?>
    		<hr>

	     <div class="text-left">
          <a href="<?php echo base_url('kepegawaian/dl-manual/add/'.$this->uri->segment(4)) ?>" class="btn btn-sm btn-info"><i class="icon-pen-plus mr-1"></i> Tambah Dinas Luar</a>
      </div>
        <div class="text-right mt-1">
				<button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
					<span><i class="icon-printer mr-2"></i> Cetak</span>
				</button> 
		</div>

		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<tr class="table-active">
						<th width="1%">No</th>
						<th class="text-nowrap">Tanggal</th>
						<th class="text-nowrap">Kegiatan</th>
            <th class="text-nowrap">Hasil</th>
						<th width="1%">Pegawai</th>
						<th width="1%">Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- /basic table -->

<script type="text/javascript">
$('.multiselect-clickable-groups').multiselect({
    includeSelectAllOption: true,
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
    placeholder: 'Pilih Pegawai',
});
$(".datepicker").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
});
CKEDITOR.replaceClass = 'ckeditor';
 $('.ckeditor').each(function(e){
      CKEDITOR.replace( this.id, {  height:'80px',
              customConfig: uri_dasar+'public/themes/plugin/ckeditor/custom/ckeditor_config.js' });
});


function CKupdate(){
for ( instance in CKEDITOR.instances )
    CKEDITOR.instances[instance].updateElement();
}

$('#formAjax').submit(function() {
  CKupdate();
  var simpan  = $('#result');
  var result  = $('.result');
  var spinner = $('#spinner');
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType : "JSON",
        error:function(){
           result.attr("disabled", false);
           spinner.hide();
           bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
         beforeSend:function(){
            result.attr("disabled", true);
            spinner.show();
        },
        success: function(res) {
            if (res.status == true) {
                bx_alert_ok(res.message,'success');
                $('#formAjax')[0].reset();
                $('[name="user[]"]').val('').trigger('change');
                $('[name="mod"]').val('add');
                $('[name="id"]').val('');
                table.ajax.reload();
                simpan.html('Tambah Dinas Luar <i class="icon-pen-plus ml-2"></i>');
            }else {
                bx_alert(res.message);
            }
            result.attr("disabled", false);
            spinner.hide();
        }
    });
    return false;
});

$(document).ready(function(){
     table = $('#datatable').DataTable({ 
      processing: true, 
      serverSide: true, 
      "ordering": false,
      language: {
            search: '<span></span> _INPUT_',
            searchPlaceholder: 'Cari...',
            processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
        },  
        "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
      ajax: {
          url : uri_dasar+'kepegawaian/dl-manual/indexJson/<?php echo $this->uri->segment(4) ?>',
          type:"post",
          "data": function ( data ) { 
                data.csrf_sikap_token_name= csrf_value;
              },
      },
      "columns": [
          {"data": "id", searchable:false},
          {"data": "tanggal", searchable:false},
          {"data": "uraian", searchable:false},
          {"data": "hasil", searchable:false},
          {"data": "pegawai", searchable:false},
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
          $('td', row).eq(1).addClass('text-nowrap p-1');
          $('td', row).eq(2).addClass('p-0');
          $('td', row).eq(3).addClass('p-0');
          $('td', row).eq(4).addClass('text-nowrap p-1');
          $('td', row).eq(5).addClass('text-nowrap text-center');
        },


  });

   // Initialize
   dt_componen();

});

function confirmAksi(id) {
        $.ajax({
            url: uri_dasar+'kepegawaian/dl-manual/AjaxDel',
            data: {id: id},
            dataType :"json",
            error:function(){
             bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
          },
            success: function(res){
                if (res.status == true) {
                    table.ajax.reload();
                    bx_alert_ok(res.message,'success');
                }else {
                    bx_alert(res.message);
                }
                
            }
        });
    }

</script>
