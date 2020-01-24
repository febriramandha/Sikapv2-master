<!-- Basic table -->
<div class="card">
  <div class="card-header bg-white header-elements-inline py-2">
    <h5 class="card-title">Absen Manual Pegawai</h5>
    <div class="header-elements">
      <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
          </div>
      </div>
  </div>
  <div class="card-body">
          <h4 class="font-weight-semibold mb-1">Pegawai:</h4>
         <?php echo nama_icon_nip($user_data->row()->nama, $user_data->row()->gelar_dpn, $user_data->row()->gelar_blk,$user_data->row()->nip);

            if ($user_data->row()->json_data) {
                  $pgarray_data = json_decode($user_data->row()->json_data, true);
            }
            $jum_tanggal = jumlah_hari_rank($user_data->row()->start_date, $user_data->row()->end_date);

           $pg_array_hari = pg_to_array($user_data->row()->hari_id);

           $absen_in  = $user_data->row()->absen_in;
           $absen_out = $user_data->row()->absen_out;

           $absen_in_disabled ='disabled';
           $absen_out_disabled = 'disabled';

           if ($absen_in) {
                $absen_in_disabled = "";
           }
           if ($absen_out) {
                $absen_out_disabled = "";
           }

           $status_in   = array();
           $status_out  = array();

           if ($absen_data) {
                $tangga_row       = pg_to_array($absen_data->tanggal);
                $status_in_row    = pg_to_array($absen_data->status_in);
                $status_out_row   = pg_to_array($absen_data->status_out);

                $i = 0;
                foreach ($tangga_row as $r_val) {
                      $n = $i++;
                      $tgl_val = tanggal_format($r_val,'Ymd');
                      $status_in[$tgl_val] = $status_in_row[$n];
                      $status_out[$tgl_val] = $status_out_row[$n];
                }
           }
               
           ?>
    <hr>
    <?php echo form_open('mngabsenmanual/absen-manualpe/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
    <div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Isi Absen <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered">
                          <thead>
                            <tr class="table-active">
                              <th width="1%" rowspan="2">No</th>
                              <th width="1%" rowspan="2">Tanggal</th>
                              <th class="text-nowrap text-center" colspan="3">Masuk</th>
                              <th class="text-nowrap text-center" colspan="3">Pulang</th>
                              <th width="1%" rowspan="2">Ket</th>
                            </tr>
                            <tr class="table-active text-center">
                                <td>H</td>
                                <td>T</td>
                                <td>TK</td>
                                <td>H</td>
                                <td>CP</td>
                                <td>TK</td>
                            </tr>
                          </thead>
                          <tbody>
                              <?php $no = 1;
                               $tanggal_data = array();
                               for ($i=0; $i < $jum_tanggal+1; $i++) { 
                                      $tanggal_ =  tgl_plus($user_data->row()->start_date, $i);
                                      $hari_n = tanggal_format(tgl_plus($user_data->row()->start_date, $i),'N');
                                      foreach ($pg_array_hari as $value) {
                                          if ($value == $hari_n) {
                                                $tanggal_data[] = tgl_plus($user_data->row()->start_date, $i);
                                          }
                                      }
                               }

                               foreach ($tanggal_data as $value) {
                                    $status_in_c = '';
                                    $status_in_c  = '';
                                    $status_out_c = '';
                                    $ket = '';
                                    $tgl_ku = tgl_ind_hari($value);  
                                    $tgl_f  = tanggal_format($value,'Ymd');  

                                    if ($absen_data) {
                                           $status_in_c  = $status_in[$tgl_f];
                                           $status_out_c = $status_out[$tgl_f];
                                           $ket_in  = status_absnmanual($status_in_c,'in');
                                           $ket_out = status_absnmanual($status_out_c,'out');
                                           $ket = $ket_in.'|'.$ket_out;
                                    }
                                   

                                    $firs_checked = 'checked';

                                    if ($status_in_c) {
                                          $firs_checked = '';
                                    }


                                  ?>
                                    <tr>
                                          <td><?php echo $no++ ?></td>
                                          <td width="1%" class="text-nowrap"><?php echo $tgl_ku ?></td> 
                                          <td class="text-center">
                                            <input type='radio' name="in[<?php echo $tgl_f ?>]" <?php if ($status_in_c == 1) { echo "checked"; } ?> value="1" <?php echo $firs_checked ?> <?php echo $absen_in_disabled ?>>
                                          </td>
                                          <td class="text-center" <?php echo $absen_in_disabled ?>>
                                            <input type='radio' name="in[<?php echo $tgl_f ?>]" <?php if ($status_in_c == 2) { echo "checked"; } ?> value="2" <?php echo $absen_in_disabled ?>>
                                          </td>
                                          <td class="text-center">
                                            <input type='radio' name="in[<?php echo $tgl_f ?>]" <?php if ($status_in_c == 3) { echo "checked"; } ?> value="3" <?php echo $absen_in_disabled ?>>
                                          </td>

                                          <td class="text-center">
                                            <input type='radio' name="out[<?php echo $tgl_f ?>]" <?php if ($status_out_c == 1) { echo "checked"; } ?> value="1" <?php echo $firs_checked ?> <?php echo $absen_out_disabled ?>>
                                          </td>
                                          <td class="text-center">
                                            <input type='radio' name="out[<?php echo $tgl_f ?>]" <?php if ($status_out_c == 2) { echo "checked"; } ?> value="2" <?php echo $absen_out_disabled ?>>
                                          </td>
                                          <td class="text-center">
                                            <input type='radio' name="out[<?php echo $tgl_f ?>]" <?php if ($status_out_c == 3) { echo "checked"; } ?> value="3" <?php echo $absen_out_disabled ?>>
                                          </td>
                                          <td class="py-0"><?php echo $ket ?></td>
                                    </tr>
                                    <input type="hidden" name="tanggal[]" value="<?php echo $value ?>">
                                  
                             <?php  }
                             ?>
                          </tbody>
                        </table>
                      </div><hr>
              </div>
          </div>
          
          <input type="hidden" name="mod" value="add">
          <input type="hidden" name="u" value="<?php echo $this->input->get('u') ?>">
          <input type="hidden" name="sch" value="<?php echo $this->input->get('sch') ?>">
          
          
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