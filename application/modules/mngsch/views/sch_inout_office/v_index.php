<style type="text/css">
  .event-tooltip {
    width:150px;
    background: rgba(0, 0, 0, 0.85);
    color:#FFF;
    padding:10px;
    position:absolute;
    z-index:10001;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 11px;

  }
  .fc-day-grid-event > .fc-content {
   padding: 0px;
 }

</style>
<!-- Basic view -->
<div class="card">
	<div class="card-header bg-white header-elements-inline py-2">
		<h5 class="card-title">Jadwal Absen Luar Kantor</h5>
		<div class="header-elements">
			<div class="list-icons">
        <a class="list-icons-item" data-action="collapse"></a>
      </div>
    </div>
  </div>

  <div class="card-body">
    <div class="alert alert-warning alert-dismissible">
    	<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    	<span class="font-weight-semibold">info!</span> Klik tanggal untuk melakukan aksi
    </div>
    <div class="row">
    	<div class="col-md-12">
    		<div id="fullcalendar-external"></div>
    	</div>
    </div>
  </div>
</div>
<!-- /basic view -->

<!-- Basic modal -->
<div id="modalkalender" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-white">
        <h5 class="modal-title">Title</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <?php echo form_open('mngsch/sch-inout-office/AjaxSave','id="formAjax"'); ?>
      <input type="hidden" name="id">
      <input type="hidden" id="start" name="start">
      <input type="hidden" id="end" name="end">
      <div class="modal-body">
         <div class="form-group row">
            <label class="col-form-label col-lg-2">Ket <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <div class="form-group-feedback form-group-feedback-left">
                <div class="form-control-feedback">
                  <i class="icon-pencil3"></i>
                </div>
                 <input type="text" name="ket" class="form-control" placeholder="Isi keterangan"/>
              </div>
            </div>
        </div>
        <div class="form-group row">
              <label class="col-form-label col-lg-2">Pegawai <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                  <div class="form-group">
                       <select class="form-control multiselect-clickable-groups" name="user[]" multiple="multiple" id="filter_list_dropdwn" data-fouc>
                             <?php foreach ($user as $row) { ?>
                                <option value="<?php echo encrypt_url($row->id,'user_id_office') ?>"><?php echo nama_gelar($row->nama,$row->gelar_dpn, $row->gelar_blk) ?>(<?php echo $row->nip ?>)</option>
                              <?php } ?>
                        </select>
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <div class="col-lg-3">
                    <label class="pure-material-checkbox"> 
                        <input type="checkbox"  name="cekin"  /> <span>Absen Masuk</span>
                    </label>
              </div>
              <div class="col-lg-3">
                    <label class="pure-material-checkbox"> 
                        <input type="checkbox" name="cekout"/> <span>Absen Pulang</span>
                    </label>
              </div>
          </div>     

        <input type="hidden" name="mod" value="add">
      </div>
      <div class="modal-footer bg-white">
        <span id="btn-del"></span>
        <button type="button" class="btn btn-sm bg-orange-300 result" data-dismiss="modal">Batal <i class="icon-cross3 ml-2"></i></button>                 
        <button type="submit" class="btn btn-sm btn-info result" id="simpan">Simpan <i class="icon-checkmark4 ml-2"></i></button>
        <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>  

      </div>

    </div>
    <?php echo form_close(); ?>
  </div>
</div>
<!-- /basic modal -->


<script type="text/javascript">
  $(function(){

    $('.multiselect-clickable-groups').multiselect({
        includeSelectAllOption: true,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        placeholder: 'Pilih Pegawai',
    });

    var currentDate; // Holds the day clicked when adding a new event
    var currentEvent; // Holds the event object when editing an event

    // Fullcalendar
    $('#fullcalendar-external').fullCalendar({
        // Get all events stored in database
        eventLimit: false, // allow "more" link when too many events
        events: uri_dasar+'mngsch/sch-inout-office/getEvents',
        selectable: true,
        selectHelper: true,
        editable: true, // Make the event resizable true         
        select: function(start, end) {

          $('#start').val(moment(start).format('YYYY-MM-DD'));
          $('#end').val(moment(end).format('YYYY-MM-DD'));
          $('input[name="tanggal"]').val(moment(end).format('DD-MM-YYYY'));
                 // Open modal to add event
                 modal();
               }, 

               eventDrop: function(event, delta, revertFunc,start,end) {  

                start = event.start.format('YYYY-MM-DD');
                if(event.end){
                  end = event.end.format('YYYY-MM-DD');
                }else{
                  end = start;
                }         

                $.get(uri_dasar+'mngsch/sch-inout-office/dragUpdateEvent',{                            
                  id:event.id,
                  start : start,
                  end :end
                }, function(result){
                  bx_alert_ok('data berhasil diperbarui','success');
                  table.ajax.reload();
                });



              }, 
        // Event Mouseover
        eventMouseover: function(calEvent, jsEvent, view){

          var tooltip = '<div class="event-tooltip">' + calEvent.ket + '</div>';
          $("body").append(tooltip);

          $(this).mouseover(function(e) {
            $(this).css('z-index', 10000);
            $('.event-tooltip').fadeIn('500');
            $('.event-tooltip').fadeTo('10', 1.9);
          }).mousemove(function(e) {
            $('.event-tooltip').css('top', e.pageY + 10);
            $('.event-tooltip').css('left', e.pageX + 20);
          });
        },
        eventMouseout: function(calEvent, jsEvent) {
          $(this).css('z-index', 8);
          $('.event-tooltip').remove();
        },
        // Handle Existing Event Click
        eventClick: function(calEvent, jsEvent, view) {
            // Set currentEvent variable according to the event clicked in the calendar
            currentEvent = calEvent;

            // Open modal to edit or delete event
            modal(currentEvent);
          }

        });

    // Prepares the modal window according to data passed
    function modal(data,aksi) {
        // Set input values
        $('input[name="id"]').val(data ? data.id : '');        
        $('#modalkalender').modal('show');

        $('form#formAjax').trigger("reset"); //Line1
        $('form#formAjax #filter_list_dropdwn').trigger("change"); //Line2
        $("form#formAjax #filter_list_dropdwn").multiselect('clearSelection');
        // Create Butttons
        if (data) {
          $('#div_pegawai').html(data ? data.ket : '');
          $('[name="ket"]').val(data.title)
          $('[name="cekin"]').prop('checked',data.in=="1" ? true : false);
          $('[name="cekout"]').prop('checked',data.out=="1" ? true : false);
          //$('#modalkalender2').modal('show');
          $('.modal-title').html('Lihat Jadwal');
          $options = $('#filter_list_dropdwn option');

          len = data.pegawai.length;
          for(i=0; i < len; i++){
               $options.filter('[value="'+data.pegawai[i]+'"]').prop('selected', true);
          }
                       
          $('#filter_list_dropdwn').multiselect("refresh");


          $('#modalkalender input[name="mod"]').val('edit');
          $('#modalkalender #btn-del').html('<button type="button" class="confirm-aksi btn btn-sm btn-danger" msg="Benar ingin hapus data ini?" id="'+ currentEvent.id+'"><i class="ico fa fa-trash"></i> Hapus</button>');
          $('#simpan').html('Update <i class="icon-checkmark4 ml-2"></i>');
        }else {
          //Show Modal
          
          $('.modal-title').html('Tambah Jadwal');
          $('input[name="mod"]').val('add');
          $('#btn-del').html('');
          $('[name="cekin"]').prop('checked',true);
          $('[name="cekout"]').prop('checked',true);

          $('#simpan').html('Simpan <i class="icon-checkmark4 ml-2"></i>');
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
        bx_alert_ok(res.message,'success');
        $('#modalkalender').modal('hide');
        $('#fullcalendar-external').fullCalendar("refetchEvents");
        $('form#formAjax').trigger("reset"); //Line1
        $('form#formAjax #filter_list_dropdwn').trigger("change"); //Line2
        $("form#formAjax #filter_list_dropdwn").multiselect('clearSelection');
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
      url: uri_dasar+'mngsch/sch-inout-office/ajaxDel',
      data: {id: id},
      dataType :"json",
      error:function(){
       bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
     },
     success: function(res){
      if (res.status == true) {
        bx_alert_ok(res.message,'success');
        $('#modalkalender').modal('hide');
        $('#fullcalendar-external').fullCalendar("refetchEvents");

      }else {
        bx_alert(res.message);
      }

    }
  });
  }

</script>
