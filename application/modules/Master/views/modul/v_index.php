<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
   <h5 class="card-title">Data Modul</h5>
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
        <tr>
          <th>Title</th>
          <th>Url</th>
          <th>Level</th>
          <th width="1%">Status</th>
          <th width="1%" class="text-nowrap">No Urut</th>
          <th width="1%">Aksi</th>
        </tr>
      </thead>
      <tbody>
       <?php foreach ($modul as $row ) { 
        $jum_sub = $row->jum_sub;
        if ($jum_sub) {
          $class = "folder";
        }else {
          $class = "file";
        }
        ?>
        <tr data-tt-id="<?php echo $row->id ?>" data-tt-parent-id="<?php echo $row->parent ?>">
          <td class="text-nowrap">
           <span class="<?php echo $class ?>">
            <?php echo $row->title ?>
          </span>
        </td>
        <td><?php echo $row->url ?></td>
        <td class="text-center"><?php echo $row->level ?></td>
        <td class="text-center"><?php echo status_tree($row->status) ?></td>
        <td class="text-center"><?php echo $row->position ?></td>
        <td class="text-nowrap">
          <a href="<?php echo base_url('master/modul/edit/'.$row->id) ?>">
            <i class="icon-pencil5 text-info-400 mr-1"></i>
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
</script>