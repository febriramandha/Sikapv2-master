<div class="card">
    <div class="card-header bg-white header-elements-inline">
        <h6 class="card-title">Edit Data Pegawai</h6>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <?php echo form_open('kepegawaian/data-pegawai/AjaxSave','class="wizard-form steps-basic" id="formAjax" data-fouc'); ?>
    <h6>Data Akun</h6>
    <fieldset>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Nama Pengguna <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                    </div>
                    <input type="text" name="username" class="form-control trim" placeholder="isi nama pengguna"
                        autocomplete="off" value="<?php echo $user->username ?>">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Kata Sandi</label>
            <div class="col-lg-10">
                <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                    </div>
                    <input type="password" name="password_confirmation" class="form-control trim"
                        placeholder="isi kata sandi" autocomplete="new-password" />
                    <span><i>* kosongkan jika tidak ingin mengganti kata sandi</i></span>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Ulangi Kata Sandi </label>
            <div class="col-lg-10">
                <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                    </div>
                    <input type="password" name="password" class="form-control trim" placeholder="isi ulangi kata sandi"
                        autocomplete="off" />
                    <span><i>* kosongkan jika tidak ingin mengganti kata sandi</i></span>
                </div>
            </div>
        </div>
    </fieldset>

    <h6>Biodata Pegawai</h6>
    <fieldset>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Memiliki NIP <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="form-control select-nosearch" name="ketegori" disabled="true">
                        <option disabled="">Pilih Ketegori</option>
                        <option value="1" <?php if ($user->pns == 1) { echo "selected";} ?>>NIP</option>
                        <option value="2" <?php if ($user->pns == 2) { echo "selected";} ?>>NON NIP</option>
                    </select>
                    <input type="hidden" name="ketegori" value="<?php echo $user->pns ?>" />
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Nama Lengkap <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                    </div>
                    <input type="text" name="nama" class="form-control" placeholder="isi nama lengkap"
                        value="<?php echo $user->nama ?>">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                    </div>
                    <input type="text" name="gelar_dpn" class="form-control" placeholder="gelar depan"
                        value="<?php echo $user->gelar_dpn ?>">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                    </div>
                    <input type="text" name="gelar_blk" class="form-control" placeholder="gelar belakang"
                        value="<?php echo $user->gelar_blk ?>">
                </div>
            </div>
        </div>

        <?php if ($user->pns == 1) { ?>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">NIP <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                    </div>
                    <input type="text" name="nip" class="form-control" placeholder="isi NIP" pattern="[0-9]{18,18}"
                        title="18 karakter dan harus angka" value="<?php echo $user->nip ?>" disabled>
                    <input type="hidden" name="nip" value="<?php echo $user->nip ?>" />
                </div>
            </div>
        </div>
        <div class="form-group row nip_pegawai">
            <label class="col-form-label col-lg-2">Eselon <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="form-control select-nosearch" name="eselon">
                        <option value="">Pilih Eselon</option>
                        <?php foreach ($eselon as $row) {?>
                        <option value="<?php echo $row->id ?>"
                            <?php if ($user->eselon_id == $row->id) { echo "selected";} ?>><?php echo $row->eselon ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group row nip_pegawai">
            <label class="col-form-label col-lg-2">Pangkat/Golongan <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="form-control select-nosearch" name="golongan">
                        <option value="">Pilih Pangkat/Golongan</option>
                        <?php foreach ($golongan as $row) {?>
                        <option value="<?php echo $row->id ?>"
                            <?php if ($user->golongan_id == $row->id) { echo "selected";} ?>>
                            <?php echo $row->pangkat ?>(<?php echo $row->golongan ?>)</option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Terima TPP <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text">
                            <input type="checkbox" name="tpp" class="form-control-switchery"
                                <?php if ($user->tpp == 1) { echo "checked";} ?> data-fouc>
                        </span>
                    </span>
                </div>
                <span><i>* aktifkan bagi penerima TPP</i></span>
            </div>
        </div>
        <?php } ?>
        <div class="form-group row" id="nip">
            <label class="col-form-label col-lg-2">Jabatan <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group-feedback form-group-feedback-left">
                    <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                    </div>
                    <input type="text" name="jabatan" class="form-control" placeholder="jabatan"
                        value="<?php echo $user->jabatan ?>">
                    <span class="text-danger"><i>* data jabatan diperbarui dari aplikasi SIMPEG (beri tanda (-) jika
                            tidak ada) </i></span>
                </div>
            </div>
        </div>
        <div class="form-group row nip_pegawai">
            <label class="col-form-label col-lg-2">Status Pegawai <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="form-control select-nosearch" name="status_pegawai" disabled>
                        <option value="">Pilih Status Pegawai</option>
                        <?php foreach ($status_peg as $row) {?>
                        <option value="<?php echo $row->id ?>"
                            <?php if ($row->id == $user->statpeg_id) { echo "selected";} ?>><?php echo $row->nama ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Unit Kerja <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <?php 
                      foreach ($instansi as $row) {
                        $datacat[encrypt_url($row->id,'instansi')] = '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name); 
                        }
                        echo form_dropdown('instansi', $datacat, encrypt_url($user->dept_id,'instansi'),'class="form-control select-search" disabled="true"');
                      ?>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Jenis Kelamin <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="form-control select-nosearch" name="gender">
                        <option disabled="">Pilih Jenis Kelamin</option>
                        <option value="1" <?php if ($user->gender == 1) { echo "selected";} ?>>Laki-Laki</option>
                        <option value="2" <?php if ($user->gender == 2) { echo "selected";} ?>>Perempuan</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Agama <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="form-control select-nosearch" name="agama">
                        <?php foreach ($agama as $row) {?>
                        <option value="<?php echo $row->id ?>"
                            <?php if ($user->agama_id == $row->id) { echo "selected";} ?>><?php echo $row->agama ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
    </fieldset>

    <h6>Kewanangan</h6>
    <fieldset>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Jenis Pengguna <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="form-control select-nosearch" name="level" disabled="true">
                        <option disabled="">Pilih Jenis Pengguna</option>
                        <option value="<?php echo encrypt_url(1,'level') ?>"
                            <?php if ($user->level == 1) { echo "selected";} ?>>Super Administrator</option>
                        <option value="<?php echo encrypt_url(2,'level') ?>"
                            <?php if ($user->level == 2) { echo "selected";} ?>>Admin Instansi</option>
                        <option value="<?php echo encrypt_url(5,'level') ?>"
                            <?php if ($user->level == 5) { echo "selected";} ?>>Pimpinan</option>
                        <option value="<?php echo encrypt_url(3,'level') ?>"
                            <?php if ($user->level == 3) { echo "selected";} ?>>Pegawai</option>
                        <option value="<?php echo encrypt_url(4,'level') ?>"
                            <?php if ($user->level == 4) { echo "selected";} ?>>User Eksekutif</option>
                    </select>
                </div>
            </div>
        </div>
    </fieldset>

    <h6>Status</h6>
    <fieldset>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Status Absen Finger</label>
            <div class="col-lg-10">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text">
                            <input type="checkbox" name="status_att" class="form-control-switchery"
                                <?php if ($user->att_status == 1) { echo "checked";} ?> data-fouc disabled="true">
                        </span>
                    </span>
                </div>
                <span><i>* aktifkan untuk mendaftarkan pada mesin sidik jari</i></span>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-2">Status Akun</label>
            <div class="col-lg-10">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text">
                            <input type="checkbox" name="status_akun" class="form-control-switchery"
                                <?php if ($user->status == 1) { echo "checked";} ?> data-fouc>
                        </span>
                    </span>
                </div>
                <span><i>* aktifkan untuk masuk sebagai pengguna</i></span>
            </div>
        </div>
        <?php if ($instansi_cek->absen_online == 1) { ?>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Absen Online</label>
            <div class="col-lg-10">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text">
                            <input type="checkbox" name="absen_online_app" class="form-control-switchery"
                                <?php if ($user->absen_online_app == 1) { echo "checked";} ?> data-fouc>
                        </span>
                    </span>
                </div>
                <span><i>* aktifkan untuk absen online</i></span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-2">Reset Device</label>
            <div class="col-lg-10">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text">
                            <input type="checkbox" name="reset_device" class="form-control-switchery" data-fouc>
                        </span>
                    </span>
                </div>
                <span><i>* aktifkan jika pengguna ingin memperbarui login absen online</i></span>
            </div>
        </div>
        <?php } ?>
        <input type="hidden" name="mod" value="edit">
        <input type="hidden" name="user_id" value="<?php echo encrypt_url($user->user_id,'user_id') ?>">
        <input type="hidden" name="login_id" value="<?php echo encrypt_url($user->login_id,'login_id') ?>">
    </fieldset>
    <?php echo form_close() ?>
</div>



<script type="text/javascript">
// Basic wizard setup
$('.steps-basic').steps({
    headerTag: 'h6',
    bodyTag: 'fieldset',
    transitionEffect: 'fade',
    titleTemplate: '<span class="number">#index#</span> #title#',
    labels: {
        previous: '<i class="icon-arrow-left13 mr-2" /> Sebelumnya',
        next: 'Lanjut <i class="icon-arrow-right14 ml-2" />',
        finish: 'Simpan <i class="icon-arrow-right14 ml-2 result" /> <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i> '
    },
    onFinished: function(event, currentIndex) {
        $("#formAjax").submit();
    }
});

$('[name="ketegori"]').change(function() {
    if ($(this).val() == 2) {
        $('#nip').hide(1000);
        $('#tpp').hide(1000);
    } else {
        $('#nip').show("slow");
        $('#tpp').show("slow");
    }
});

$('#formAjax').submit(function() {
    var result = $('.result');
    var spinner = $('#spinner');
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "JSON",
        error: function() {
            result.attr("disabled", false);
            spinner.hide();
            bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        beforeSend: function() {
            result.attr("disabled", true);
            spinner.show();
        },
        success: function(res) {
            if (res.status == true) {
                bx_alert_successUpadate(res.message, 'kepegawaian/data-pegawai');
            } else {
                bx_alert(res.message);
            }
            result.attr("disabled", false);
            spinner.hide();
        }
    });
    return false;
});
</script>