<!-- Basic table -->
<div class="card">
    <div class="card-header bg-white header-elements-inline py-2">
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>


    <div class="card-body">
        <div class="text-left">
            <a href="<?php echo base_url('master/allowance/add') ?>" class="btn btn-sm btn-info" data-toggle="modal"
                data-target="#exampleModal" data-backdrop="static" data-keyboard="false"><i
                    class="icon-pen-plus mr-1"></i> Tambah Baru</a>
        </div>
        <p>
        <div class="table-responsive">
            <table id="example-advanced" class="table table-sm table-hover table-bordered">
                <thead>
                    <tr>
                        <th width="1%">No</th>
                        <th>Uraian Unit Kerja</th>
                        <th>Jabatan</th>
                        <th width="1%">Kelas Jabatan</th>
                        <th width="1%">Besaran TPP</th>
                        <th width="1%">Status</th>
                        <th width="11%">Aksi</th>
                    </tr>
                </thead>
                <tbody>



                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Unit Kerja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open('master/allowance/AjaxSaveUraian','id="formAjax"'); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-12">Uraian Unit Kerja <span class="text-danger">*</span></label>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="form-group-feedback form-group-feedback-left">
                                <div class="form-control-feedback">
                                    <i class="icon-pencil3"></i>
                                </div>
                                <input type="text" class="form-control" name="uraian" id="uraian"
                                    placeholder="Isi Uraian">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-12">Jabatan <span class="text-danger">*</span></label>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="form-group-feedback form-group-feedback-left">
                                <div class="form-control-feedback">
                                    <i class="icon-pencil3"></i>
                                </div>
                                <input type="text" class="form-control" name="jabatan" id="jabatan"
                                    placeholder="Jabatan">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-12">Kelas Jabatan <span class="text-danger">*</span></label>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="form-group-feedback form-group-feedback-left">
                                <div class="form-control-feedback">
                                    <i class="icon-pencil3"></i>
                                </div>
                                <input type="text" class="form-control" name="kelas_jabatan" id="kelas_jabatan"
                                    placeholder="Kelas Jabatan">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-12">Besaran TPP <span class="text-danger">*</span></label>
                    <div class="col-lg-12">
                        <div class="form-group-feedback form-group-feedback-left">
                            <div class="form-control-feedback">
                                <i class="icon-pencil3"></i>
                            </div>
                            <input type="number" class="form-control" id="tpp" name="tpp">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-2">Status</label>
                    <div class="col-lg-10">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text">
                                    <input type="checkbox" name="status" class="form-control-switchery" checked
                                        data-fouc>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="mod" value="add">
                <div class="modal-footer">
                    <button type="reset" class="btn btn-sm bg-orange-300 result" data-dismiss="modal">Batal <i
                            class="icon-cross3 ml-2"></i></button>
                    <button type="submit" class="btn btn-sm btn-info result">Simpan <i
                            class="icon-checkmark4 ml-2"></i></button>
                    <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>
                </div>
            </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>

<script type="text/javascript">
$('.multiselect-clickable-groups').multiselect({
    includeSelectAllOption: true,
    enableClickableOptGroups: true,
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
    placeholder: 'Pilih Data',
});


$('.multiselect-item').on('click', function(event) {
    // logic created by  rian reski
    var a = $(this).find('[type="checkbox"]:checked');
    var vala = a.val();
    var t = $('#textbox' + vala).val();

    if (vala !== undefined && t !== undefined) {
        var selected = $('#filter_list_dropdwn').attr('selected', 'selected').val();
        //alert(selected)
        var ya;
        var len = selected.length;
        for (i = 0; i < len; i++) {
            if (selected[i] == vala) {
                ya = true;
            }
        }
        //alert(ya)
        $options = $('#filter_list_dropdwn option');
        if (t == vala && ya != true || t == 0 && ya != true) {
            if (t == vala) {
                //$("#filter_list_dropdwn").val(vala);
                $options.filter('[value="' + vala + '"]').prop('selected', true);
                $('#filter_list_dropdwn option[parent_id=' + vala + ']').each(function() {
                    $(this).prop('selected', true);

                });

                for (i = 0; i < len; i++) {
                    // $("#filter_list_dropdwn").val(selected[i]);
                    $options.filter('[value="' + selected[i] + '"]').prop('selected', true);
                }

                $('#textbox' + vala).val(0);
                $('#filter_list_dropdwn').multiselect("refresh");
            }

            if (t == 0) {
                //alert(ya)
                $('#filter_list_dropdwn option[parent_id=' + vala + ']').each(function() {
                    $(this).prop('selected', false);
                });
                $('#textbox' + vala).val(vala);
                $('#filter_list_dropdwn').multiselect("refresh");
            }
        }
    }

});

//  $("#example-advanced").treetable({ expandable: true });

// Highlight selected row
$("#example-advanced tbody").on("mousedown", "tr", function() {
    $(".selected").not(this).removeClass("selected");
    $(this).toggleClass("selected");
});

function confirmAksi(id) {
    $.ajax({
        url: "<?php echo site_url('master/allowance/AjaxDel') ?>",
        data: {
            id: id
        },
        dataType: "json",
        error: function() {
            $('.table').unblock();
            bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        beforeSend: function() {
            load_dt('.table');
        },
        success: function(res) {
            if (res.status == true) {
                bx_alert_successReload(res.message);
            } else {
                bx_alert(res.message);
            }
            $('.table').unblock();
        }
    });
}

$('#formAjax').submit(function() {
    var result = $('.result');
    var spinner = $('#spinner');
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "JSON",
        error: function() {
            $('.table').unblock();
            result.attr("disabled", false);
            spinner.hide();
            bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        beforeSend: function() {
            result.attr("disabled", true);
            spinner.show();
            load_dt('.table');
        },
        success: function(res) {
            if (res.status == true) {
                bx_alert_successReload(res.message);
            } else {
                bx_alert(res.message);
            }
            result.attr("disabled", false);
            spinner.hide();
            $('.table').unblock();
        }
    });
    return false;
});
</script>