$(document).ready(function () {

    let tabla = $('#tablaTickets').DataTable({

        processing: true,
        serverSide: true,

        ajax: {
            url: '../../controlador/reportes/consulta_general_ajax.php',
            type: 'POST',
            data: function (d) {

                d.tipo = $('#tipoFiltro').val();
                d.inicio = $('input[name="inicio"]').val();
                d.fin = $('input[name="fin"]').val();

                d.estado = $('#filtroEstado').val();
                d.servicio = $('#filtroServicio').val();
                d.operador = $('#filtroOperador').val();
            }
        },

        pageLength: 10,

        responsive: true,

        language: {
            url: "../../assets/plugins/datatables/es-ES.json"
        }

    });

    // ACTUALIZAR EXPORTACIONES AL INICIO
    actualizarExportaciones();

    // FILTROS DINÁMICOS

    $('#filtroEstado').change(function () {

        tabla.ajax.reload();

        actualizarExportaciones();
    });

    $('#filtroServicio').change(function () {

        tabla.ajax.reload();

        actualizarExportaciones();
    });

    $('#filtroOperador').change(function () {

        tabla.ajax.reload();

        actualizarExportaciones();
    });

    $('#tipoFiltro').change(function () {

        tabla.ajax.reload();

        actualizarExportaciones();
    });

    $('input[name="inicio"]').change(function () {

        tabla.ajax.reload();

        actualizarExportaciones();
    });

    $('input[name="fin"]').change(function () {

        tabla.ajax.reload();

        actualizarExportaciones();
    });

    // BUSCADOR DATATABLE
    $('#tablaTickets_filter input').on('keyup', function () {

        actualizarExportaciones();
    });

    // FUNCION EXPORTACIONES

    function actualizarExportaciones() {

        let inicio = $('input[name="inicio"]').val();
        let fin = $('input[name="fin"]').val();

        let estado = $('#filtroEstado').val();
        let servicio = $('#filtroServicio').val();
        let operador = $('#filtroOperador').val();

        let busqueda = $('.dataTables_filter input').val() || '';

        let tipo = $('#tipoFiltro').val();

        let params =
            '?tipo=' + encodeURIComponent(tipo) +
            '&inicio=' + encodeURIComponent(inicio) +
            '&fin=' + encodeURIComponent(fin) +
            '&estado=' + encodeURIComponent(estado) +
            '&servicio=' + encodeURIComponent(servicio) +
            '&operador=' + encodeURIComponent(operador) +
            '&search=' + encodeURIComponent(busqueda);

        $('#btnExcel').attr(
            'href',
            '../../controlador/reportes/generar_excel.php' + params
        );

        $('#btnPDF').attr(
            'href',
            '../../controlador/reportes/generar_pdf.php' + params
        );

        console.log("INICIO:", inicio);
        console.log("FIN:", fin);
    }

});


/*$(document).ready(function () {

    $('#tablaTickets').DataTable({

        processing: true,
        serverSide: true,

        ajax: {
            url: '../../controlador/reportes/consulta_general_ajax.php',
            type: 'POST',

            data: function (d) {

                d.tipo = $('button[name="tipo"]').val() || 'hoy';

                d.inicio = $('input[name="inicio"]').val();

                d.fin = $('input[name="fin"]').val();
            }
        },

        pageLength: 10,

        responsive: true,

        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }

    });

});*/


/*$(document).ready(function () {

    $('#tablaTickets').DataTable({

        pageLength: 10,
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }

    });

});*/