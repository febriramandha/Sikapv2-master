<div class="row">
    <div class="col-md-12">
        <div class="card">
        <div class="card-header bg-white header-elements-inline py-2">
            <h5 class="card-title">Tambah Pos</h5>
        </div>
            <div class="card-body">
                <?php echo form_open('pos/AjaxSave','id="formAjax"'); ?>
                         <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Judul</label>
                            <div class="col-lg-9">
                                 <div class="form-group-feedback form-group-feedback-left">
                                      <div class="form-control-feedback">
                                        <i class="icon-pencil3"></i>
                                      </div>
                                       <input class="form-control" placeholder="Isi Judul" name="title">
                                  </div>
                            </div>
                         </div>
                         <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Deskripsi</label>
                            <div class="col-lg-9">
                                <div class="form-group-feedback form-group-feedback-left">
                                      <div class="form-control-feedback">
                                        <i class="icon-pencil3"></i>
                                      </div>
                                        <input class="form-control" placeholder="Isi Deskripsi" name="deskripsi"  >
                                </div>
                            </div>
                         </div>
                         <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Isi</label>
                            <div class="col-lg-9">
                               <textarea id="ckeditor" name="isi"></textarea>
                            </div>
                         </div>
                         <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Kategori</label>
                            <div class="col-lg-4">
                            	<select class="form-control select-fixed-single" name="kategori">
                            		<?php foreach ($kategori as $row ) { ?>
                                        <option value="<?php echo $row->id ?>"><?php echo $row->name ?></option>
                                    <?php } ?>
                            	</select>
                            </div>
                            <div class="col-lg-4">
                                <a href="<?php echo base_url('pos/kategori') ?>" class="btn btn-sm btn-info legitRipple"><i class="icon-pen-plus mr-2"></i> Tambah Ketegori</a>
                            </div>
                         </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Status</label>
                            <div class="col-lg-10">
                              <div class="input-group">
                                <span class="input-group-prepend">
                                  <span class="input-group-text">
                                    <input type="checkbox" name="status" class="form-control-switchery" checked data-fouc> 
                                  </span>
                                </span>
                              </div>
                            </div>
                          </div>
                        <input type="hidden" name="mod" value="add">
                        <div class="text-left offset-lg-2" >
                          <button type="reset" class="btn btn-sm bg-orange-300 result">Batal <i class="icon-cross3 ml-2"></i></button>                 
                          <button type="submit" class="btn btn-sm btn-info result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
                          <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>  
                       </div>

                <?php echo form_close(); ?>
                   
            </div>
         </div>
    </div>
</div>

<script type="text/javascript">
    var ckeditor = CKEDITOR.replace('ckeditor',
            {
              height:'230px',
              customConfig: uri_dasar+'public/themes/plugin/ckeditor/custom/ckeditor_config.js'}
    );

    function CKupdate(){
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
    }

    $('.select-fixed-single').select2({
        minimumResultsForSearch: Infinity,
        // width: 350
    });

    $('#formAjax').submit(function() {
        CKupdate();
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
                    bx_alert_success(res.message, 'pos');
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