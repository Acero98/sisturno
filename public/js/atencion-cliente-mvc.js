(function () {
    const rutaAccion = window.BASE_URL + 'index.php?ruta=atencion&action=';

    function mostrarError(mensaje) {
        Swal.fire({ icon: 'error', title: 'Error', text: mensaje });
    }

    function actualizarAtencion() {
        fetch(rutaAccion + 'contenido', { credentials: 'same-origin' })
            .then(function (respuesta) {
                if (!respuesta.ok) throw new Error('No se pudo actualizar la atención');
                return respuesta.text();
            })
            .then(function (html) {
                document.getElementById('contenedorAtencion').innerHTML = html;
            })
            .catch(function (error) {
                console.error(error);
            });
    }

    function ejecutarAccion(accion, boton, confirmacion) {
        Swal.fire(confirmacion).then(function (resultado) {
            if (!resultado.isConfirmed) return;
            boton.disabled = true;
            fetch(rutaAccion + accion, { method: 'POST', credentials: 'same-origin' })
                .then(function (respuesta) { return respuesta.text(); })
                .then(function (respuesta) {
                    if (respuesta.trim() === 'OK') {
                        Swal.fire({ icon: 'success', title: confirmacion.successTitle, timer: 1000, showConfirmButton: false });
                        actualizarAtencion();
                        return;
                    }
                    if (respuesta.trim() === 'SIN_TICKETS') {
                        Swal.fire({ icon: 'warning', title: 'Sin tickets', text: 'No hay tickets disponibles.' });
                        return;
                    }
                    mostrarError('No se pudo procesar la solicitud.');
                })
                .catch(function (error) {
                    console.error(error);
                    mostrarError('No se pudo comunicar con el servidor.');
                })
                .finally(function () { boton.disabled = false; });
        });
    }

    window.llamarTicket = function (boton) {
        const ticket = boton.dataset.ticket;
        ejecutarAccion('llamar', boton, {
            icon: 'question', title: '¿Desea llamar al ticket ' + ticket + '?', text: 'El cliente será llamado a ventanilla.', showCancelButton: true,
            confirmButtonText: 'Sí, llamar', cancelButtonText: 'Cancelar', successTitle: 'Ticket llamado'
        });
    };
    window.comenzarAtencion = function (boton) {
        ejecutarAccion('comenzar', boton, {
            icon: 'question', title: '¿Comenzar la atención?', text: 'El ticket pasará a atención en proceso.', showCancelButton: true,
            confirmButtonText: 'Sí, comenzar', cancelButtonText: 'Cancelar', successTitle: 'Atención iniciada'
        });
    };
    window.finalizarAtencion = function (boton) {
        ejecutarAccion('finalizar', boton, {
            icon: 'question', title: '¿Finalizar la atención?', text: 'Esta acción cerrará el ticket actual.', showCancelButton: true,
            confirmButtonText: 'Sí, finalizar', cancelButtonText: 'Cancelar', successTitle: 'Atención finalizada'
        });
    };
    window.cancelarAtencion = function (boton) {
        ejecutarAccion('cancelar', boton, {
            icon: 'warning', title: '¿Cancelar la atención?', text: 'El ticket actual será cancelado.', showCancelButton: true,
            confirmButtonText: 'Sí, cancelar', cancelButtonText: 'Volver', successTitle: 'Atención cancelada'
        });
    };

    const socket = io(SOCKET_URL);
    socket.on('connect', function () {
        console.log('Atención MVC conectada:', socket.id);
    });
    socket.on('actualizar_pantalla', function () {
        actualizarAtencion();
    });
}());
