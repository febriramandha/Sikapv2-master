
<ul class="nav nav-tabs nav-tabs-solid border-0">
	<li class="nav-item col-md-2 p-0">
		<a href="#tab1" class="tab_profil nav-link legitRipple active show" id="biodata" data-toggle="tab">
			<i class="icon-user mr-2 text-blue-400"></i>
			Bioadata
		</a>
	</li>
	<li class="nav-item col-md-2 p-0">
		<a href="#tab1" class="tab_profil nav-link legitRipple" id="verifikator" data-toggle="tab">
			<i class="icon-user-tie mr-2"></i>
			Verifikator
		</a>
	</li>
	<li class="nav-item col-md-2 p-0">
		<a href="#tab1" class="tab_profil nav-link legitRipple" id="akun" data-toggle="tab">
			<i class="icon-key mr-2"></i>
			Akun
		</a>
	</li>
	<li class="nav-item col-md-3 p-0">
		<a href="#" class="tab_profil nav-link legitRipple " data-toggle="tab" id="perangkat">
			<i class="icon-screen3 mr-2"></i>
			Perangkat Saya
		</a>
	</li>

</ul>

<div id="load"></div>

<script type="text/javascript">
	
	$(document).ready(function() {
		load_profil('biodata');
	});



	$('.tab_profil').click(function(){ 
		var id = $(this).attr('id');
		load_profil(id);
	});

	function load_profil(id) {
		$.ajax({
			url: "<?php echo site_url('app/profile/AjaxGet') ?>",
			data: {id: id},
			dataType :"html",
			error:function(){
				$('#load').unblock();
			},
			beforeSend:function(){
				load_dt('#load');
			},
			success: function(res){
				$('#load').html(res);
			}
		});
	}
	
</script>