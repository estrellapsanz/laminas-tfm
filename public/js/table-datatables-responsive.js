var TableDatatablesResponsive = function () {

    var tableExtensible = function () {
        var table = $('#table_extensible');
        var fixedHeaderOffset = 0;
        // if (App.getViewPort().width < App.getResponsiveBreakpoint('md')) {
        //     if ($('.page-header').hasClass('page-header-fixed-mobile')) {
        //         fixedHeaderOffset = $('.page-header').outerHeight(true);
        //     }
        // }
        //NUEVA FUNCION PARA ORDENAR FECHAS AUTOMATICAMENTE SEGUN EL FORMATO!!
        /*$.fn.dataTable.moment = function (format, locale) {
            var types = $.fn.dataTable.ext.type;

            // Add type detection
            types.detect.unshift(function (d) {
                return moment(d, format, locale, true).isValid() ?
                    'moment-' + format :
                    null;
            });

            // Add sorting method - use an integer for the sorting
            types.order['moment-' + format + '-pre'] = function (d) {
                return moment(d, format, locale, true).unix();
            };
        };
        //ESTABLECEMOS EL FORMATO POSIBLE EN LA TABLA, en caso de haber varios se duplica la linea por cada formato necesario
        $.fn.dataTable.moment('DD/MM/YYYY hh:mm:ss');*/

        var oTable = table.dataTable({
            retrieve: true,
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            // Or you can use remote translation file
            //  "language": {
            //      url: '/plantilla/assets/global/plugins/datatables/traduccion.json'
            //  },
            fixedHeader: {
                header: true,
                headerOffset: fixedHeaderOffset
            },
            // setup buttons extentension: http://datatables.net/extensions/buttons/
            buttons: [
                /*
                    { extend: 'print', className: 'btn dark btn-outline', text: 'Imprimir' },
                    //{ extend: 'pdf', className: 'btn green btn-outline' },
                    //{ extend: 'excel', className: 'btn purple btn-outline ' }
                    */
            ],
            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: {
                breakpoints: [
                    {name: 'screen-xs', width: 600}
                ],
                /*
                details: {
                    renderer: function ( api, rowIdx, columns ) {
                        sOut = '';

                        if(columns[0].hidden)
                            sOut += '<tr><td>Tipo Solicitud:</td><td>' + columns[0].data + '</td></tr>';
                        if(columns[1].hidden)
                            sOut += '<tr><td>Fecha Solicitud:</td><td>' + columns[1].data + '</td></tr>';
                        if(columns[2].hidden)
                            sOut += '<tr><td>Máster:</td><td>' + columns[2].data + '</td></tr>';

                        if (columns[0].data == "") {
                            if(columns[3].hidden)
                                sOut += '<tr><td>Asignatura:</td><td>' + columns[3].data + '</td></tr>';
                            sOut += '<tr><td>Créditos:</td><td>' + columns[11].data + '</td></tr>';
                            sOut += '<tr><td>Semestre:</td><td>' + columns[15].data + '</td></tr>';
                            sOut += '<tr><td>Horas:</td><td>' + columns[10].data + '</td></tr>';
                            sOut += '<tr><td>Docente:</td><td>' + columns[9].data + '</td></tr>';
                        
                        }
                        

                        return $('<table/>').append(sOut);
                    }
                }
                */
            },
            columnDefs: [
                //className: 'screen-xs', targets: [0 },
                {"width": "5%", responsivePriority: 1, "targets": 0},
                {"width": "5%", responsivePriority: 2, "targets": 1},
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
            "dom": "<'row' <'col-md-12'>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'fB>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
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