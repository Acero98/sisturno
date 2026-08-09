document.addEventListener('DOMContentLoaded', () => {
    let tablaOperadores = null;
    const detallesServicios = {};
    if (window.jQuery && jQuery.fn.DataTable) {
        jQuery('#tablaOperadoresMvc tbody .operator-services-row').each(function () {
            const filaDetalle = jQuery(this);
            const clave = filaDetalle.find('.collapse').attr('id');
            detallesServicios[clave] = filaDetalle.find('.operator-services').html();
            filaDetalle.remove();
        });
        tablaOperadores = jQuery('#tablaOperadoresMvc').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
            order: [[1, 'asc']],
            columnDefs: [{ orderable: false, targets: [8, 9] }],
            dom: '<"d-flex justify-content-end mb-3"l>t<"row mt-3 align-items-center"<"col-md-5"i><"col-md-7"p>>',
            language: { emptyTable: 'No hay datos disponibles en la tabla', info: 'Mostrando _START_ a _END_ de _TOTAL_ operadores', infoEmpty: 'Mostrando 0 a 0 de 0 operadores', zeroRecords: 'No se encontraron operadores coincidentes', paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' } }
        });
        const campoBuscar = document.getElementById('buscarOperadores');
        const filtrar = () => tablaOperadores.search(campoBuscar?.value || '').draw();
        campoBuscar?.addEventListener('input', filtrar);
        document.getElementById('formBuscarOperadores')?.addEventListener('submit', evento => { evento.preventDefault(); filtrar(); });
        jQuery('#tablaOperadoresMvc tbody').on('click', '.btn-services', function (evento) {
            evento.preventDefault();
            const fila = tablaOperadores.row(jQuery(this).closest('tr'));
            const clave = (jQuery(this).attr('data-bs-target') || '').replace('#', '');
            if (fila.child.isShown()) { fila.child.hide(); jQuery(this).attr('aria-expanded', 'false'); }
            else { fila.child(`<div class="operator-services">${detallesServicios[clave] || 'No tiene servicios asignados.'}</div>`).show(); jQuery(this).attr('aria-expanded', 'true'); }
        });
    }
    const confirmarFormulario = (selector, titulo, texto, color) => {
        document.addEventListener('submit', (event) => {
            if (!event.target.matches(selector) || event.target.dataset.confirmado === '1') return;
            event.preventDefault();
            Swal.fire({ title: titulo, text: texto, icon: 'question', showCancelButton: true, confirmButtonColor: color, cancelButtonColor: '#6c757d', confirmButtonText: 'Sí, continuar', cancelButtonText: 'Cancelar' })
                .then((resultado) => { if (resultado.isConfirmed) { event.target.dataset.confirmado = '1'; event.target.requestSubmit(); } });
        });
    };

    confirmarFormulario('.formRegistrarOperador', '¿Registrar operador?', 'Se creará un nuevo operador en el sistema.', '#0f766e');
    confirmarFormulario('.formEditarOperador', '¿Guardar cambios?', 'Se actualizará la información del operador.', '#d97706');
    confirmarFormulario('.formCambiarEstadoOperador', '¿Cambiar estado?', 'El acceso del operador se actualizará.', '#0f766e');

    const mensaje = new URLSearchParams(window.location.search).get('mensaje');
    const mensajes = {
        registrado: ['¡Registrado!', 'El operador fue creado correctamente.', 'success'],
        actualizado: ['¡Actualizado!', 'Los cambios se guardaron correctamente.', 'success'],
        activado: ['Activado', 'El operador ya puede iniciar sesión.', 'success'],
        desactivado: ['Desactivado', 'El operador ya no puede iniciar sesión.', 'success'],
        usuario_existe: ['Usuario duplicado', 'El nombre de usuario ya está registrado.', 'warning'],
        dni_existe: ['DNI duplicado', 'El DNI ya está registrado.', 'warning'],
        obligatorio: ['Datos incompletos', 'Completa todos los campos obligatorios.', 'warning'],
        error: ['No se pudo completar', 'Ocurrió un error al procesar la operación.', 'error']
    };

    if (mensajes[mensaje]) {
        const [titulo, texto, icono] = mensajes[mensaje];
        Swal.fire({ title: titulo, text: texto, icon: icono, confirmButtonColor: '#0f766e' })
            .then(() => window.history.replaceState({}, document.title, `${window.location.pathname}?ruta=operadores`));
    }
});
