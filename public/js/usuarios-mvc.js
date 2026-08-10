document.addEventListener('DOMContentLoaded', () => {
    if (!window.jQuery || !jQuery.fn.DataTable) return;
    const tabla = jQuery('#tablaUsuariosMvc').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
        order: [[1, 'asc']],
        columnDefs: [{ orderable: false, targets: 5 }],
        dom: '<"d-flex justify-content-end mb-3"l>t<"row mt-3 align-items-center"<"col-md-5"i><"col-md-7"p>>',
        language: {
            emptyTable: 'No hay datos disponibles en la tabla',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ usuarios',
            infoEmpty: 'Mostrando 0 a 0 de 0 usuarios',
            zeroRecords: 'No se encontraron usuarios coincidentes',
            paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
        }
    });
    const campo = document.getElementById('buscarUsuarios');
    const filtrar = () => tabla.search(campo?.value || '').draw();
    campo?.addEventListener('input', filtrar);
    document.getElementById('formBuscarUsuarios')?.addEventListener('submit', evento => { evento.preventDefault(); filtrar(); });

    document.addEventListener('submit', (evento) => {
        if (!evento.target.classList.contains('formEditarUsuario')) return;

        evento.preventDefault();
        const formulario = evento.target;
        Swal.fire({
            title: '¿Guardar cambios?',
            text: 'Se actualizará la información del usuario',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((resultado) => {
            if (resultado.isConfirmed) formulario.submit();
        });
    });

    document.addEventListener('click', (evento) => {
        const boton = evento.target.closest('.btnConfirmarRegistro');
        if (!boton) return;

        const formulario = boton.closest('form');
        if (!formulario) return;
        if (!formulario.checkValidity()) {
            formulario.reportValidity();
            return;
        }

        Swal.fire({
            title: '¿Registrar usuario?',
            text: 'Se creará un nuevo usuario en el sistema',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, registrar',
            cancelButtonText: 'Cancelar'
        }).then((resultado) => {
            if (resultado.isConfirmed) formulario.requestSubmit();
        });
    });

    document.addEventListener('click', (evento) => {
        const enlace = evento.target.closest('a[href*="ruta=usuarios"][href*="action=estado"]');
        if (!enlace) return;

        evento.preventDefault();
        const activar = new URL(enlace.href).searchParams.get('estado') === '1';
        Swal.fire({
            title: activar ? '¿Activar usuario?' : '¿Desactivar usuario?',
            text: activar ? 'El usuario podrá iniciar sesión nuevamente.' : 'El usuario no podrá iniciar sesión hasta volver a activarlo.',
            icon: activar ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: activar ? '#198754' : '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: activar ? 'Sí, activar' : 'Sí, desactivar',
            cancelButtonText: 'Cancelar'
        }).then((resultado) => {
            if (resultado.isConfirmed) window.location.href = enlace.href;
        });
    });

    const mensajes = {
        registrado: ['¡Registrado!', 'El registro fue guardado correctamente.', 'success', '#0d6efd'],
        actualizado: ['¡Actualizado!', 'Los cambios se guardaron correctamente.', 'success', '#ffc107'],
        activado: ['Activado', 'El usuario ya puede iniciar sesión.', 'success'],
        desactivado: ['Desactivado', 'El usuario ya no puede iniciar sesión.', 'success'],
        existe: ['Duplicado', 'El usuario ya existe.', 'warning', '#ffc107']
    };
    const mensaje = new URLSearchParams(window.location.search).get('mensaje');
    if (mensajes[mensaje]) {
        const [titulo, texto, icono, color] = mensajes[mensaje];
        Swal.fire({ title: titulo, text: texto, icon: icono, confirmButtonColor: color })
            .then(() => window.history.replaceState({}, document.title, `${window.location.pathname}?ruta=usuarios`));
    }
});
