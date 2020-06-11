<!-- Basic table -->
<div class="card">
      <div class="card-header bg-white header-elements-inline py-2">
            <h5 class="card-title">Struktur Jabatan</h5>
            <div class="header-elements">
                  <div class="list-icons">
                  <a class="list-icons-item" data-action="collapse"></a>
            </div>
      </div>
      </div>

      <div class="card-body">  
      	<div class="text-center mb-3 py-2">
    			<h4 class="font-weight-semibold mb-1">Struktur Jabatan</h4>
    			<span class="text-muted d-block"><?php echo $instansi->nama_instansi ?></span>
    		</div>
        <?php if (!$jabatan->row()) { ?>
  	  	 <div class="form-group row">
    			<div class="text-left col-lg-12">
    				<a href="<?php echo base_url('master/jabatan/add/'.$this->uri->segment(4)) ?>" class="btn btn-sm btn-info"><i class="icon-pen-plus mr-1"></i> Tambah Jabatan</a>
    			</div>
    		</div>
       <?php } ?>
          <div class="table-responsive">
                  <table id="example-advanced" class="table table-sm table-hover table-bordered">
                    <thead>
                      <tr class="table-active">
                        <th width="1%">Aksi</th>
                        <th>Nama Jabatan</th>
                        <th>Pegawai</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($jabatan->result() as $row ) { 
                                $jum_sub = $row->jum_sub;
                                $del ='';
                                $id =     encrypt_url($row->id,"jabatan_id");
                                if ($jum_sub) {
                                          $class = "folder";
                                          $del = '';
                                          $color = 'text-warning-600';
                                }else {
                                          $class = "file";
                                         
                                           $color = '';

                                           if ($row->status == '0') {
                                                 $del = '<a class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus data" tooltip="hapus jabatan" flow="right" style="cursor:pointer;" id="'.$id.'">
                                          <i class="icon-bin"></i></a>';
                                           }
                                }

                                $aksi = '<a href="'.base_url('master/jabatan/add/'.$this->uri->segment(4).'?at='.$id.'').'" tooltip="tambah sub jabatan" flow="right">
                                          <i class="icon-file-plus2 text-orange-300 mr-1"></i>
                                      </a>
                                      <a href="'.base_url('master/jabatan/edit/'.$id).'" tooltip="edit jabatan" flow="right">
                                          <i class="icon-pencil5 text-info-400 mr-1"></i>
                                      </a>'.$del;
                              ?>
                              <tr data-tt-id="<?php echo $row->id ?>" data-tt-parent-id="<?php echo $row->parent ?>">
                                    <th class="text-nowrap" class="pr-1"><?php echo $aksi ?></th>
                                    <td class="text-nowrap"><span class="<?php echo $class ?>">
                                          <i class="icon-user <?php echo $color ?>"></i> <?php echo $row->nama_jabatan ?></span>
                                    </td>
                                    <td class="text-nowrap" >
                                        <div class="alert alert-danger alert-dismissible py-0 px-1 m-0">
                                            Jabatan Kosong
                                          </div>
                                    </td>
                                    <td><?php echo status_tree($row->status) ?></td>
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