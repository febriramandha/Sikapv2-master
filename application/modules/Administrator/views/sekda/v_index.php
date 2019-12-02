<!-- Search field -->
<div class="card">
	<div class="card-body">
		<div class="text-center mb-3 py-2">
			<h4 class="font-weight-semibold mb-1">Sekretariat Daerah</h4>
		</div>

		<div class="col-md-12">
			<div class="row">
				<div class="col-md-3">
					<div class="card-img-actions d-inline-block mb-3">
						<img class="image_avatar img-fluid rounded-circle" src="<?php echo base_url('uploads/avatar/thumb/'.$sekda->avatar) ?>" width="170" height="170" alt="">
						<div class="card-img-actions-overlay card-img rounded-circle">
							<a data-popup="tooltip" title="Lihat Foto" href="<?php echo base_url('uploads/avatar/thumb/'.$sekda->avatar) ?>" class="image_avatar btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round">
								<i class="icon-eye2"></i>
							</a>						
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<?php echo form_open('administrator/sekda/AjaxSave','id="formAjax"'); ?>
					 <div class="form-group row">
				            <label class="col-form-label col-lg-3">Nama <span class="text-danger">*</span></label>
				            <div class="col-lg-9">
				              <div class="form-group">
				                  <?php 
									foreach ($eselon2a as $row) {
										$datacat[$row->id] = $row->nama; 
                                    }
                                    echo form_dropdown('nama', $datacat, "$sekda->id",'class="form-control select-fixed-single"');
									?>
				              </div>
				            </div>
			          </div>
			          <div class="form-group row">
				            <label class="col-form-label col-lg-3">NIP </label>
				            <div class="col-lg-9">
				              <div class="form-group">
				                  <input type="text" class="form-control" name="nip" placeholder="Isi nip disini" value="<?php echo $sekda->nip ?>" readonly="">
				              </div>
				            </div>
			          </div>
			          <div class="form-group row">
				            <label class="col-form-label col-lg-3">Jabatan</label>
				            <div class="col-lg-9">
				              <div class="form-group">
				                  <input type="text" class="form-control" name="jabatan" placeholder="Isi Jabatan" value="<?php echo $sekda->jabatan ?>"  readonly="">
				              </div>
				            </div>
			          </div>
			          <div class="form-group row">
				            <label class="col-form-label col-lg-3">Eselon</label>
				            <div class="col-lg-9">
				              <div class="form-group">
				                  <input type="text" class="form-control" name="jabatan" placeholder="Isi Jabatan" value="<?php echo $sekda->eselon ?>"  readonly="">
				              </div>
				            </div>
			          </div>
			          <div class="form-group row">
				            <label class="col-form-label col-lg-3">Pangkat/Golongan</label>
				            <div class="col-lg-9">
				              <div class="form-group">
				                  <input type="text" class="form-control" name="jabatan" placeholder="Isi Jabatan" value="<?php echo $sekda->pangkat ?>/<?php echo $sekda->golongan ?>"  readonly="">
				              </div>
				            </div>
			          </div>
			          <div class="text-center">
							<button type="reset" class="btn btn-sm bg-orange-300">Batal <i class="icon-cross3 ml-2"></i></button>
							<button type="submit" class="btn btn-sm btn-info" id="result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
					  </div>
					  <?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /search field -->
<script type="text/javascript">
$('.select-fixed-single').select2({
    minimumResultsForSearch: Infinity,
    // width: 350
});

$('#formAjax').submit(function() {
	var result = $('#result');
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType : "JSON",
         error:function(){
	      	 result.html('<span>Simpan <i class="icon-checkmark4 ml-2"></i></span>');
	      	 result.attr("disabled", false);
	      	 bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
	      },
	       beforeSend:function(){
	       		result.html('Simpan <i class="icon-spinner2 spinner"></i> ');
	 			result.attr("disabled", true);
	      },
        success: function(res) {
            if (res.status == true) {
            	bx_alert_successUpadate(res.message, 'administrator/sekda');
            }else {
                bx_alert(res.message);
            }
            result.html('<span>Simpan <i class="icon-checkmark4 ml-2"></i> </span>');
	        result.attr("disabled", false);
        }
    });
    return false;
});

</script>