<div class="row">
    <div class="col-md-12">
        <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title">Edit Pos</h5>
        </div>
            <div class="card-body">
                <?php echo form_open('pos/AjaxSave','id="formAjax"'); ?>
                         <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Judul</label>
                            <div class="col-lg-9">
                                <input class="form-control" placeholder="Isi Judul" name="title"  value="<?php echo $edit->title ?>">
                            </div>
                         </div>
                         <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Deskripsi</label>
                            <div class="col-lg-9">
                                <input class="form-control" placeholder="Isi Deskripsi" name="deskripsi" value="<?php echo $edit->description ?>" >
                            </div>
                         </div>
                         <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Isi</label>
                            <div class="col-lg-9">
                               <textarea id="ckeditor" name="isi"><?php echo $edit->content ?></textarea>
                            </div>
                         </div>
                         <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Kategori</label>
                            <div class="col-lg-4">
                                    <?php 
                                    foreach ($kategori as $row) {
                                        $datacat[$row->id] = $row->name; 
                                    }
                                    echo form_dropdown('kategori', $datacat, "$edit->kategori_id",'class="form-control select-fixed-single"');
                                    ?>
                            </div>
                            <div class="col-lg-4">
                                <a href="<?php echo base_url('pos/kategori') ?>" class="btn btn-sm btn-info legitRipple"><i class="icon-stack-plus mr-2"></i> Tambah Ketegori</a>
                            </div>
                         </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Status</label>
                            <div class="col-lg-9">
                                <span class="badge badge-success" disabled="">
                                     <input type="radio" name="status" value="publish" checked=""> Aktif
                                </span>
                                <span class="badge badge-danger" disabled="">
                                   <input type="radio" name="status" value="unpublish" > Non Aktif
                                </span>
                            </div>
                        </div>
                        <input type="hidden" name="mod" value="edit">
                        <input type="hidden" name="id" value="<?php echo $edit->id ?>">
                        <div class="text-center">
                            <button type="reset" class="btn btn-sm btn-default">Batal <i class="icon-cross3 ml-2"></i></button>
                            <button type="submit" class="btn btn-sm btn-info" id="result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
                      </div>

                <?php echo form_close(); ?>
                   
            </div>
         </div>
    </div>
</div>

<script type="text/javascript">
    var ckeditor = CKEDITOR.replace('ckeditor',
                {height:'230px'}
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
        var result = $('#result');
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType : "JSON",
            error:function(){
                 result.html('<span><i class="icon-checkmark4 ml-2"></i> Simpan</span>');
                 result.attr("disabled", false);
              },
            beforeSend:function(){
                result.html('<i class="icon-spinner2 spinner"></i> Proses..');
                result.attr("disabled", true);
            },
            success: function(res) {   
                if (res.status == true) {
                    toastr["success"](res.alert);
                    window.location.assign("<?= base_url('pos') ?>");
                }else {
                    toastr["warning"](res.alert);
                    result.attr("disabled", false);
                    result.html('<span><i class="icon-checkmark4 ml-2"></i> Simpan</span>');
                }

            }
        });
        return false;
    });
</script>