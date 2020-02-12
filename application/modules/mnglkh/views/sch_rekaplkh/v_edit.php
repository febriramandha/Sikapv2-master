<!-- Basic table -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Edit Jadwal LKH Manual</h5>
		<div class="header-elements">
			<div class="list-icons">
        <a class="list-icons-item" data-action="collapse"></a>
      </div>
    </div>
  </div>

  <div class="card-body">
    <?php echo form_open('mnglkh/sch-rekaplkh/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
    <div class="col-lg-12">
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Nama Jadwal<span class="text-danger">*</span></label>
        <div class="col-lg-9">
          <div class="form-group-feedback form-group-feedback-left">
            <div class="form-control-feedback">
              <i class="icon-pencil3"></i>
            </div>
            <input type="text" name="nama" class="form-control" placeholder="Nama Jadwal" value="<?php echo $schabsenmanual->name ?>">
          </div>
        </div>
      </div>
      <div class="form-group row">
      <label class="col-form-label col-lg-2"> Rentang Waktu <span class="text-danger">*</span></label>
      <div class="col-lg-4">
        <div class="form-group-feedback form-group-feedback-left">
          <div class="form-group">
                 <select class="form-control select-nosearch result" name="tahun" readonly>  
                  <option disabled="">Pilih Tahun..</option> 
                   <?php foreach ($laporan_tahun as $row) { 
                     $tahun_cek =  tanggal_format($schabsenmanual->start_date,'Y');

                    ?> 
                    <option value="<?php echo $row->tahun ?>" <?php if ($row->tahun == $tahun_cek) { echo "selected";} ?>><?php echo $row->tahun ?></option> 
                  <?php } ?>
                </select> 
              </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="form-group-feedback form-group-feedback-left">
          <div class="form-group">
                 <select class="form-control select-nosearch result" name="bulan" readonly>  
                  <option disabled="">Pilih Bulan..</option> 
                  <?php for ($i=1; $i < 13; $i++) {
                      $bulan_cek =  (int)tanggal_format($schabsenmanual->start_date,'m');
                   ?>
                    <option value="<?php echo $i ?>" <?php if ($i == $bulan_cek) { echo "selected";} ?>><?php echo _bulan($i) ?></option>
                <?php } ?>
                </select> 
              </div>
        </div>
      </div>
    </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-2">Unit Kerja</label>
        <div class="col-lg-10">
          <div class="form-group">
            <div class="form-group-feedback form-group-feedback-left">
              <div class="form-control-feedback">
                <i class="icon-price-tag3"></i>
              </div>
              <input type="text" class="form-control" value="<?php echo $instansi->dept_name ?>" disabled>
            </div>
        </div>
      </div>
    </div>
  <div class="form-group row">
    <label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span></label>
    <div class="col-lg-10">
      <div class="form-group">
       <div class="table-responsive">
        <table id="datatable" class="table table-sm table-hover table-bordered">
          <thead>
            <tr class="table-active">
              <th width="1%">No</th>
              <th width="1%" class="text-center" ><label class="pure-material-checkbox"> <input type="checkbox" id="checkAll" /> <span></span></label>
              </th>
              <th class="text-nowrap">Nama(NIP)</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<input type="hidden" name="mod" value="edit">
<input type="hidden" name="instansi" value="<?php echo encrypt_url($instansi->id,'instansi') ?>">
<input type="hidden" name="id" value="<?php echo encrypt_url($schabsenmanual->id,'schlkhmanual_id') ?>">
<div class="text-left offset-lg-2" >
  <button type="reset" class="btn btn-sm bg-orange-300 result">Batal <i class="icon-cross3 ml-2"></i></button>                 
  <button type="submit" class="btn btn-sm btn-info result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
  <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
</div>
</div>
<?php echo form_close() ?> 
</div>
</div>

<script type="text/javascript">
  $('#checkAll').click(function () {    
    $('.checkbox').prop('checked', this.checked);  
  });

  $(document).ready(function(){
   table = $('#datatable').DataTable({ 
    processing: true, 
    serverSide: true, 
    "ordering": false,
    "paging": false,
    "searching": false,
    language: {
      search: '<span></span> _INPUT_',
      searchPlaceholder: 'Cari...',
      processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
    },  
    ajax: {
      url : uri_dasar+'mnglkh/sch-rekaplkh/PegawaiJsonEdit/<?php echo $this->uri->segment(4) ?>',
      type:"post",
      "data": function ( data ) { 
        data.csrf_sikap_token_name= csrf_value;
        data.instansi=$('[name="instansi"]').val();
        
      },
    },
    "columns": [
    {"data": "id", searchable:false},
    {"data": "cekbox", searchable:false},
    {"data": "nama_nip", searchable:false},
    ],
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      var index = page * length + (iDisplayIndex + 1);
      $('td:eq(0)', row).html(index);
    },
    createdRow: function(row, data, index) {
          // $('td', row).eq(5).addClass('text-center');
          $('td', row).eq(1).addClass('text-nowrap');
        },


      });
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
                  bx_alert_successUpadate(res.message, 'mnglkh/sch-rekaplkh');
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