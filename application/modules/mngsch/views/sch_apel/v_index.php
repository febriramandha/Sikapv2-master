<style type="text/css">
.event-tooltip {
    width: 150px;
    background: rgba(0, 0, 0, 0.85);
    color: #FFF;
    padding: 10px;
    position: absolute;
    z-index: 10001;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 11px;

}

.fc-day-grid-event>.fc-content {
    padding: 0px;
}
</style>
<!-- Basic view -->
<div class="card">
    <div class="card-header bg-white header-elements-inline py-2">
        <h5 class="card-title">Jadwal Apel</h5>
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
        <div class="text-left">
            <a href="<?php echo base_url('mngsch/sch-apel/add') ?>" class="btn btn-sm btn-info"><i
                    class="icon-pen-plus mr-1"></i> Tambah Apel</a>
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
                                <th class="text-nowrap" width="1%">Tanggal(jam)</th>
                                <th class="text-nowrap" width="1%">Jenis Apel</th>
                                <th>Komandan Apel</th>
                                <th>Pengambilan Apel</th>
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
            <?php echo form_open('mngsch/sch-apel/AjaxSave','id="formAjax"'); ?>
            <input type="hidden" name="id">
            <input type="hidden" id="start" name="start">
            <input type="hidden" id="end" name="end">
            <input type="hidden" name="date_now" value="<?= date('d-m-Y'); ?>">
            <div class="modal-body">
                <div class="alert alert-warning alert-dismissible p-2">
                    <span class="font-weight-semibold">Perhatian!</span> Data yang telah diinputkan hanya dapat diubah
                    pada H-1 !
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Tanggal Apel <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <div class="form-group-feedback form-group-feedback-left">
                            <div class="form-control-feedback">
                                <i class="icon-pencil3"></i>
                            </div>
                            <input type="text" name="tgl_apel" class="form-control" placeholder="isi tanggal apel"
                                disabled="">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Jam Apel <span class="text-danger">*</span></label>
                    <div class="col-lg-4">
                        <div class="form-group-feedback form-group-feedback-left">
                            <div class="form-control-feedback">
                                <i class="icon-pencil3"></i>
                            </div>
                            <input type="text" name="start_time" id="start_time" class="form-control clockpicker"
                                placeholder="isi jam mulai apel" value="07:31">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group-feedback form-group-feedback-left">
                            <div class="form-control-feedback">
                                <i class="icon-pencil3"></i>
                            </div>
                            <input type="text" name="end_time" id="end_time" class="form-control clockpicker"
                                placeholder="isi jam berakhir apel">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Jenis Apel <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <div class="form-group">
                            <select class="form-control select-search" name="jenis_apel_id" id="jenis_apel_id">
                                <option disabled="" selected="">Pilih Jenis Apel</option>
                                <?php  foreach($jenis_apel as $row) {?>
                                <option value="<?php echo encrypt_url($row->id,'jenis_apel'); ?>">
                                    <?php echo $row->name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Pengambilan Apel <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <div class="form-group-feedback form-group-feedback-left">
                            <div class="form-control-feedback">
                                <i class="icon-pencil3"></i>
                            </div>
                            <input type="text" name="pengambilan_apel" class="form-control"
                                placeholder="Pengambilan Apel">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Komandan Apel <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <div class="form-group-feedback form-group-feedback-left">
                            <div class="form-control-feedback">
                                <i class="icon-pencil3"></i>
                            </div>
                            <input type="text" name="komandan_apel" class="form-control" placeholder="Komandan Apel">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Unit Kerja <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <div class="form-group">
                            <select class="form-control multiselect-clickable-groups instansi" name="instansi[]"
                                id="instansi" multiple="multiple" id="filter_list_dropdwn" data-fouc>
                                <?php foreach ($instansi as $row) { ?>
                                <option class="tes" value="<?php echo $row->id ?>"
                                    parent_id="<?php echo $row->parent_id ?>">
                                    <?php echo '['.$row->level.']'.carakteX($row->level, '-','|').filter_path($row->path_info)." ".strtoupper($row->dept_name) ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Kategori Pengguna <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <div class="form-group">
                            <select class="form-control select-nosearch result" name="kategori" id="ketegori">
                                <option value="0">Semua..</option>
                                <option value="1">PNS</option>
                                <option value="2">NON PNS</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Kondisi Cuaca <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <div class="form-group">
                            <select class="form-control select-nosearch result" name="kondisi_cuaca" id="kondisi_cuaca">
                                <option value="0">Tidak Hujan</option>
                                <option value="1">Hujan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Buka Jadwal <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <div class="form-group">
                            <select class="form-control select-nosearch result" name="status" id="status">
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-lg-3">Keterangan <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                        <div class="form-group">
                            <textarea name="keterangan" id="keterangan" class="form-control"
                                placeholder="Keterangan"></textarea>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="mod" value="add">
            </div>
            <div class="modal-footer bg-white">
                <span id="btn-del"></span>
                <button type="button" class="btn btn-sm bg-orange-300 result" data-dismiss="modal">Batal <i
                        class="icon-cross3 ml-2"></i></button>
                <button type="submit" class="btn btn-sm btn-info result">Simpan <i
                        class="icon-checkmark4 ml-2"></i></button>
                <i class="icon-spinner2 spinner" style="display: none" id="spinner"></i>

            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<!-- /basic modal -->

<script type="text/javascript">
$('.clockpicker').clockpicker({
    placement: 'bottom',
    align: 'left',
    autoclose: true,
});

$('.multiselect-clickable-groups').multiselect({
    includeSelectAllOption: true,
    enableClickableOptGroups: true,
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
    placeholder: 'Pilih Data',
});

$('.multiselect-item').on('click', function(event) {
    // logic created by  rian reski
    var a = $(this).find('[type="checkbox"]:checked');
    var vala = a.val();
    var t = $('#textbox' + vala).val();

    if (vala !== undefined && t !== undefined) {
        var selected = $('#filter_list_dropdwn').attr('selected', 'selected').val();
        //alert(selected)
        var ya;
        var len = selected.length;
        for (i = 0; i < len; i++) {
            if (selected[i] == vala) {
                ya = true;
            }
        }
        //alert(ya)
        $options = $('#filter_list_dropdwn option');
        if (t == vala && ya != true || t == 0 && ya != true) {
            if (t == vala) {
                //$("#filter_list_dropdwn").val(vala);
                $options.filter('[value="' + vala + '"]').prop('selected', true);
                $('#filter_list_dropdwn option[parent_id=' + vala + ']').each(function() {
                    $(this).prop('selected', true);

                });

                for (i = 0; i < len; i++) {
                    // $("#filter_list_dropdwn").val(selected[i]);
                    $options.filter('[value="' + selected[i] + '"]').prop('selected', true);
                }

                $('#textbox' + vala).val(0);
                $('#filter_list_dropdwn').multiselect("refresh");
            }

            if (t == 0) {
                //alert(ya)
                $('#filter_list_dropdwn option[parent_id=' + vala + ']').each(function() {
                    $(this).prop('selected', false);
                });
                $('#textbox' + vala).val(vala);
                $('#filter_list_dropdwn').multiselect("refresh");
            }
        }
    }

});

$(function() {

    var currentDate; // Holds the day clicked when adding a new event
    var currentEvent; // Holds the event object when editing an event

    // Fullcalendar
    $('#fullcalendar-external').fullCalendar({
        // Get all events stored in database
        eventLimit: false, // allow "more" link when too many events
        events: uri_dasar + 'mngsch/sch-apel/getEvents',
        selectable: true,
        selectHelper: true,
        editable: true, // Make the event resizable true         
        select: function(start, end) {

            $('#start').val(moment(start).format('YYYY-MM-DD'));
            $('#end').val(moment(end).format('YYYY-MM-DD'));
            $('input[name="tgl_apel"]').val(moment(start).format('DD-MM-YYYY'));
            // Open modal to add event
            modal();
        },

        eventDrop: function(event, delta, revertFunc, start, end) {

            start = event.start.format('YYYY-MM-DD');
            if (event.end) {
                end = event.end.format('YYYY-MM-DD');
            } else {
                end = start;
            }

            $.get(uri_dasar + 'mngsch/sch-apel/dragUpdateEvent', {
                id: event.id,
                start: start,
                end: end
            }, function(result) {
                bx_alert_ok('data berhasil diperbarui', 'success');
                table.ajax.reload();
            });



        },
        // Event Mouseover
        eventMouseover: function(calEvent, jsEvent, view) {
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
    function modal(data, aksi) {
        // Set input values
        $('input[name="id"]').val(data ? data.id : '');
        $('[name="pengambilan_apel"]').val(data ? data.pengambilan_apel : '');
        $('[name="komandan_apel"]').val(data ? data.komandan_apel : '');

        $('[name="keterangan"]').val(data ? data.ket : '');
        $("#instansi").multiselect("clearSelection");
        // $("#kategori").val("");           
        // $('#jenis_apel_id:selected', this).removeAttr('selected');
        // $('#kategori:selected', this).removeAttr('selected');
        // $('#jenis_apel_id').select2("val", "");
        $('[name="end_time"]').val(data ? data.end_time : '');

        $('.modal-footer').show();


        // Create Butttons
        if (data) {
            var date_now = $('input[name="date_now"]').val();
            var date_select = moment(currentEvent.start).format('DD-MM-YYYY');
            $('.modal-title').html('Edit Jadwal Apel');
            $('input[name="tgl_apel"]').val(moment(currentEvent.start).format('DD-MM-YYYY'));
            $('input[name="pengambilan_apel"]').val(data.pengambilan_apel);
            $('input[name="komandan_apel"]').val(data.komandan_apel);
            $('#ketegori').val(data.kategori).change();
            $('#keterangan').val(data.ket).change();
            $('#jenis_apel_id').val(data.jenis_apel_id).change();
            $('#status').val(data.status).change();

            $('#start_time').val(data.start_time);
            $('#end_time').val(data.end_time);


            for (var i = 0; i < data.dept_id.length; i++) {
                $('#instansi').multiselect('select', data.dept_id[i]);
            }

            $('#instansi').val(data.dept_id);

            $('input[name="mod"]').val('edit');


            //    if(Date.parse(date_now) == Date.parse(date_select))
            //    {
            //        $('.modal-footer').hide();
            //    }else if(Date.parse(date_now) > Date.parse(date_select)) {

            //        $('.modal-footer').hide();
            //    }else {
            //        $('.modal-footer').show();
            //    }


            $('#btn-del').html(
                '<button type="button" class="confirm-aksi btn btn-sm btn-danger" msg="Benar ingin hapus data ini?" id="' +
                currentEvent.id + '"><i class="ico fa fa-trash"></i> Hapus</button>');
        } else {
            $('.modal-title').html('Tambah Jadwal Apel');
            $('input[name="mod"]').val('add');
            $('#btn-del').html('');
        }
        //Show Modal
        $('#modalkalender').modal('show');
    }


});

$(document).ready(function() {
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
        "lengthMenu": [
            [10, 25, 50, 100, 200],
            [10, 25, 50, 100, 200]
        ],
        ajax: {
            url: uri_dasar + 'mngsch/sch-apel/indexJson',
            type: "post",
            "data": function(data) {
                data.csrf_sikap_token_name = csrf_value;
            },
        },
        "columns": [{
                "data": "id",
                searchable: false
            },
            {
                "data": "tgl_apel",
                searchable: false
            },
            {
                "data": "name",
                searchable: false
            },
            {
                "data": "komandan_apel",
                searchable: false
            },
            {
                "data": "pengambilan_apel",
                searchable: false
            },
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
            $('td', row).eq(2).addClass('text-nowrap');
        },


    });

    // Initialize
    dt_componen();
});

$('#formAjax').submit(function() {
    var result = $('.result');
    var spinner = $('#spinner');
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "JSON",
        error: function() {
            result.attr("disabled", false);
            spinner.hide();
            bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        beforeSend: function() {
            result.attr("disabled", true);
            spinner.show();
        },
        success: function(res) {
            if (res.status == true) {
                table.ajax.reload();
                bx_alert_ok(res.message, 'success');
                $('#modalkalender').modal('hide');
                $('#fullcalendar-external').fullCalendar("refetchEvents");
            } else {
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
        url: uri_dasar + 'mngsch/sch-apel/ajaxDel',
        data: {
            id: id
        },
        dataType: "json",
        error: function() {
            bx_alert('gagal menghubungkan ke server cobalah mengulang halaman ini kembali');
        },
        success: function(res) {
            if (res.status == true) {
                table.ajax.reload();
                bx_alert_ok(res.message, 'success');
                $('#modalkalender').modal('hide');
                $('#fullcalendar-external').fullCalendar("refetchEvents");

            } else {
                bx_alert(res.message);
            }

        }
    });
}
</script>