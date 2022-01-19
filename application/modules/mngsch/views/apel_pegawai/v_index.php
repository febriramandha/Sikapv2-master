<!-- Basic table -->
<div class="card">
    <div class="card-header bg-white header-elements-inline py-2">
        <h5 class="card-title">Jadwal Apel Pagi</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-sm table-hover table-bordered">
                <thead>
                    <tr class="table-active">
                        <th width="1%">No</th>
                        <th class="text-nowrap col-lg-8">Jadwal Apel</th>
                        <th class="text-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /basic table -->

<script type="text/javascript">
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
            url: uri_dasar + 'mngsch/apel_pegawai/indexJson',
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
                "data": "sch_name",
                searchable: false
            },
            {
                "data": "action",
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
            $('td', row).eq(1).addClass('text-nowrap');
        },


    });

    // Initialize
    dt_componen();
});
</script>