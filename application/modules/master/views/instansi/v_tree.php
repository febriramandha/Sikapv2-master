<!-- Basic table -->
<div class="card">
      <div class="card-header bg-white header-elements-inline py-2">
            <h5 class="card-title">Data Instansi</h5>
            <div class="header-elements">
                  <div class="list-icons">
                  <a class="list-icons-item" data-action="collapse"></a>
            </div>
      </div>
      </div>

      <div class="card-body">
           <div class="text-right mt-1 mb-2">
              <button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
                <span><i class="icon-printer mr-2"></i> Cetak</span>
              </button> 
          </div>
          <div class="card-body">
            <input type="text"  class="form-control" name="cari" id="search" placeholder="Cari Instansi">
          </div>    
          <div class="table-responsive">
                  <table id="example-advanced" class="table table-sm table-hover table-bordered">
                    <thead>
                      <tr>
                        <th>Nama Instansi</th>
                        <th width="1%" style="font-size: 80%;">Jumlah Pengguna</th>
                        <th width="1%">Status</th>
                        <th width="1%" class="text-nowrap">Kecamatan</th>
                        <th width="1%" class="text-nowrap">No Urut</th>
                        <th width="1%">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($instansi as $row ) { 
                                $jum_sub = $row->jum_sub;
                                $id =     encrypt_url($row->id,"instansi");
                                if ($jum_sub) {
                                          $class = "folder";
                                          $jum_sub_ = '('.$row->jum_sub.')';
                                }else {
                                          $class = "file";
                                          $jum_sub_ = '';
                                }
                              ?>
                              <tr data-tt-id="<?php echo $row->id ?>" data-tt-parent-id="<?php echo $row->parent_id ?>">
                                    <td class="text-nowrap"><span class="<?php echo $class ?>">
                                          <?php echo filter_path($row->path_info) ?>
                                          <?php echo $row->dept_alias ?> 
                                          <?php echo $jum_sub_ ?></span></td>
                                    <td class="text-center"><?php echo $row->jum_user ?></td>
                                    <td class="text-center"><?php echo status_tree($row->status_instansi) ?></td>
                                    <td class="text-nowrap"><?php echo $row->kecamatan ?></td>
                                    <td class="text-center"><?php echo $row->position_order ?></td>
                                    <td class="text-nowrap"> 
                                      <a href="<?php echo base_url('master/instansi/add/'.$id) ?>" tooltip="tambah sub instansi" flow="left">
                                          <i class="icon-file-plus2 text-orange-300 mr-1"></i>
                                      </a>
                                      <a href="<?php echo base_url('master/instansi/edit/'.$id) ?>" tooltip="edit instansi" flow="left">
                                          <i class="icon-pencil5 text-info-400 mr-1"></i>
                                      </a>
                                      <a class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus data" tooltip="hapus instansi" flow="left" style="cursor:pointer;" id="<?php echo $id ?>">
                                          <i class="icon-bin"></i>
                                      </a>
                                    </td>
                              </tr>


                        <?php } ?>
                       
                        
                    </tbody>
                  </table>
            </div>
      </div>

      
</div>

<script type="text/javascript">
      $("#example-advanced").treetable({ expandable: true });

      // Highlight selected row
      $("#example-advanced tbody").on("mousedown", "tr", function() {
        $(".selected").not(this).removeClass("selected");
        $(this).toggleClass("selected");
      });

$("#search").keyup(function () {
    var value = this.value.toLowerCase().trim();

    $("table tr").each(function (index) {
        if (!index) return;
        $(this).find("td").each(function () {
            var id = $(this).text().toLowerCase().trim();
            var not_found = (id.indexOf(value) == -1);
            $(this).closest('tr').toggle(!not_found);
            return not_found;
        });
    });
});
function confirmAksi(id) {
      $.ajax({
          url: "<?php echo site_url('master/instansi/AjaxDel') ?>",
          data: {id: id},
          dataType :"json",
          error:function(){
             $('.table').unblock();
             bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
          },
           beforeSend:function(){
              load_dt('.table');
          },
          success: function(res) {
              if (res.status == true) {
                  bx_alert_successReload(res.message);
              }else {
                 bx_alert(res.message);
              }
              $('.table').unblock();
          }
      });
}

$('#cetak').click(function() {
      newWindow = window.open(uri_dasar + 'master/instansi/cetak',"open",'height=600,width=800');
      if (window.focus) {newWindow.focus()}
      return false;
})

     
      
</script>