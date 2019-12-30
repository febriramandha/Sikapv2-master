<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Tambah Jadwal Tidak Tetap</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">

  <?php echo form_open('mngsch/sch-notfixed/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Nama Jadwal<span class="text-danger">*</span></label>
              <div class="col-lg-9">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="nama" class="form-control" placeholder="Nama Jadwal" value="<?php echo $sch_run->name ?>">
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-2"> Rentang Waktu<span class="text-danger">*</span></label>
              <div class="col-lg-4">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="rank1" class="form-control datepicker" placeholder="tanggal mulai" value="<?php echo format_tgl_ind($sch_run->start_date) ?>">
                  </div>
              </div>
              <div class="col-lg-1">
                  <div class="form-group">
                      <span>s/d</span>
                  </div>
              </div>
              <div class="col-lg-4">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="rank2" class="form-control datepicker" placeholder="tanggal berakhir" value="<?php echo format_tgl_ind($sch_run->end_date) ?>">
                  </div>
              </div>
          </div>
         <div class="form-group row">
              <label class="col-form-label col-lg-2">Instansi <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group">
                       <select class="form-control multiselect-clickable-groups" name="instansi[]" multiple="multiple" id="filter_list_dropdwn" data-fouc>
                             <?php foreach ($instansi as $row) { 
                                     $instansi_cek =  pg_to_array($sch_run->dept_id);
                                     $selected ='';
                                     for ($i=0; $i < count($instansi_cek); $i++) { 
                                            if ($instansi_cek[$i] == $row->id) {
                                                    $selected = "selected";
                                            }
                                     }
                              ?>
                                <option value="<?php echo $row->id ?>" parent_id="<?php echo $row->parent_id ?>" <?php echo $selected ?>><?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?></option>
                              <?php } ?>
                        </select>
                  </div>
              </div>
          </div>
         <div class="form-group row" id="tabel">
              <label class="col-form-label col-lg-2">Atur Jadwal <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered">
                          <thead>
                            <tr class="table-active">
                              <th width="1%">ceklis</th>
                              <th width="1%">Tanggal</th>
                              <th class="text-nowrap">Jadwal</th>
                            </tr>
                          </thead>
                          <tbody>
                              <?php $no=1; foreach ($schnotfixed_run_day as $row_day) {
                                    $checked ='disabled';
                                    if ($row_day->class_id) {
                                          $checked ='checked';
                                    }

                               ?>
                                     <tr>
                                          <td><label class="pure-material-checkbox">
                                                  <input class="ceklis" type="checkbox" name="ceklis[<?php echo $row_day->id ?>]" value="<?php echo $row_day->id ?>" <?php echo $checked ?>>
                                                  <span></span>
                                            </label></td>
                                          <td class="text-nowrap"><?php echo $row_day->day_ind ?></td>
                                          <td class="py-1">
                                               <select class="form-control select-search select_hari" name="h[<?php echo $row_day->id ?>]" >  
                                                        <option disabled="">Pilih Jam Kerja</option>
                                                        <?php foreach ($sch_class as $row) {
                                                              $selected = '';
                                                              if ($row->id == $row_day->class_id) {
                                                                    $selected = 'selected';
                                                              }

                                                         ?>
                                                          <option value="<?php echo $row->id ?>" <?php echo $selected ?>><?php echo $row->name ?> (<?php echo $row->start_time ?> - <?php echo $row->end_time ?>)</option>
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

          <div class="form-group row">
            <label class="col-form-label col-lg-2">Buka Jadwal <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <div class="input-group">
                <span class="input-group-prepend">
                  <span class="input-group-text">
                    <input type="checkbox" name="status" class="form-control-switchery" <?php if ($sch_run->schedule_status ==1) { echo "checked";} ?> data-fouc> 
                  </span>
                </span>
              </div>
            </div>
          </div>
         
          <input type="hidden" name="id" value="<?php echo encrypt_url($sch_run->id,"schrun_tidak_tetap")  ?>">
          <input type="hidden" name="mod" value="edit">
          <div class="text-left offset-lg-3" >
              <button type="reset" class="btn btn-sm bg-orange-300 result">Batal <i class="icon-cross3 ml-2"></i></button>                 
              <button type="submit" class="btn btn-sm btn-info result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
              <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
          </div>
        </div>
        <?php echo form_close() ?> 
	</div>
</div>
<?php 

foreach ($instansi as $row) {
    if ($row->jum_sub) { ?>
        <input type="hidden" id="textbox<?php echo $row->id ?>" value="<?php echo $row->id ?>">
   <?php }
}
?>

<script type="text/javascript">


$(".datepicker").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
});
$('.multiselect-clickable-groups').multiselect({
    includeSelectAllOption: true,
    enableClickableOptGroups: true,
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
    placeholder: 'Pilih Data',
});

$('.multiselect-item').on('click', function(event) {
    // logic created by  rian reski
    var a =$(this).find('[type="checkbox"]:checked');
    var vala = a.val();
    var t = $('#textbox'+vala).val();
  
    if (vala !== undefined && t  !== undefined) {
            var selected = $('#filter_list_dropdwn').attr('selected','selected').val();
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
                      $options.filter('[value="'+vala+'"]').prop('selected', true);
                      $('#filter_list_dropdwn option[parent_id=' + vala + ']').each(function () {
                          $(this).prop('selected', true);
                         
                      });
                      
                      for(i=0; i < len; i++){
                          // $("#filter_list_dropdwn").val(selected[i]);
                           $options.filter('[value="'+selected[i]+'"]').prop('selected', true);
                      }

                       $('#textbox'+vala).val(0);
                       $('#filter_list_dropdwn').multiselect("refresh");
                  }

                  if (t == 0) {
                      //alert(ya)
                      $('#filter_list_dropdwn option[parent_id=' + vala + ']').each(function () {
                            $(this).prop('selected', false);
                        });
                        $('#textbox'+vala).val(vala);
                        $('#filter_list_dropdwn').multiselect("refresh");
                  } 
            }
   }
    
});


$('[name="rank1"]').on('change keyup paste', function () {
      $('.ceklis').filter(':checkbox').prop('checked',false);
      $(".select_hari").prop("selected", false).trigger('change');
      fungsi_ceklis_hari();
});
$('[name="rank2"]').on('change keyup paste', function () {
      $('.ceklis').filter(':checkbox').prop('checked',false);
      $(".select_hari").prop("selected", false).trigger('change');
      fungsi_ceklis_hari();
});


function fungsi_ceklis_hari() {

      var result   = $('.result');
      var spinner  = $('#spinner');
      var rank1    = $('[name="rank1"]').val();
      var rank2    = $('[name="rank2"]').val();
      if (rank1 && rank2) {
             $.ajax({
                      type: 'get',
                      url: uri_dasar+'mngsch/sch-notfixed/AjaxGet',
                      data: {mod:'cekhari',rank1:rank1, rank2:rank2},
                      dataType : "JSON",
                      error:function(){
                         result.attr("disabled", false);
                         spinner.hide();
                         $('.ceklis').attr("disabled", true);
                         $(".select_hari").attr("disabled", true);
                         $('.ceklis').filter(':checkbox').prop('checked',false);
                         $(".select_hari").prop("selected", false).trigger('change');
                         bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
                        
                      },
                       beforeSend:function(){
                          result.attr("disabled", true);
                          spinner.show();
                      },
                      success: function(res) {
                          if (res.status == true) {
                              var select;
                              var hari_id  = res.result.hari_id;
                              var len      = hari_id.length;
                              $('.ceklis').filter(':checkbox').prop('checked',false);
                              $(".select_hari").prop("selected", false).trigger('change');
                              for (i = 0; i < len; i++) {
                                  if (hari_id[i].day_eng) {
                                      select = false;
                                  }else {
                                      select = true;
                                  }
                                  $('[name="ceklis['+hari_id[i].id+']"]').attr("disabled", select);
                                  $('[name="h['+hari_id[i].id+']"]').attr("disabled", select);
                              }
                          }else {
                             $('.ceklis').attr("disabled", true);
                             $(".select_hari").attr("disabled", true);
                             $('.ceklis').filter(':checkbox').prop('checked',false);
                             $(".select_hari").prop("selected", false).trigger('change');
                              bx_alert(res.message);
                          }
                          result.attr("disabled", false);
                          spinner.hide();
                      }
                  });
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
                bx_alert_successUpadate(res.message, 'mngsch/sch-notfixed');
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
