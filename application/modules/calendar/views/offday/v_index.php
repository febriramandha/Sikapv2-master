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
		<h5 class="card-title">Kelender Libur</h5>
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
    	<div class="col-md-8">
    		<div id="fullcalendar-external"></div>
    	</div>
    	<div class="col-md-4">
    		<div class="table-responsive">
    			<table id="datatable" class="table table-sm table-hover">
    				<thead>
    					<tr>
    						<th width="1%">No</th>
    						<th width="1%">Tanggal</th>
                <th class="text-nowrap">nama libur</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /basic view -->

<!-- Basic modal -->
<div id="modalkalender" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-white">
        <h5 class="modal-title">Title</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <?php echo form_open('calendar/offday/AjaxSave','id="formAjax"'); ?>
      <input type="hidden" name="id">
      <input type="hidden" id="start" name="start">
      <input type="hidden" id="end" name="end">
      <div class="modal-body">
        <div class="form-group row">
          <label class="col-form-label col-lg-3">nama libur <span class="text-danger">*</span></label>
          <div class="col-lg-9">
            <div class="form-group-feedback form-group-feedback-left">
              <div class="form-control-feedback">
                <i class="icon-pencil3"></i>
              </div>
              <textarea name="ket" class="form-control" placeholder="Isi keterangan"></textarea>
            </div>
          </div>
        </div>
        <input type="hidden" name="mod" value="add">
      </div>
      <div class="modal-footer bg-white">
        <span id="btn-del"></span>
        <button type="button" class="btn btn-sm bg-orange-300 result" data-dismiss="modal">Batal <i class="icon-cross3 ml-2"></i></button>                 
        <button type="submit" class="btn btn-sm btn-info result">Simpan <i class="icon-checkmark4 ml-2"></i></button>
        <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>	

      </div>

    </div>
    <?php echo form_close(); ?>
  </div>
</div>
<!-- /basic modal -->

<script type="text/javascript">
  $(function(){

    var currentDate; // Holds the day clicked when adding a new event
    var currentEvent; // Holds the event object when editing an event

    // Fullcalendar
    $('#fullcalendar-external').fullCalendar({
        // Get all events stored in database
        eventLimit: false, // allow "more" link when too many events
        events: uri_dasar+'calendar/offday/getEvents',
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

                $.get(uri_dasar+'calendar/offday/dragUpdateEvent',{                            
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
        $('[name="ket"]').val(data ? data.ket : '');
        // Create Butttons
        if (data) {
          $('.modal-title').html('Edit Kalender');
          $('input[name="mod"]').val('edit');
          $('#btn-del').html('<button type="button" class="confirm-aksi btn btn-sm btn-danger" msg="Benar ingin hapus data ini?" id="'+ currentEvent.id+'"><i class="ico fa fa-trash"></i> Hapus</button>');
        }else {
          $('.modal-title').html('Tambah Kalender');
          $('input[name="mod"]').val('add');
          $('#btn-del').html('');
        }
        //Show Modal
        $('#modalkalender').modal('show');
      }


    });

  $(document).ready(function(){
   table = $('#datatable').DataTable({ 
       processing: true, 
       serverSide: true, 
       "ordering": false,
       "searching": false,
       language: {
        search: '<span></span> _INPUT_',
        searchPlaceholder: 'Cari...',
        processing: '<i class="icon-spinner9 spinner text-blue"></i> Loading..'
      },  
      "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
      ajax: {
       url : uri_dasar+'calendar/offday/indexJson',
       type:"post",
       "data": function ( data ) {	
        data.csrf_sikap_token_name= csrf_value;
      },
      },
      "columns": [
          {"data": "id", searchable:false},
          {"data": "start_date", searchable:false},
          {"data": "nama", searchable:false},
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
          $('td', row).eq(2).addClass('text-nowrap');
        },


      });

	 // Initialize
	 dt_componen();
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
        table.ajax.reload();
        bx_alert_ok(res.message,'success');
        $('#modalkalender').modal('hide');
        $('#fullcalendar-external').fullCalendar("refetchEvents");
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
      url: uri_dasar+'calendar/offday/ajaxDel',
      data: {id: id},
      dataType :"json",
      error:function(){
       bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
     },
     success: function(res){
      if (res.status == true) {
        table.ajax.reload();
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