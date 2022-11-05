var TableDatatablesResponsive = function () {

    var tableExtensible = function () {
        var table = $('#table_extensible');
        var fixedHeaderOffset = 0;

        var oTable = table.dataTable({
            retrieve: true,
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            // Or you can use remote translation file
            "language": {
                url: '//cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json'
            },
            fixedHeader: {
                header: true,
                headerOffset: fixedHeaderOffset
            },
            // setup buttons extentension: http://datatables.net/extensions/buttons/
            buttons: [
                /*  {extend: 'print', className: 'btn dark btn-outline', text: 'Imprimir'},*/
                {extend: 'pdf', className: 'btn green btn-outline'},
                {extend: 'excel', className: 'btn purple btn-outline '}
            ],
            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: true,
            columnDefs: [
                {responsivePriority: 1, "targets": 0},
                {responsivePriority: 2, "targets": 5},
            ],
            "order": [
                //[0, 'asc']
            ],
            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "Todos"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,
            "dom": "<'row' <'col-md-12'>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            tableExtensible();
        }
    };
}();