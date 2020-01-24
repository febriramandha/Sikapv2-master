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

		<?php echo form_open('kepegawaian/dl-manual/AjaxSave/'.$this->uri->segment(4),'class="form-horizontal" id="formAjax"'); ?>
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Tanggal Dinas Luar<span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="tanggal" class="form-control datepicker" placeholder="tanggal Dinas Luar" >
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Pegawai Dinas Luar <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group">
                       <select class="form-control multiselect-clickable-groups" name="user[]" multiple="multiple" id="filter_list_dropdwn" data-fouc>
                             <?php foreach ($user as $row) { ?>
                                <option value="<?php echo encrypt_url($row->id,'user_id_dl') ?>"><?php echo nama_gelar($row->nama,$row->gelar_dpn, $row->gelar_blk) ?>(<?php echo $row->nip ?>)</option>
                              <?php } ?>
                        </select>
                  </div>
              </div>
          </div>
           <div class="form-group row">
              <label class="col-lg-2 col-form-label">Uraian Kegiatan <span class="text-danger">*</span></label>
              <div class="col-lg-9">
                 <textarea class="ckeditor" id="edit1" name="kegiatan"></textarea>
              </div>
           </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label">Hasil <span class="text-danger">*</span></label>
              <div class="col-lg-9">
                 <textarea class="ckeditor" id="edit2" name="hasil"></textarea>
              </div>
           </div>
          <input type="hidden" name="id">
          <input type="hidden" name="mod" value="add">
          <div class="text-left offset-lg-2" >
               <a href="javascript:history.back()" class="btn btn-sm bg-success-300 result">Kembali <i class="icon-arrow-left5 ml-2"></i></a>                 
              <button type="submit" class="btn btn-sm btn-info result" id="result">Simpan <i class="icon-pen-plus ml-2"></i></button>
              <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
          </div>
        </div>
        <?php echo form_close() ?> 
       
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
                bx_alert_successUpadate(res.message);
            }else {
                bx_alert(res.message);
            }
            result.attr("disabled", false);
            spinner.hide();
        }
    });
    return false;
});
</script>
