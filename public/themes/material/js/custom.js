/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

 $(document).on('click', '.confirm-aksi', function(){
	var id = $(this).attr("id");
	var msg = $(this).attr("msg");
    bootbox.dialog({
	  	title:"Konfirmasi",
	  	message: msg,
		buttons: {
		    "cancel" : {
		      	"label" : "<i class='icon-cross3'></i> Tidak",
		      	"className" : "btn-danger"
		    },
		    "main" : {
		      	"label" : "<i class='icon-checkmark2'></i> Ya",
		      	"className" : "btn-primary",
		      	callback:function(result){
		        	if (result) {
						confirmAksi(id);
					}
		    	}
		    }
		}
	});
});


// for sidebar menu entirely but not cover treeview
$('ul.nav-sidebar a').filter(function() {
	var tesulr = $(this).attr('href');
  return tesulr == url1;
}).addClass('active');

// for treeview
$('li.treeview-menu a').filter(function() {
	  var tesulr = $(this).attr('href');
	  return tesulr == url1 || tesulr.substr(tesulr.lastIndexOf("/")+1) == pgclass;
}).closest('.treeview-menu').addClass('nav-item-expanded nav-item-open');

function dt_componen() {
	$('.dataTables_length select').select2({
	    minimumResultsForSearch: Infinity,
	    dropdownAutoWidth: true,
	    width: 'auto'
	});
}

$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
{
    return {
        "iStart": oSettings._iDisplayStart,
        "iEnd": oSettings.fnDisplayEnd(),
        "iLength": oSettings._iDisplayLength,
        "iTotal": oSettings.fnRecordsTotal(),
        "iFilteredTotal": oSettings.fnRecordsDisplay(),
        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
    };
};


function load_dt(load) {
	$(load).block({
        message: '<i class="icon-spinner9 spinner"></i> Loading..',
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.8,
            cursor: 'wait'
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'none'
        }
    });
}

$('.select-search').select2();

$('.select-nosearch').select2({
	minimumResultsForSearch: Infinity,
	placeholder: 'Pilih Data',
	allowClear: true,
});

$('.trim').bind('input', function(){
    $(this).val(function(_, v){
        return v.trim();
    });
});

// Initialize
var elems = Array.prototype.slice.call(document.querySelectorAll('.form-control-switchery'));
elems.forEach(function(html) {
    var switchery = new Switchery(html);
});

function bx_alert(msg) {
	bootbox.alert({
   		title: "Peringatan!",
	    message: msg,
	    closeButton: false,
	    buttons: {
	      ok: {
	        label: 'Baiklah',
	        className: 'btn-info',
	      }
	    },
	});
}


function bx_alert_ok(msg, clasic) {

	var icon = '';
	if(clasic=='success') {
		 icon = '<i class="icon-checkmark4 ml-2 text-'+clasic+'"></i> ';
	}

	bootbox.dialog({
	    message: icon+msg,
	    timeOut : 5000,
	    closeButton: true
	});
}

function bx_alert_success(msg, redirec) {
	bootbox.dialog({
	    message: '<i class="icon-checkmark4 ml-2 text-success"></i> '+msg,
	    closeButton: false,
	    buttons: {
	      add: {
	        label: '<i class="icon-pen-plus mr-1"></i> Tambah data',
	        className: 'btn-info',
	        callback:function(result){
	        	if (result) {
					location.reload();
				}
	    	}
	      },
	      main: {
	        label: '<i class="icon-arrow-left5 mr-1"></i> Kembali',
	        className: 'bg-success-400',
	        callback:function(result){
	        	if (result) {
					window.location.href = uri_dasar+redirec;
				}
	    	}
	      }
	    },
	});
}

function bx_alert_successUpadate(msg, redirec) {
	bootbox.dialog({
	    message: '<i class="icon-checkmark4 ml-2 text-success"></i> '+msg,
	    closeButton: false,
	    buttons: {
	      main: {
	        label: '<i class="icon-arrow-left5 mr-1"></i> Kembali',
	        className: 'bg-success-400',
	        callback:function(result){
	        	if (result) {
					window.location.href = uri_dasar+redirec;
				}
	    	}
	      }
	    },
	});
}

  
