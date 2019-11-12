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
		      	"className" : "btn-danger btn-sm py-1"
		    },
		    "main" : {
		      	"label" : "<i class='icon-checkmark2'></i> Ya",
		      	"className" : "btn-primary btn-sm py-1",
		      	callback:function(result){
		        	if (result) {
						confirmAksi(id);
					}
		    	}
		    }
		}
	});
});


// toastr.options = {
//   "closeButton": false,
//   "debug": false,
//   "newestOnTop": false,
//   "progressBar": false,
//   "rtl": false,
//   "positionClass": "toast-top-full-width mt-2",
//   "preventDuplicates": false,
//   "onclick": null,
//   "showDuration": 300,
//   "hideDuration": 1000,
//   "timeOut": 5000,
//   "extendedTimeOut": 1000,
//   "showEasing": "swing",
//   "hideEasing": "linear",
//   "showMethod": "fadeIn",
//   "hideMethod": "fadeOut"
// };

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
