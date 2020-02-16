<!-- Default unordered list markup -->
<div class="card">
	<div class="card-header header-elements-inline">
		<h6 class="card-title">Unordered list markup</h6>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
				<a class="list-icons-item" data-action="reload"></a>
				<a class="list-icons-item" data-action="remove"></a>
			</div>
		</div>
	</div>




	<div class="card-body">
		<p class="mb-3">Current example</p>

		<div class="tree-default card card-body border-left-info border-left-2 shadow-0 rounded-left-0">
			<?php 
			//index elements by id
			foreach ($instansi as $item) {
			    //$item['subs'] = array();
			    $indexedItems[$item->id] = (object) $item;
			}
			//assign to parent
			$topLevel = array();
			foreach ($indexedItems as $item) {
			    if ($item->parent_id == 0) {
			        $topLevel[] = $item;
			    } else {
			        $indexedItems[$item->parent_id]->subs[] = $item;
			    }
			}
			 echo renderMenu($topLevel);
			?>
		</div>
	</div>
</div>
<script type="text/javascript">
// Basic example
$('.tree-default').fancytree({
    init: function(event, data) {
        $('.has-tooltip .fancytree-title').tooltip();
    }
});
</script>