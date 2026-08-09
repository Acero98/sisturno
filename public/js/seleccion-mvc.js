(function () {
    const rutaAccion = window.BASE_URL + 'index.php?ruta=seleccion&action=';

    function actualizarSeleccion() {
        fetch(rutaAccion + 'contenido', { credentials: 'same-origin' })
            .then(function (respuesta) {
                if (!respuesta.ok) throw new Error('No se pudo actualizar la selección');
                return respuesta.text();
            })
            .then(function (html) {
                document.getElementById('contenedorSeleccion').innerHTML = html;
            })
            .catch(function (error) {
                console.error('Error al actualizar la selección:', error);
            });
    }

    document.addEventListener('submit', function (evento) {
        const formulario = evento.target;
        if (!formulario.classList.contains('form-generar-ticket')) return;
        evento.preventDefault();

        const boton = formulario.querySelector('button[type="submit"]');
        const servicio = boton.textContent.trim();
        Swal.fire({
            icon: 'question',
            title: '¿Generar e imprimir ticket?',
            text: 'Servicio: ' + servicio,
            showCancelButton: true,
            confirmButtonText: 'Sí, generar e imprimir',
            cancelButtonText: 'Cancelar'
        }).then(function (confirmacion) {
            if (!confirmacion.isConfirmed) return;
            const datos = new FormData(formulario);
            datos.append('imprimir', '1');
            boton.disabled = true;

            return fetch(rutaAccion + 'generar-ticket', {
                method: 'POST',
                body: datos,
                credentials: 'same-origin'
            })
                .then(function (respuesta) { return respuesta.text(); })
                .then(function (ticket) {
                    ticket = ticket.trim();
                    if (!ticket || ticket === 'ERROR') {
                        throw new Error('No se pudo generar el ticket');
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'TICKET GENERADO',
                        html: '<div style="font-size: 3rem; font-weight: bold; color: #0d6efd;">' + ticket + '</div><br><div style="font-size: 1.2rem; color: #6c757d;">Espere su turno, por favor.</div>',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                    actualizarSeleccion();
                })
                .catch(function (error) {
                    console.error(error);
                    Swal.fire('Error', 'No se pudo generar el ticket.', 'error');
                })
                .finally(function () { boton.disabled = false; });
        });
    });

    const socket = io(SOCKET_URL);
    socket.on('connect', function () {
        console.log('Selección MVC conectada:', socket.id);
    });
    socket.on('actualizar_pantalla', function () {
        actualizarSeleccion();
    });
}());
