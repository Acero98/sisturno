function llamarTicket(btn) {

    let ticket = btn.dataset.ticket;

    Swal.fire({
        title: `¿Desea llamar al ticket ${ticket}?`,
        html: `
            <div style="font-size: 1.1rem;">
                El cliente será llamado a ventanilla.
            </div>
        `,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, llamar",
        cancelButtonText: "Cancelar"
        //reverseButtons: true
    }).then((result) => {

        if (result.isConfirmed) {

            fetch("../../controlador/atencion/llamar_ticket.php", {
                method: "POST"
            })
                .then(response => response.text())
                .then(data => {

                    if (data.trim() === "OK") {

                        Swal.fire({
                            icon: "success",
                            title: "Ticket llamado",
                            html: `
                            <div style="font-size: 2.5rem; font-weight: bold; color: #0d6efd;">
                                ${ticket}
                            </div>
                            <br>
                            <div style="font-size: 1.1rem;">
                                Diríjase a ventanilla
                            </div>
                        `,
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: true
                        });

                        actualizarAtencion();

                    } else {
                        Swal.fire({
                            icon: "warning",
                            title: "Sin tickets",
                            text: "No hay tickets disponibles"
                        });
                    }

                })
                .catch(error => {
                    console.error(error);

                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "No se pudo procesar la solicitud"
                    });
                });

        }

    });
}
``

/*
function llamarTicket() {

    fetch("../../controlador/atencion/llamar_ticket.php", {
        method: "POST"
    })
        .then(response => response.text())
        .then(data => {

            if (data.trim() === "OK") {

                actualizarAtencion();
                
                |--------------------------------------------------------------------------
                | YA NO USAMOS location.reload()
                |--------------------------------------------------------------------------
                | El controlador llamar_ticket.php:
                | 1. Actualiza el ticket en la base de datos.
                | 2. Ejecuta notificar_socket.php.
                | 3. El servidor WebSocket emite "actualizar_pantalla".
                | 4. atencion_cliente.js escucha ese evento.
                | 5. Se ejecuta actualizarContenidoAtencion().
                | 6. Solo se refresca el contenedor #contenedorAtencion.
                |--------------------------------------------------------------------------
                |
                | Por lo tanto, aquí no hacemos nada adicional.
                |
                

                console.log("Ticket llamado correctamente. Esperando actualización vía WebSocket...");
                //location.reload();
            } else {
                Swal.fire({
                    icon: "warning",
                    title: "Sin tickets",
                    text: "No hay tickets disponibles"
                });
            }

        })
        .catch(error => {
            console.error("Error:", error);

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudo procesar la solicitud"
            });
        });

    /*
    else {
        Swal.fire("Sin tickets", "No hay tickets disponibles", "warning");
    }

})
.catch(error => {
    console.error("Error:", error);
});*/
//}

function comenzarAtencion(btn) {

    let ticket = btn.dataset.ticket;

    Swal.fire({
        title: `¿Desea iniciar la atención de ${ticket}?`,
        html: `
            <div style="font-size: 1.1rem;">
                Presione en continuar para comenzar con la operación
            </div>
        `,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, continuar!",
        cancelButtonText: "Cancelar"
        //reverseButtons: true
    }).then((result) => {

        if (result.isConfirmed) {

            fetch("../../controlador/atencion/comenzar_atencion.php", {
                method: "POST"
            })
                .then(response => response.text())
                .then(data => {

                    if (data.trim() === "OK") {

                        Swal.fire({
                            icon: "success",
                            title: "Atención iniciada",
                            text: `Se inició la atención de ${ticket}`,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        actualizarAtencion();

                    } else {
                        Swal.fire("Error", "No se pudo iniciar la atención.", "error");
                    }

                })
                .catch(error => {
                    console.error(error);
                    Swal.fire("Error", "Problema con el servidor", "error");
                });

        }
    });
}

/*
function comenzarAtencion() {

    fetch("../../controlador/atencion/comenzar_atencion.php", {
        method: "POST"
    })
        .then(response => response.text())
        .then(data => {

            if (data.trim() === "OK") {

                Swal.fire({
                    icon: "success",
                    title: "Atención iniciada",
                    text: "El ticket está siendo atendido",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });

                actualizarAtencion();
                // Recargar la página para actualizar la interfaz
                //location.reload();
            } else {
                Swal.fire(
                    "Error",
                    "No se pudo iniciar la atención.",
                    "error"
                );
            }

        })
        .catch(error => {
            console.error("Error:", error);

            Swal.fire(
                "Error",
                "Ocurrió un problema al comunicarse con el servidor.",
                "error"
            );
        });
}*/

function cancelarAtencion(btn) {

    let ticket = btn.dataset.ticket;

    Swal.fire({
        title: `¿Cancelar la atención de ${ticket}?`,
        html: `
            <div style="font-size: 1.1rem;">
                El ticket será marcado como cancelado.
            </div>
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, finalizar",
        cancelButtonText: "Cancelar"
        //reverseButtons: true
    }).then((result) => {

        if (result.isConfirmed) {

            fetch("../../controlador/atencion/cancelar_atencion.php", {
                method: "POST"
            })
                .then(response => response.text())
                .then(data => {

                    if (data.trim() === "OK") {

                        Swal.fire({
                            icon: "success",
                            title: "Atención cancelada",
                            text: `El ticket ${ticket} fue cancelado`,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        actualizarAtencion();

                    } else {
                        Swal.fire(
                            "Error",
                            "No se pudo cancelar la atención.",
                            "error"
                        );
                    }

                })
                .catch(error => {
                    console.error(error);

                    Swal.fire(
                        "Error",
                        "Ocurrió un problema al comunicarse con el servidor.",
                        "error"
                    );
                });
        }
    });
}
``

/*function cancelarAtencion() {

    Swal.fire({
        title: "¿Cancelar atención?",
        text: "El ticket será marcado como cancelado.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, cancelar",
        cancelButtonText: "No"
    }).then((result) => {

        if (result.isConfirmed) {

            fetch("../../controlador/atencion/cancelar_atencion.php", {
                method: "POST"
            })
                .then(response => response.text())
                .then(data => {

                    if (data.trim() === "OK") {
                        actualizarAtencion();
                        //location.reload();
                    } else {
                        Swal.fire(
                            "Error",
                            "No se pudo cancelar la atención.",
                            "error"
                        );
                    }

                })
                .catch(error => {
                    console.error(error);

                    Swal.fire(
                        "Error",
                        "Ocurrió un problema al comunicarse con el servidor.",
                        "error"
                    );
                });
        }
    });
}*/

function finalizarAtencion(btn) {

    let ticket = btn.dataset.ticket;

    Swal.fire({
        title: `¿Finalizar la atención de ${ticket}?`,
        html: `
            <div style="font-size: 1.1rem;">
                El ticket será marcado como finalizado.
            </div>
        `,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, finalizar",
        cancelButtonText: "Cancelar"
        //reverseButtons: true
    }).then((result) => {

        if (result.isConfirmed) {

            fetch("../../controlador/atencion/finalizar_atencion.php", {
                method: "POST"
            })
                .then(response => response.text())
                .then(data => {

                    if (data.trim() === "OK") {

                        Swal.fire({
                            icon: "success",
                            title: "Atención finalizada",
                            text: `El ticket ${ticket} fue finalizado correctamente`,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        actualizarAtencion();

                    } else {
                        Swal.fire(
                            "Error",
                            "No se pudo finalizar la atención.",
                            "error"
                        );
                    }

                })
                .catch(error => {
                    console.error(error);

                    Swal.fire(
                        "Error",
                        "Ocurrió un problema al comunicarse con el servidor.",
                        "error"
                    );
                });
        }
    });
}
``

/*
function finalizarAtencion() {

    Swal.fire({
        title: "¿Finalizar atención?",
        text: "El ticket será marcado como finalizado.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, finalizar",
        cancelButtonText: "No"
    }).then((result) => {

        if (result.isConfirmed) {

            fetch("../../controlador/atencion/finalizar_atencion.php", {
                method: "POST"
            })
                .then(response => response.text())
                .then(data => {

                    if (data.trim() === "OK") {
                        actualizarAtencion();
                        //location.reload();
                    } else {
                        Swal.fire(
                            "Error",
                            "No se pudo finalizar la atención.",
                            "error"
                        );
                    }

                })
                .catch(error => {
                    console.error(error);

                    Swal.fire(
                        "Error",
                        "Ocurrió un problema al comunicarse con el servidor.",
                        "error"
                    );
                });
        }
    });
}*/

/* ==========================================================
   WEBSOCKET - ACTUALIZAR ATENCIÓN EN TIEMPO REAL
   ========================================================== 
const socket = io(SOCKET_URL);

socket.on("connect", function () {
    console.log("Atención Cliente conectado:", socket.id);
});

socket.on("actualizar_pantalla", function (data) {
    console.log("Evento recibido en Atención Cliente:", data);

    // Recargar la vista completa para actualizar:
    // - Ticket actual
    // - Botones (Llamar, Comenzar, Finalizar)
    // - Lista de próximos tickets
    location.reload();
});*/

function actualizarAtencion() {
    fetch("contenido_atencion.php")
        .then(response => response.text())
        .then(html => {
            document.getElementById("contenedorAtencion").innerHTML = html;
        })
        .catch(error => {
            console.error("Error al actualizar atención:", error);
        });
}

const socket = io(SOCKET_URL);

socket.on("connect", function () {
    console.log("Conectado al WebSocket:", socket.id);
});

socket.on("actualizar_pantalla", function (data) {
    //console.log("Evento recibido:", data);
    actualizarAtencion();
});