<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Absen Upacara</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
			</div>
		</div>
	</div>

	<div class="card-body">
		<?php echo form_open('kepegawaian/absen-upacara/AjaxSave/'.$this->uri->segment(4),'class="form-horizontal" id="formAjax"'); ?>
		<div class="form-group row">
	      <label class="col-form-label col-lg-2">Hari/Tanggal</label>
	      <div class="col-lg-10">
	        <div class="form-group-feedback form-group-feedback-left">
	          <div class="form-control-feedback">
	            <i class="icon-price-tag3"></i>
	          </div>
	          <input type="text" class="form-control" value="<?php echo tgl_ind_hari($jadwal->tanggal).' '.jm($jadwal->jam_mulai) ?>" disabled>
	        </div>
	      </div>
	    </div>
		 <div class="form-group row">
	      <label class="col-form-label col-lg-2">Berita Acara</label>
	      <div class="col-lg-10">
	        <div class="form-group-feedback form-group-feedback-left">
	          <div class="form-control-feedback">
	            <i class="icon-price-tag3"></i>
	          </div>
	          <input type="text"  class="form-control" value="<?php echo $jadwal->ket ?>" disabled>
	        </div>
	      </div>
	    </div>
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
			<label class="col-form-label col-lg-2">Eselon <span class="text-danger">*</span></label>
			<div class="col-lg-10">
				<div class="form-group">
					<select class="form-control multiselect-clickable-groups" name="eselon" multiple="multiple" id="filter_list_dropdwn" data-fouc>
						<?php foreach ($eselon as $row) { ?>
							<option value="<?php echo encrypt_url($row->id,'eselon_id') ?>"><?php echo $row->eselon ?></option>
						<?php } ?>
					</select>
					 <span class="text-danger"><i>* pilih eselon untuk melakukan perubahan data absen</i></span>
				</div>
			</div>
		</div>
		<div class="text-right mt-1">
			<span class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
				<span><i class="icon-printer mr-2"></i> Cetak</span>
			</span> 
		</div>		
		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<tr class="table-active">
						<th width="1%" rowspan="2">No</th>
						<th class="text-nowrap" rowspan="2">Nama</th>
						<th class="text-nowrap" rowspan="2">NIP</th>
						<th class="text-nowrap" rowspan="2">Pangkat/Gol</th>
						<th class="text-nowrap" colspan="3">Absen</th>
						<th class="text-nowrap" rowspan="2">Ket</th>
						<th class="text-nowrap" rowspan="2">Aksi</th>
					</tr>
					<tr class="table-active">
						<th  width="1%" >Hadir (H)</th>
						<th  width="1%" >Tidak Hadir (A)</th>
						<th  width="1%" >Cuti (C)</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
				<tr>
					<td colspan="9" align="center">
						<button type="submit" class="btn btn-sm bg-info legitRipple result">Simpan Absen <i class="icon-checkmark4 ml-2"></i></button>
						<i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
					</td>
	            </tr>
			</table>
		</div>
		</form>
	</div>
</div>
<!-- /basic table -->
<input type="hidden" name="stag" value="0">

<script type="text/javascript">
	$('.multiselect-clickable-groups').multiselect({
		includeSelectAllOption: true,
		enableClickableOptGroups: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		placeholder: 'Pilih Data',
	});

	$('[name="eselon"]').change(function() {
		table.ajax.reload();
	})

	$(document).ready(function(){
		table = $('#datatable').DataTable({ 
			processing: true, 
			serverSide: true, 
			"ordering": false,
			"paging": false,
			"searching": false,
			stateSave: true,
			language: {
				search: '<span></span> _INPUT_',
				searchPlaceholder: 'Cari...',
				processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
			},  
			ajax: {
				url : uri_dasar+'kepegawaian/absen-upacara/PegawaiJson/<?php echo $this->uri->segment(4) ?>',
				type:"post",
				"data": function ( data ) { 
					data.csrf_sikap_token_name= csrf_value;
					data.eselon=$('[name="eselon"]').val();
					if ($('[name="stag"]').val() == 1) {
						data.instansi=$('[name="instansi"]').val();
					}else {
						data.instansi= localStorage.index_instansi;
					} 
				},
			},
			"columns": [
			{"data": "id", searchable:false},
			{"data": "nama", searchable:false},
			{"data": "nip", searchable:false},
			{"data": "golongan", searchable:false},
			{"data": "cek1", searchable:false},
			{"data": "cek2", searchable:false},
			{"data": "cek3", searchable:false},
			{"data": "ket", searchable:false},
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
			  $('td', row).eq(4).addClass('text-center');
			  $('td', row).eq(5).addClass('text-center');
			  $('td', row).eq(6).addClass('text-center');
			},

		});

		dt_componen();
		loadSettings();
	});

	$(window).on('unload', function(){
		saveSettings();
	});

	$('[name="instansi"]').change(function() {
		if ($('[name="stag"]').val() == 1) {
			table.ajax.reload();
	    }else {
	    	$('[name="stag"]').val(1);
	    }
	})

	function loadSettings() {
		if (localStorage.index_instansi) {
			$('[name="instansi"]').val(localStorage.index_instansi).trigger('change');
			if (!$('[name="instansi"]').val()) {
				$('[name="instansi"]').val($('[name="instansi"] option:first').val()).trigger('change');
			}
		}
	}

	function saveSettings() {
		var instansi = $('[name="instansi"]').val();
		if (instansi) {
			localStorage.index_instansi = instansi;
		}

	}

	$('#formAjax').submit(function() {
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
	            	table.ajax.reload();
	                bx_alert_ok(res.message,'success');
	            }else {
	                bx_alert(res.message);
	            }
	            result.attr("disabled", false);
	            spinner.hide();
	        }
	    });
	    return false;
	});

	function confirmAksi(id) {
        $.ajax({
            url: uri_dasar+'kepegawaian/absen-upacara/AjaxDel',
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

    $('#cetak').click(function() {
		newWindow = window.open(uri_dasar + 'kepegawaian/absen-upacara/cetak/'+$('[name="instansi"]').val()+'/<?php echo $this->uri->segment(4) ?>',"open",'height=600,width=800');
		if (window.focus) {newWindow.focus()}
			return false;
	})

</script>