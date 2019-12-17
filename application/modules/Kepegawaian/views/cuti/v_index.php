<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Cuti Pegawai</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">

		<?php echo form_open('kepegawaian/cuti/AjaxGet','class="form-horizontal" id="formAjax"'); ?>
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Instansi <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group">
                       <select class="form-control select-search" name="instansi" >  
                              <option disabled="">Pilih Instansi</option> 
                              <?php foreach ($instansi as $row) { ?>
                                <option value="<?php echo encrypt_url($row->id,'instansi') ?>"><?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?></option>
                              <?php } ?>
                      </select> 
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                        <select class="form-control advanced2AutoComplete" type="text" autocomplete="off" placeholder="Cari NIP/Nama" name="user">
                          <option></option>
                        </select>
                      <span><i>* cari nip atau nama pegawai</i></span>
                  </div>
              </div>
          </div>
          <input type="hidden" name="mod" value="next">
          <div class="text-left offset-lg-2" >
              <button type="reset" class="btn btn-sm bg-orange-300 result">Batal <i class="icon-cross3 ml-2"></i></button>                 
              <button type="submit" class="btn btn-sm btn-info result">Pilih dan Lanjutkan <i class="icon-next2 ml-2"></i></button>
              <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
          </div>
        </div>
        <?php echo form_close() ?>  
	</div>
</div>
<script type="text/javascript">
$('.advanced2AutoComplete').autoComplete({
	  resolver: 'custom',
	  formatResult: function (item) {
	    return {
	      value: item.id,
	      text: item.nama +"(" + item.nip + ")",
	      html: [ 
	          $('<img>').attr('src', item.icon).css("height", 18), ' ',
	          item.nama+'('+item.nip+')'
	        ] 
	    };
  },
  events: {
    search: function (qry, callback) {
      // let's do a custom ajax call
      var user = $('[name="instansi"]').val();
      $.ajax(
        uri_dasar+'kepegawaian/cuti/AjaxGet',
        {
          data: {modul:"listuser", 'qry': qry, id:user},
          dataType :"JSON",
        }
      ).done(function (res) {
        callback(res.results);
      });
    }
  }
});

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
                window.location.assign(uri_dasar+'kepegawaian/cuti/view/'+res.user);
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
