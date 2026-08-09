document.addEventListener('DOMContentLoaded', () => {
    if (!window.jQuery || !jQuery.fn.DataTable) return;
    const tabla = jQuery('#tablaUsuariosMvc').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
        order: [[1, 'asc']],
        columnDefs: [{ orderable: false, targets: 5 }],
        dom: '<"d-flex justify-content-end mb-3"l>t<"row mt-3 align-items-center"<"col-md-5"i><"col-md-7"p>>',
        language: { emptyTable: 'No hay datos disponibles en la tabla', info: 'Mostrando _START_ a _END_ de _TOTAL_ usuarios', infoEmpty: 'Mostrando 0 a 0 de 0 usuarios', zeroRecords: 'No se encontraron usuarios coincidentes', paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' } }
    });
    const campo = document.getElementById('buscarUsuarios');
    const filtrar = () => tabla.search(campo?.value || '').draw();
    campo?.addEventListener('input', filtrar);
    document.getElementById('formBuscarUsuarios')?.addEventListener('submit', evento => { evento.preventDefault(); filtrar(); });
});
