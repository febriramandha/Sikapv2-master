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
		 <?php echo nama_icon_nip("Kominfo") ?>
    		<hr>

		<?php echo form_open('kepegawaian/cuti/AjaxSave','class="form-horizontal" id="formAjax"'); ?>
		<div class="col-lg-12">
          <div class="form-group row">
              <label class="col-form-label col-lg-2"> Rentang Waktu<span class="text-danger">*</span></label>
              <div class="col-lg-4">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="rank1" class="form-control datepicker" placeholder="tanggal mulai" >
                  </div>
              </div>
              <div class="col-lg-1">
                      <span>s/d</span>
              </div>
              <div class="col-lg-4">
                  <div class="form-group-feedback form-group-feedback-left">
                      <div class="form-control-feedback">
                        <i class="icon-pencil3"></i>
                      </div>
                      <input type="text" name="rank2" class="form-control datepicker" placeholder="tanggal berakhir" >
                  </div>
              </div>
          </div>
          <input type="hidden" name="id">
          <input type="hidden" name="mod" value="add">
          <div class="text-left offset-lg-2" >
              <button type="reset" class="btn btn-sm bg-orange-300 result">Batal <i class="icon-cross3 ml-2"></i></button>                 
              <button type="submit" class="btn btn-sm btn-info result" id="result">Simpan <i class="icon-pen-plus ml-2"></i></button>
              <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	
          </div>
        </div>
        <?php echo form_close() ?> 
        <hr>
        <div class="text-right mt-1">
				<button class="btn btn-sm bg-success-400 legitRipple pt-1 pb-1" id="cetak">
					<span><i class="icon-printer mr-2"></i> Cetak</span>
				</button> 
		</div>

		<div class="table-responsive">
			<table id="datatable" class="table table-sm table-hover table-bordered">
				<thead>
					<tr class="table-active">
						<th width="1%">No</th>
						<th class="text-nowrap">Tanggal Cuti</th>
						<th class="text-nowrap">Jenis Cuti</th>
						<th width="1%" style="font-size: 80%;">Jumlah Hari</th>
						<th width="1%">Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- /basic table -->
