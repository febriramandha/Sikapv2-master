<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Edit Data Verifikator</h5>
		<div class="header-elements">
			<div class="list-icons">
        		<a class="list-icons-item" data-action="collapse"></a>
        	</div>
    	</div>
	</div>

	<div class="card-body">
    <div class="row">
      <div class="col-lg-5">
          <h4 class="font-weight-semibold mb-1">Pegawai:</h4>
          <?php echo nama_icon_nip($user->nama, $user->gelar_dpn, $user->gelar_blk,$user->nip,'','',$user->jabatan) ?>
      </div>
      <div class="col-lg-1">
          <i class="icon-arrow-right16 mr-3 ml-3"></i>
      </div>
      <div class="col-lg-5">
          <?php if ($verifikator): ?>
            <h4 class="font-weight-semibold mb-1">Verifikator: <a href="#" id="<?php echo encrypt_url($user->id,'user_id') ?>" class="confirm-aksi btn btn-sm bg-warning-300" msg="Benar ingin hapus data ini?" ><i class="icon-bin"></i></a> </h4>
            <?php echo nama_icon_nip($verifikator->nama, $verifikator->gelar_dpn, $verifikator->gelar_blk,$verifikator->nip,'','',$verifikator->jabatan) ?>
          <?php endif ?>
      </div>
    </div>
    <hr>
		<?php echo form_open('kepegawaian/verifikator/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2">Edit Verifikator <span class="text-danger">*</span></label>
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
          
          <input type="hidden" name="mod" value="edit">
          <input type="hidden" name="id" value="<?php echo encrypt_url($user->id,'user_id') ?>">
          <div class="text-left offset-lg-2" >
              <a href="javascript:history.back()" class="btn btn-sm bg-success-300 result">Kembali <i class="icon-arrow-left5 ml-2"></i></a>               
              <button type="submit" class="btn btn-sm btn-info result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
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
      text: item.nama +"(" + item.nip + ") " +item.jabatan,
      html: [ 
          $('<img>').attr('src', item.icon).css("height", 18), ' ',
          item.nama+'('+item.nip+')'+ item.jabatan 
        ] 
    };
  },
  events: {
    search: function (qry, callback) {
      // let's do a custom ajax call
      var user = $('[name="id"]').val();
      $.ajax(
        uri_dasar+'kepegawaian/verifikator/AjaxGet',
        {
          data: {modul:"listverifikator", 'qry': qry, id:user},
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
                bx_alert_successUpadate(res.message, 'kepegawaian/verifikator');
            }else {
                bx_alert(res.message);
            }
            result.attr("disabled", false);
            spinner.hide();
        }
    });
    return false;
});

function confirmAksi(id) {
        $.ajax({
            url: uri_dasar+'kepegawaian/verifikator/AjaxDel',
            data: {id: id},
            dataType :"json",
            error:function(){
             bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
          },
            success: function(res){
                if (res.status == true) {
                   bx_alert_successUpadate(res.message, 'kepegawaian/verifikator');
                }else {
                    bx_alert(res.message);
                }
                
            }
        });
    }
</script>