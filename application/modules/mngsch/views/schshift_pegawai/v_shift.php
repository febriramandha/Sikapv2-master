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
    <div class="text-right mt-1">
      <button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
        <span><i class="icon-printer mr-2"></i> Cetak</span>
      </button> 
    </div>

    <?php 
    $jum_tanggal = jumlah_hari_rank($sch_run->start_date, $sch_run->end_date);
    $schrun_id = encrypt_url($sch_run->id,'schrun_id_shift');

    ?>
    
    <br>
    <div class="table-responsive">
      <table id="datatable" class="table table-sm table-hover table-bordered">
        <thead>
          <tr class="table-active">
            <th width="1%" rowspan="3">No</th>
            <th class="text-nowrap" rowspan="3">Nama<hr class="m-0">NIP</th>
            <th  class="text-nowrap" colspan="<?php echo $jum_tanggal+1 ?>"> Priode: <?php echo format_tgl_ind($sch_run->start_date) ?> - <?php echo format_tgl_ind($sch_run->end_date) ?></th>
            
          </tr>
          <tr class="table-active">
           <?php 
           for ($i=0; $i < $jum_tanggal+1; $i++) { ?>
             <th width="1%"><?php echo tanggal_format(tgl_plus($sch_run->start_date, $i),'d') ?></th> 
           <?php  }
           ?>
         </tr>
         <tr class="table-active">
           <?php 
           for ($i=0; $i < $jum_tanggal+1; $i++) { ?>
             <th width="1%"><?php echo substr(hari_tgl(tgl_plus($sch_run->start_date, $i)), 0,1); ?></th> 
           <?php  }
           ?>
         </tr>
       </thead>
       <tbody>
        <?php $ir=0; $no=1; foreach ($user as $row ) {
          $id = encrypt_url($row->id,"user_id_shift");
          $kd_shift = pg_to_array($row->kd_shift);
          $kode = $row->kd_shift;
          ?>
          <tr>
            <td><?php echo $no++ ?></td>
            <td class="text-nowrap"><?php echo nama_icon_nip_link($row->nama,$row->gelar_dpn, $row->gelar_blk,$row->nip,'mngsch/schshift-pegawai/usershift/?sch='.$schrun_id.'&u='.$id) ?>
          </td>
          <?php  
          for ($i=0; $i < $jum_tanggal+1; $i++) {
            if ($kode == null) {
              $shift_kode=  '-';
            }else {
              $shift_kode = $kd_shift[$i];
            }
            ?>
            <td><?php echo $shift_kode  ?></td>
          <?php  }
          ?>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
</div>
<div class="text-left offset-lg-1 m-2" >
  <a href="javascript:history.back()" class="btn btn-sm bg-success-300 result">Kembali <i class="icon-arrow-left5 ml-2"></i></a>                  
</div>
</div>

<script type="text/javascript">
$('#cetak').click(function() {
    var uri_4 = "<?php echo base_url('mngsch/schshift-pegawai/cetak/'.$this->uri->segment(4)) ?>";
      newWindow = window.open(uri_4,"open",'height=600,width=1000');
      if (window.focus) {newWindow.focus()}
        return false;
  })
</script>