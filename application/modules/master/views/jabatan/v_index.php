<!-- Basic table -->
<div class="card">
      <div class="card-header bg-white header-elements-inline py-2">
            <h5 class="card-title">Data Jenjang Jabatan</h5>
            <div class="header-elements">
                  <div class="list-icons">
                  <a class="list-icons-item" data-action="collapse"></a>
            </div>
      </div>
      </div>

      <div class="card-body">  
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
                                $jum_sub = $row->jum_sub;
                                $id =     encrypt_url($row->id,"instansi_id");
                                if ($jum_sub) {
                                          $class = "folder";
                                }else {
                                          $class = "file";
                                }
                                $aksi ='';
                                if ($row->type != "non_instansi") {
                                     $aksi = '<a href="'.base_url('master/jabatan/struktur/'.$id).'" class="btn btn-sm badge-info p-1"> <i class="icon-users"></i>Atur Struktur</a>
                          </span>';
                                }

                             
                              ?>
                              <tr data-tt-id="<?php echo $row->id ?>" data-tt-parent-id="<?php echo $row->parent ?>">
                                    <th class="text-nowrap py-0" class="pr-1"><?php echo $aksi ?></th>
                                    <td class="text-nowrap"><span class="<?php echo $class ?>">
                                          <?php echo $row->position_order ?> -
                                          <?php echo $row->nama_instansi ?></span></td>
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