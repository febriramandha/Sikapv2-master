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
          <div class="card-body">
            <input type="text"  class="form-control" name="cari" id="search" placeholder="Cari Instansi">
          </div>    
          <div class="table-responsive">
                  <table id="example-advanced" class="table table-sm table-hover table-bordered">
                    <thead>
                      <tr class="table-active">
                        <th width="1%">Aksi</th>
                        <th>Nama Instansi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($instansi as $row ) { 
                                $del ='';
                                $jum_sub = $row->jum_sub;
                                $id =     encrypt_url($row->id,"instansi");
                                if ($jum_sub) {
                                          $class = "folder";
                                          $jum_sub_ = '('.$row->jum_sub.')';
                                }else {
                                          $class = "file";
                                          $jum_sub_ = '';
                                          if ($row->status == '0') {
                                                 $del = '<a class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus data" tooltip="hapus jabatan" flow="right" style="cursor:pointer;" id="'.$id.'">
                                          <i class="icon-bin"></i></a>';
                                           }
                                }

                                $aksi = '<a href="'.base_url('master/data-instansi/add/'.$id).'" tooltip="tambah sub instansi" flow="right">
                                          <i class="icon-file-plus2 text-orange-300 mr-1"></i>
                                      </a>
                                      <a href="'.base_url('master/data-instansi/edit/'.$id).'" tooltip="edit instansi" flow="right">
                                          <i class="icon-pencil5 text-info-400 mr-1"></i>
                                      </a>'.$del;
                              ?>
                              <tr data-tt-id="<?php echo $row->id ?>" data-tt-parent-id="<?php echo $row->parent ?>">
                                    <th class="text-nowrap" class="pr-1"><?php echo $aksi ?></th>
                                    <td class="text-nowrap"><span class="<?php echo $class ?>">
                                          <?php echo $row->position_order ?> -
                                          <?php echo $row->nama_instansi ?> 
                                          <?php echo $jum_sub_ ?></span></td>
                              </tr>


                        <?php } ?>
                       
                        
                    </tbody>
                  </table>
            </div>
      </div>

      
</div>

<script type="text/javascript">

 $("#example-advanced").treetable({ expandable: true, initialState:"expanded" });

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
</script>