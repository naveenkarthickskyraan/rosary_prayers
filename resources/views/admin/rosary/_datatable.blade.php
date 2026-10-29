<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<style>
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter { margin-bottom: 12px; }
    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #ced4da; border-radius: 4px; padding: 5px 8px; outline: none;
    }
    .dataTables_wrapper .dataTables_info { padding-top: 14px; color: #6c757d; }
    .dataTables_wrapper .dataTables_paginate { padding-top: 10px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #dee2e6 !important; border-radius: 4px;
        background: #fff !important; color: #162133 !important;
        padding: 5px 12px; margin: 0 2px; cursor: pointer;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9ecef !important; color: #162133 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #162133 !important; color: #fff !important; border-color: #162133 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background: #f8f9fa !important; color: #adb5bd !important; cursor: default;
    }
    table.dataTable thead th { border-bottom: 1px solid #111 !important; }
</style>
<script>
(function () {
    function loadScript(src, done) {
        var s = document.createElement('script');
        s.src = src;
        s.onload = done;
        document.body.appendChild(s);
    }

    function startTables() {
        jQuery('.js-datatable').each(function () {
            if (jQuery.fn.dataTable.isDataTable(this)) { return; }
            jQuery(this).DataTable({
                pageLength: 20,
                lengthMenu: [10, 20, 50, 100],
                order: [],
                columnDefs: [{ orderable: false, targets: 'no-sort' }],
                language: { search: 'Search:', emptyTable: 'No data found' }
            });
        });
    }

    function loadDataTables() {
        if (jQuery.fn.DataTable) { startTables(); return; }
        loadScript('https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js', startTables);
    }

    window.addEventListener('load', function () {
        if (window.jQuery) { loadDataTables(); }
        else { loadScript('https://code.jquery.com/jquery-3.7.1.min.js', loadDataTables); }
    });
})();
</script>