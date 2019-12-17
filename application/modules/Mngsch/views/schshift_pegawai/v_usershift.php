<!-- Basic table -->
<div class="card">
  <div class="card-header bg-white header-elements-inline py-2">
    <h5 class="card-title">Jadwal Shift Pegawai</h5>
    <div class="header-elements">
      <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
          </div>
      </div>
  </div>

  <div class="card-body">
          <h4 class="font-weight-semibold mb-1">Pegawai:</h4>
          <?php echo nama_icon_nip($user->nama, $user->gelar_dpn, $user->gelar_blk,$user->nip);

            if ($shift_data) {
               $shiftrun_id = pg_to_array($shift_data->shiftrun_id);
            }
               
           ?>
    <hr>
    <?php $jum_tanggal = jumlah_hari_rank($user->start_date, $user->end_date); ?>
    <?php echo form_open('mngsch/schshift-pegawai/AjaxSaveShift','class="form-horizontal" id="formAjax"'); ?>
    <div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Atur Jadwal <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered">
                          <thead>
                            <tr class="table-active">
                              <th width="1%">No</th>
                              <th width="1%">Tanggal</th>
                              <th class="text-nowrap">Shift</th>
                            </tr>
                          </thead>
                          <tbody>
                              <?php $no=1;
                                  $data_id ='';
                                  for ($i=0; $i < $jum_tanggal+1; $i++) {

                                    if ($shift_data) {
                                       $data_id =  $shiftrun_id[$i]; 
                                    }
                                   
                                    $tgl_plus = tgl_plus($user->start_date, $i);
                                   ?>
                                     <tr>
                                          <td><?php echo $no++ ?></td>
                                          <td class="text-nowrap"><?php echo tgl_ind_hari(tgl_plus($user->start_date, $i));?></td>
                                          <td class="py-1">
                                              <select class="select-nosearchallowClearfalse" name="schrun_shift[]">
                                                    <?php foreach ($schrun_shift->result() as $row_shift) {
                                                      ?>
                                                       <option value="<?php echo $row_shift->id ?>,<?php echo $row_shift->work_day ?>" <?php if($data_id == $row_shift->id) { echo 'selected';} ?>><?php echo $row_shift->kd_shift ?> (<?php echo $row_shift->ket ?> <?php echo jm($row_shift->start_time) ?> - <?php echo jm($row_shift->end_time) ?>)</option>
                                                    <?php } ?>
                                              </select>

                                          </td>
                                     </tr>
                               <?php  }
                               ?>
                          </tbody>
                        </table>
                      </div><hr>
              </div>
          </div>
          
          <input type="hidden" name="mod" value="shift">
          <input type="hidden" name="user_id" value="<?php echo encrypt_url($user->id,'user_id_shift') ?>">
          <input type="hidden" name="schrun_id" value="<?php echo encrypt_url($user->schrun_id,'schrun_id_shift') ?>">
          <input type="hidden" name="dept_id" value="<?php echo encrypt_url($user->dept_id,'dept_id_shift') ?>">
          <div class="text-left offset-lg-2" >
              <a href="javascript:history.back()" class="btn btn-sm bg-success-300 result">Kembali <i class="icon-arrow-left5 ml-2"></i></a>                 
              <button type="submit" class="btn btn-sm btn-info result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
              <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>  
          </div>
        </div>
        <?php echo form_close() ?>
  </div>
 
</div>

<script type="text/javascript">

$('.select-nosearchallowClearfalse').select2({
    minimumResultsForSearch: Infinity,
    placeholder: 'Pilih Data',
    allowClear: false,
});
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
           bx_alert('terjadi kesalahan sistem cobalah mengulang halaman ini kembali');
        },
         beforeSend:function(){
            result.attr("disabled", true);
            spinner.show();
        },
        success: function(res) {
            if (res.status == true) {
                 window.location.href = 'javascript:history.back()';
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