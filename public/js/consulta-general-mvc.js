$(function () {
    const tabla = $('#tablaTickets').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        responsive: true,
        ajax: {
            url: window.BASE_URL + 'index.php?ruta=consulta-general&action=datos',
            type: 'POST',
            data: function (datos) {
                datos.tipo = $('#tipoFiltro').val();
                datos.inicio = $('input[name="inicio"]').val();
                datos.fin = $('input[name="fin"]').val();
                datos.estado = $('#filtroEstado').val();
                datos.servicio = $('#filtroServicio').val();
                datos.operador = $('#filtroOperador').val();
            }
        },
        language: { url: window.BASE_URL + 'assets/plugins/datatables/es-ES.json' }
    });

    function actualizarExportaciones() {
        const parametros = new URLSearchParams({
            tipo: $('#tipoFiltro').val(),
            inicio: $('input[name="inicio"]').val(),
            fin: $('input[name="fin"]').val(),
            estado: $('#filtroEstado').val(),
            servicio: $('#filtroServicio').val(),
            operador: $('#filtroOperador').val(),
            search: $('#tablaTickets_filter input').val() || ''
        });
        $('#btnExcel').attr('href', window.BASE_URL + 'app/Models/generar_excel.php?' + parametros.toString());
        $('#btnPDF').attr('href', window.BASE_URL + 'app/Models/generar_pdf.php?' + parametros.toString());
    }

    $('#filtroEstado, #filtroServicio, #filtroOperador').on('change', function () {
        tabla.ajax.reload();
        actualizarExportaciones();
    });
    $('#tablaTickets_filter').on('input', 'input', actualizarExportaciones);
    actualizarExportaciones();
});
