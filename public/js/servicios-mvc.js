document.addEventListener('DOMContentLoaded', () => {
    const tabla = window.jQuery && jQuery.fn.DataTable
        ? jQuery('#tablaServiciosMvc').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
            order: [[1, 'asc']],
            columnDefs: [{ orderable: false, targets: 5 }],
            dom: '<"d-flex justify-content-end mb-3"l>t<"row mt-3 align-items-center"<"col-md-5"i><"col-md-7"p>>',
            language: {
                emptyTable: 'No hay datos disponibles en la tabla',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ servicios',
                infoEmpty: 'Mostrando 0 a 0 de 0 servicios',
                zeroRecords: 'No se encontraron servicios coincidentes',
                paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
            }
        })
        : null;
    const campoBuscar = document.getElementById('buscarServicios');
    const filtrar = () => tabla?.search(campoBuscar?.value || '').draw();
    campoBuscar?.addEventListener('input', filtrar);
    document.getElementById('formBuscarServicios')?.addEventListener('submit', (event) => {
        event.preventDefault();
        filtrar();
    });
    const confirmar = (selector, titulo, texto, color) => document.addEventListener('submit', event => {
        if (!event.target.matches(selector) || event.target.dataset.confirmado === '1') return;
        event.preventDefault();
        Swal.fire({
            title: titulo,
            text: texto,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: color,
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then(r => { if (r.isConfirmed) { event.target.dataset.confirmado = '1'; event.target.requestSubmit(); } });
    });
    confirmar('.formRegistrarServicioMvc', '¿Registrar servicio?', 'Se creará un nuevo servicio.', '#0d6efd');
    confirmar('.formEditarServicioMvc', '¿Guardar cambios?', 'Se actualizará la información del servicio.', '#d97706');
    confirmar('.formCambiarEstadoServicio', '¿Cambiar estado?', 'La disponibilidad del servicio se actualizará.', '#0d6efd');
    const mensajes = { registrado: ['¡Registrado!', 'El servicio fue creado correctamente.', 'success'], actualizado: ['¡Actualizado!', 'Los cambios se guardaron correctamente.', 'success'], activado: ['Activado', 'El servicio volverá a mostrarse en la selección.', 'success'], desactivado: ['Desactivado', 'El servicio dejó de mostrarse en la selección.', 'success'], existe: ['Código duplicado', 'El código ya está registrado.', 'warning'], obligatorio: ['Datos incompletos', 'Completa todos los campos obligatorios.', 'warning'], error: ['No se pudo completar', 'Ocurrió un error al procesar la operación.', 'error'] };
    const mensaje = new URLSearchParams(window.location.search).get('mensaje'); if (mensajes[mensaje]) { const [titulo, texto, icono] = mensajes[mensaje]; Swal.fire({ title: titulo, text: texto, icon: icono, confirmButtonColor: '#0d6efd' }).then(() => window.history.replaceState({}, document.title, window.location.pathname + '?ruta=servicios')); }
});
